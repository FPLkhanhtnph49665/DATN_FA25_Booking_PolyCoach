<?php

namespace App\Http\Controllers\Client;

use App\Models\Trip;
use App\Models\City;
use App\Models\PickupDropoffPoint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TripController extends Controller
{
    /**
     * Tìm kiếm và hiển thị danh sách chuyến đi
     */
    public function index(Request $request)
    {
        // 1. Chuẩn hóa dữ liệu đầu vào
        $from = $request->integer('from_city_id');
        $to = $request->integer('to_city_id');
        $date = $request->input('departure_date');
        $seatsRequired = $request->integer('seats', 1);
        $timeFilters = (array) $request->input('time', []);
        $busTypes = (array) $request->input('bus_type', []);
        $rows = (array) $request->input('row', []);

        // 2. Xây dựng Query với Eager Loading toàn diện (Nested Relations)
        $query = Trip::with([
            'bus',
            'tickets',
            'route.fromCity',
            'route.toCity',
            // Nạp đồng thời cả 2 quan hệ riêng biệt
            'route.pickupPoints' => fn($q) => $q->where('active', 1)->orderBy('time'),
            'route.dropoffPoints' => fn($q) => $q->where('active', 1)->orderBy('time'),
        ])
        ->withCount('tickets')
        ->where('status', 1);

        // 3. Áp dụng các bộ lọc tại tầng Database
        $query->when($from, fn($q) => $q->whereHas('route', fn($qr) => $qr->where('from_city_id', $from)))
            ->when($to, fn($q) => $q->whereHas('route', fn($qr) => $qr->where('to_city_id', $to)))
            ->when($date, fn($q) => $q->whereDate('departure_date', $date))
            ->when($busTypes, fn($q) => $q->whereHas('bus', fn($qr) => $qr->whereIn('type', $busTypes)));

        // Bộ lọc khung giờ
        if (!empty($timeFilters)) {
            $query->where(function ($q) use ($timeFilters) {
                if (in_array('sang', $timeFilters))
                    $q->orWhereBetween('departure_time', ['00:00:00', '06:00:00']);
                if (in_array('sang2', $timeFilters))
                    $q->orWhereBetween('departure_time', ['06:00:01', '12:00:00']);
                if (in_array('chieu', $timeFilters))
                    $q->orWhereBetween('departure_time', ['12:00:01', '18:00:00']);
                if (in_array('toi', $timeFilters))
                    $q->orWhereBetween('departure_time', ['18:00:01', '23:59:59']);
            });
        }

        $trips = $query->orderBy('departure_date')->orderBy('departure_time')->get();

        // 4. Lọc trên Collection (Bộ nhớ PHP) để tối ưu các logic phức tạp
        if ($seatsRequired > 0 || !empty($rows)) {
            $trips = $trips->filter(function ($trip) use ($seatsRequired, $rows) {
                $hasSeats = $trip->availableSeats() >= $seatsRequired;
                $hasRows = empty($rows) || $trip->availableSeatsInRows($rows) >= $seatsRequired;
                return $hasSeats && $hasRows;
            })->values();
        }

        // 5. Lấy danh sách tỉnh thành cho Form (Cache hoặc Eager Load nếu cần)
        $cities = City::where('status', 1)->orderBy('name')->get();

        return view('client.trips.index', [
            'trips' => $trips,
            'allFrom' => $cities,
            'allTo' => $cities,
        ]);
    }

    public function searchTrips(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Hiển thị chi tiết chuyến xe và chọn ghế
     */
    public function show(Request $request)
    {
        $tripId = $request->input('trip_id');

        // Eager load mọi thứ cần thiết cho trang chi tiết trong 1 lần fetch
        $trip = Trip::with([
            'bus',
            'route.fromCity',
            'route.toCity',
            'route.pickupPoints', // Nạp điểm đón
            'route.dropoffPoints', // Nạp điểm trả
            'tickets'
        ])->findOrFail($tripId);

        return view('client.trips.show', [
            'trip' => $trip,
            'bookedSeats' => $trip->getBookedSeats(),
            'pickupPoints' => $trip->route->pickupPoints,
            'dropoffPoints' => $trip->route->dropoffPoints,
        ]);
    }
}