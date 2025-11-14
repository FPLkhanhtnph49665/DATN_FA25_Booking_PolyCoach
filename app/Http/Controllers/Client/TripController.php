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

        // ====== BUILD QUERY CƠ BẢN (KHÔNG ĐỤNG so_ghe_trong) ======
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

        // (Nếu sau này cần paginate, ta sẽ custom LengthAwarePaginator từ collection,
        // còn hiện tại dùng collection là đủ để hiển thị)
        return view('client.trips.index', compact('trips'));
    }

    public function show(Trip $trip)
    {
        $trip->load('route', 'bus', 'tickets.passengers');

        return view('client.trips.show', compact('trip'));
    }
}
