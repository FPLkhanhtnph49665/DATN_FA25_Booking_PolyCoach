<?php

namespace App\Http\Controllers\Client;

use App\Models\Trip;
use App\Models\Ticket;
use App\Models\PickupDropoffPoint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TripController extends Controller
{
    public function searchTrips(Request $request)
    {
        return $this->index($request);
    }

    public function index(Request $request)
    {
        $from  = trim($request->input('from'));
        $to    = trim($request->input('to'));
        $date  = $request->input('date');
        $seats = (int) $request->input('seats', 1);

        $timeFilters = (array) $request->input('time', []);
        $busTypes    = (array) $request->input('bus_type', []);
        $rows        = (array) $request->input('row', []);

<<<<<<< HEAD
        // ====== BUILD QUERY CƠ BẢN (KHÔNG ĐỤNG so_ghe_trong) ======
        $query = Trip::with([
                'route.pickupPoints',  // Load điểm đón
                'route.dropoffPoints', // Load điểm trả
                'bus', 
                'tickets.passengers'
            ])
=======
        $query = Trip::with(['route.fromCity', 'route.toCity', 'bus', 'tickets.passengers'])
>>>>>>> 8b2d99e384608832df1c473dc5dbf415fe251c7f
            ->active()
            ->when($from, fn($q) =>
                $q->whereHas('route.fromCity', fn($qr) =>
                    $qr->where('name', 'like', "%$from%")
                )
            )
            ->when($to, fn($q) =>
                $q->whereHas('route.toCity', fn($qr) =>
                    $qr->where('name', 'like', "%$to%")
                )
            )
            ->when($date, fn($q) =>
                $q->whereDate('departure_date', $date)
            )
            ->when($timeFilters, function ($q) use ($timeFilters) {
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
            ->when($busTypes, fn($q) =>
                $q->whereHas('bus', fn($qr) =>
                    $qr->whereIn('type', $busTypes)
                )
            );

        $trips = $query
            ->orderBy('departure_date')
            ->orderBy('departure_time')
            ->get();

        // Filter theo số ghế trống
        if ($seats > 0) {
            $trips = $trips->filter(fn($trip) => $trip->availableSeats() >= $seats)->values();
        }

        // Filter theo dãy ghế
        if (!empty($rows)) {
            $trips = $trips->filter(fn($trip) => $trip->availableSeatsInRows($rows) >= $seats)->values();
        }

        return view('client.trips.index', compact('trips'));
    }

    /**
     * Show chi tiết chuyến + chọn ghế.
     * Route hiện tại dùng ?trip_id=... nên mình giữ lại cho đỡ phải sửa route.
     */
    public function show(Request $request)
    {
<<<<<<< HEAD
        // ====== UPDATE 2: Load dữ liệu cho trang Chi tiết/Đặt vé ======
        // Load route kèm theo danh sách điểm đón/trả để hiển thị vào Select box
        $trip->load([
            'route.pickupPoints', 
            'route.dropoffPoints', 
            'bus', 
            'tickets.passengers'
        ]);

        // Dùng lại logic của selectSeat cho đồng nhất
        return $this->selectSeat($tripId);
    }

    /**
     * Trang chọn ghế cho 1 trip cụ thể.
     * Có thể gắn thêm route kiểu /trips/{trip}/select-seat dùng method này luôn.
     */
    public function selectSeat($tripId)
    {
        $trip = Trip::with([
            'bus.seats',
            'route.fromCity',
            'route.toCity',
        ])->findOrFail($tripId);

        $route = $trip->route;

        // GHẾ ĐÃ BÁN cho trip này
        $bookedSeats = Ticket::where('trip_id', $trip->id)
            ->pluck('seat_code') // "A01,A02"
            ->flatMap(function ($codes) {
                return array_filter(array_map('trim', explode(',', $codes)));
            })
            ->unique()
            ->values()
            ->all();

        // GHẾ CỦA XE, group theo tầng
        $seatsByFloor = $trip->bus->seats
            ->where('status', 1)              // chỉ ghế đang sử dụng
            ->sortBy(['floor', 'col', 'row']) // để layout đẹp
            ->groupBy('floor');

        // CHECKPOINT ĐIỂM ĐÓN / ĐIỂM TRẢ
        $pickupPoints  = collect();
        $dropoffPoints = collect();

        if ($route) {
            $pickupPoints = PickupDropoffPoint::where('route_id', $route->id)
                ->where('type', 'pickup')   // chỉnh nếu cột của bạn khác
                ->get();

            $dropoffPoints = PickupDropoffPoint::where('route_id', $route->id)
                ->where('type', 'dropoff')
                ->get();
        }

        // View ghế đang dùng là client.trips.show
        return view('client.trips.show', [
            'trip'          => $trip,
            'bookedSeats'   => $bookedSeats,
            'seatsByFloor'  => $seatsByFloor,
            'pickupPoints'  => $pickupPoints,
            'dropoffPoints' => $dropoffPoints,
        ]);
    }
}
