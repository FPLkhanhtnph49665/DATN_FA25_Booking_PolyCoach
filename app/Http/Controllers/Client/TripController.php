<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trip;

class TripController extends Controller
{
    public function searchTrips(Request $request)
    {
        // dùng chung logic với index
        return $this->index($request);
    }

    public function index(Request $request)
    {
        // ====== Form chính ======
        $from  = trim($request->input('from'));
        $to    = trim($request->input('to'));
        $date  = $request->input('date');
        $seats = (int) $request->input('seats', 1);

        // ====== Bộ lọc bên trái ======
        $timeFilters = (array) $request->input('time', []);     // ['sang', 'chieu', ...]
        $busTypes    = (array) $request->input('bus_type', []); // ['ghe','giuong','limousine']
        $rows        = (array) $request->input('row', []);      // ['front','middle','back']

        // ====== BUILD QUERY CƠ BẢN (KHÔNG ĐỤNG so_ghe_trong, row) ======
        $query = Trip::with(['route', 'bus', 'tickets.passengers'])
            ->active()

            // 1. Điểm đi
            ->when($from !== '', function ($q) use ($from) {
                $q->whereHas('route', function ($qr) use ($from) {
                    $qr->where('diem_di', 'like', "%{$from}%");
                });
            })

            // 2. Điểm đến
            ->when($to !== '', function ($q) use ($to) {
                $q->whereHas('route', function ($qr) use ($to) {
                    $qr->where('diem_den', 'like', "%{$to}%");
                });
            })

            // 3. Ngày đi
            ->when(!empty($date), function ($q) use ($date) {
                $q->whereDate('ngay_khoi_hanh', $date);
            })

            // 4. Khung giờ đi
            ->when(!empty($timeFilters), function ($q) use ($timeFilters) {
                $q->where(function ($query) use ($timeFilters) {
                    if (in_array('sang', $timeFilters)) {
                        $query->orWhereBetween('gio_khoi_hanh', ['00:00:00', '05:59:59']);
                    }
                    if (in_array('sang2', $timeFilters)) {
                        $query->orWhereBetween('gio_khoi_hanh', ['06:00:00', '11:59:59']);
                    }
                    if (in_array('chieu', $timeFilters)) {
                        $query->orWhereBetween('gio_khoi_hanh', ['12:00:00', '17:59:59']);
                    }
                    if (in_array('toi', $timeFilters)) {
                        $query->orWhereBetween('gio_khoi_hanh', ['18:00:00', '23:59:59']);
                    }
                });
            })

            // 5. Loại xe
            ->when(!empty($busTypes), function ($q) use ($busTypes) {
                $q->whereHas('bus', function ($qr) use ($busTypes) {
                    $qr->whereIn('loai_xe', $busTypes);
                });
            });

        // Lấy toàn bộ trips theo điều kiện trên
        $trips = $query
            ->orderBy('ngay_khoi_hanh')
            ->get();

        // ====== LỌC THEO SỐ GHẾ TRỐNG BẰNG ACCESSOR so_ghe_trong ======
        if ($seats > 0) {
            $trips = $trips->filter(function ($trip) use ($seats) {
                // so_ghe_trong là accessor trong Trip model
                return $trip->so_ghe_trong >= $seats;
            })->values();
        }

        // ====== LỌC THEO HÀNG GHẾ (front / middle / back) ======
        if (!empty($rows)) {
            // chỉ giữ các giá trị hợp lệ
            $rows = array_values(array_intersect($rows, ['front', 'middle', 'back']));

            if (!empty($rows)) {
                // Map hàng ghế -> danh sách mã ghế
                $seatRows = [
                    'front'  => ['A01','A02','A03','A04','B01','B02','B03','B04'],
                    'middle' => [
                        'A05','A06','A07','A08','A09','A10','A11','A12',
                        'B05','B06','B07','B08','B09','B10','B11','B12',
                    ],
                    'back'   => ['A13','A14','A15','A16','B13','B14','B15','B16','B17'],
                ];

                // Full sơ đồ ghế
                $fullSeats = array_values(array_unique(array_merge(
                    $seatRows['front'],
                    $seatRows['middle'],
                    $seatRows['back'],
                )));

                $trips = $trips->filter(function ($trip) use ($rows, $seatRows, $fullSeats, $seats) {
                    // Lấy danh sách ghế đã đặt từ tickets.passengers (cột seat_code)
                    $bookedSeats = [];

                    if ($trip->relationLoaded('tickets')) {
                        $bookedSeats = $trip->tickets
                            ->flatMap(function ($ticket) {
                                return $ticket->passengers ?? collect();
                            })
                            ->pluck('seat_code')
                            ->filter()
                            ->map(function ($code) {
                                return strtoupper(trim($code));
                            })
                            ->values()
                            ->all();
                    }

                    // Ghế còn trống = fullSeats - bookedSeats
                    $availableSeats = array_values(array_diff($fullSeats, $bookedSeats));

                    // Ghế thuộc các hàng được chọn
                    $allowedSeats = [];
                    foreach ($rows as $r) {
                        if (isset($seatRows[$r])) {
                            $allowedSeats = array_merge($allowedSeats, $seatRows[$r]);
                        }
                    }
                    $allowedSeats = array_values(array_unique($allowedSeats));

                    // Giao giữa "ghế trống" và "ghế thuộc hàng đã chọn"
                    $availableInRows = array_values(array_intersect($availableSeats, $allowedSeats));

                    // Điều kiện: phải đủ số ghế yêu cầu trong các hàng này
                    $needSeats = max($seats, 1);

                    return count($availableInRows) >= $needSeats;
                })->values();
            }
        }

        return view('client.trips.index', compact('trips'));
    }

    public function show(Request $request)
    {
        // /dat-ve?trip_id=...
        $tripId = $request->query('trip_id');

        if (!$tripId) {
            abort(404, 'Thiếu trip_id');
        }

        $trip = Trip::with(['route', 'bus', 'tickets.passengers'])
            ->findOrFail($tripId);

        // TODO: có thể tái sử dụng logic trên để tính $bookedSeats
        // tạm thời để rỗng (chưa chặn chọn ghế đã đặt)
        $bookedSeats = [];

        return view('client.trips.show', compact('trip', 'bookedSeats'));
    }
}
