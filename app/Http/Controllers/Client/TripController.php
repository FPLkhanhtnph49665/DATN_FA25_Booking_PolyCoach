<?php

namespace App\Http\Controllers\Client;

use App\Models\Trip;
use App\Models\Ticket;
use App\Models\PickupDropoffPoint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PickupPoint;
use App\Models\DropoffPoint;

class TripController extends Controller
{
    public function searchTrips(Request $request)
    {
        return $this->index($request);
    }

    public function index(Request $request)
    {
        $from = trim($request->input('from'));
        $to = trim($request->input('to'));
        $date = $request->input('date');
        $seats = (int) $request->input('seats', 1);
        $timeFilters = (array) $request->input('time', []);
        $busTypes = (array) $request->input('bus_type', []);
        $rows = (array) $request->input('row', []);
        $query = Trip::with([
            'route.pickupPoints',  // Load điểm đón
            'route.dropoffPoints', // Load điểm trả
            'bus',
            'tickets.passengers'
        ])
            // ->active()
            ->when($from, fn($q) => $q->whereHas(
                'route',
                fn($qr) =>
                $qr->where('diem_di', 'like', "%$from%")
            ))
            ->when($to, fn($q) => $q->whereHas(
                'route',
                fn($qr) =>
                $qr->where('diem_den', 'like', "%$to%")
            ))
            ->when(
                $date,
                fn($q) =>
                $q->whereDate('ngay_khoi_hanh', $date)
            )
            ->when($timeFilters, function ($q) use ($timeFilters) {
                $q->where(function ($query) use ($timeFilters) {
                    if (in_array('morning', $timeFilters)) {
                        $query->orWhereBetween('gio_khoi_hanh', ['00:00:00', '11:59:59']);
                    }
                    if (in_array('afternoon', $timeFilters)) {
                        $query->orWhereBetween('gio_khoi_hanh', ['12:00:00', '17:59:59']);
                    }
                    if (in_array('evening', $timeFilters)) {
                        $query->orWhereBetween('gio_khoi_hanh', ['18:00:00', '23:59:59']);
                    }
                });
            })
            ->when(
                $busTypes,
                fn($q) =>
                $q->whereHas(
                    'bus',
                    fn($qr) =>
                    $qr->whereIn('type', $busTypes)
                )
            );

        $trips = $query
            ->orderBy('ngay_khoi_hanh')
            ->orderBy('gio_khoi_hanh')
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
        // Lấy trip_id từ request
        $tripId = $request->input('trip_id');

        // Lấy trip theo ID, load quan hệ
        $trip = Trip::with([
            'route.pickupPoints',
            'route.dropoffPoints',
            'bus',
            'tickets.passengers'
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
        $trip = Trip::with(['route', 'tickets.passengers'])->findOrFail($tripId);
        $route = $trip->route;

        // Ghế đã bán
        $bookedSeats = $trip->getBookedSeats();

        // Điểm đón / trả
        $pickupPoints = PickupPoint::where('route_id', $route->id)
            ->orderBy('order')
            ->get();

        $dropoffPoints = DropoffPoint::where('route_id', $route->id)
            ->orderBy('order')
            ->get();

        return view('client.trips.show', [
            'trip' => $trip,
            'bookedSeats' => $bookedSeats,
            'pickupPoints' => $pickupPoints,
            'dropoffPoints' => $dropoffPoints,
        ]);
    }

}
