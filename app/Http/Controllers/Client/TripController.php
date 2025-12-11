<?php

namespace App\Http\Controllers\Client;

use App\Models\Trip;
use App\Models\Ticket;
use App\Models\PickupDropoffPoint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PickupPoint;
use App\Models\DropoffPoint;
use App\Models\City;

class TripController extends Controller
{
    public function searchTrips(Request $request)
    {
        return $this->index($request);
    }

    public function index(Request $request)
    {
        // Lấy dữ liệu từ Request
        // Dùng intval() cho ID để đảm bảo chúng là số và tránh lỗi LIKE
        $from = intval(trim($request->input('from_city_id')));
        $to = intval(trim($request->input('to_city_id')));
        $date = $request->input('departure_date');
        $seats = (int) $request->input('seats', 1);
        $timeFilters = (array) $request->input('time', []);
        $busTypes = (array) $request->input('bus_type', []);
        $rows = (array) $request->input('row', []);

        $query = Trip::with([
            'route.fromCity',       // Thêm load City
            'route.toCity',         // Thêm load City
            'route.pickupPoints',
            'route.dropoffPoints',
            'bus',
            // Chỉ load tickets nếu cần tính availableSeats, nếu không thì nên tối ưu
            'tickets'
        ])
            ->where('status', 1) // Chỉ lấy các chuyến đi đang hoạt động (Active)
            ->when($from, fn($q) => $q->whereHas(
                'route',
                // Sửa LỖI: Dùng toán tử bằng (=) cho ID
                fn($qr) => $qr->where('from_city_id', $from)
            ))
            ->when($to, fn($q) => $q->whereHas(
                'route',
                // Sửa LỖI: Dùng toán tử bằng (=) cho ID
                fn($qr) => $qr->where('to_city_id', $to)
            ))
            ->when(
                $date,
                fn($q) => $q->whereDate('departure_date', $date)
            )
            ->when($timeFilters, function ($q) use ($timeFilters) {
                // Nhóm các điều kiện OR cho thời gian
                $q->where(function ($query) use ($timeFilters) {
                    if (in_array('morning', $timeFilters)) {
                        $query->orWhereBetween('departure_time', ['00:00:00', '11:59:59']);
                    }
                    if (in_array('afternoon', $timeFilters)) {
                        $query->orWhereBetween('departure_time', ['12:00:00', '17:59:59']);
                    }
                    if (in_array('evening', $timeFilters)) {
                        $query->orWhereBetween('departure_time', ['18:00:00', '23:59:59']);
                    }
                });
            })
            ->when(
                $busTypes,
                fn($q) => $q->whereHas(
                    'bus',
                    fn($qr) => $qr->whereIn('type', $busTypes)
                )
            );

        $trips = $query
            ->orderBy('departure_date')
            ->orderBy('departure_time')
            ->get();

        // --- Lọc trên Collection (Bộ nhớ PHP) ---

        // 1. Filter theo số ghế trống
        if ($seats > 0) {
            // Giả định $trip->availableSeats() là method tồn tại trong model Trip
            $trips = $trips->filter(fn($trip) => $trip->availableSeats() >= $seats)->values();
        }

        // 2. Filter theo dãy ghế
        if (!empty($rows)) {
            // Giả định $trip->availableSeatsInRows($rows) là method tồn tại trong model Trip
            $trips = $trips->filter(fn($trip) => $trip->availableSeatsInRows($rows) >= $seats)->values();
        }

        // Tải lại danh sách cities cho form tìm kiếm/lọc trên view
        $allFrom = City::where('status', 1)->orderBy('name', 'asc')->get();
        $allTo = City::where('status', 1)->orderBy('name', 'asc')->get();

        // Trả về view kết quả tìm kiếm
        return view('client.trips.index', compact('trips', 'allFrom', 'allTo'));
    }

    /**
     * Show chi tiết chuyến + chọn ghế.
     * Route hiện tại dùng ?trip_id=... nên mình giữ lại cho đỡ phải sửa route.
     */
    public function show(Request $request)
    {
        // Lấy trip_id từ request
        $tripId = $request->input('trip_id');

        // Lấy trip theo ID, load quan hệ
        $trip = Trip::with([
            'route.fromCity',
            'route.toCity',
            'route.pickupPoints',
            'route.dropoffPoints',
            'bus',
            'tickets',
            'bookings',
        ])->findOrFail($tripId);

        // Gọi method selectSeat, có thể truyền tripId hoặc $trip trực tiếp
        return $this->selectSeat($tripId);
    }


    /**
     * Trang chọn ghế cho 1 trip cụ thể.
     * Có thể gắn thêm route kiểu /trips/{trip}/select-seat dùng method này luôn.
     */
    public function selectSeat($tripId)
    {
        // Loại bỏ 'tickets.passengers' khỏi Eager Loading
        $trip = Trip::with(['route', 'tickets'])->findOrFail($tripId);
        $route = $trip->route;

        // Ghế đã bán
        $bookedSeats = $trip->getBookedSeats();

        // Điểm đón / trả
        $pickupPoints = PickupDropoffPoint::where('route_id', $route->id)
            ->where('type', 'pickup')
            ->orderBy('created_at')
            ->get();

        $dropoffPoints = PickupDropoffPoint::where('route_id', $route->id)
            ->where('type', 'dropoff')
            ->orderBy('created_at')
            ->get();

        return view('client.trips.show', [
            'trip' => $trip,
            'bookedSeats' => $bookedSeats,
            'pickupPoints' => $pickupPoints,
            'dropoffPoints' => $dropoffPoints,
        ]);
    }

}
