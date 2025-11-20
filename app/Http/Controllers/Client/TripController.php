<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trip;

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

        $query = Trip::with(['route.fromCity', 'route.toCity', 'bus', 'tickets.passengers'])
            ->active()
            ->when($from, fn($q) => $q->whereHas('route.fromCity', fn($qr) => $qr->where('name','like',"%$from%")))
            ->when($to, fn($q) => $q->whereHas('route.toCity', fn($qr) => $qr->where('name','like',"%$to%")))
            ->when($date, fn($q) => $q->whereDate('departure_date', $date))
            ->when($timeFilters, function($q) use($timeFilters){
                $q->where(function($query) use($timeFilters){
                    if(in_array('morning',$timeFilters)) $query->orWhereBetween('departure_time',['00:00:00','11:59:59']);
                    if(in_array('afternoon',$timeFilters)) $query->orWhereBetween('departure_time',['12:00:00','17:59:59']);
                    if(in_array('evening',$timeFilters)) $query->orWhereBetween('departure_time',['18:00:00','23:59:59']);
                });
            })
            ->when($busTypes, fn($q) => $q->whereHas('bus', fn($qr) => $qr->whereIn('type', $busTypes)));

        $trips = $query->orderBy('departure_date')->orderBy('departure_time')->get();

        // Filter available seats
        if($seats > 0) {
            $trips = $trips->filter(fn($trip) => $trip->availableSeats() >= $seats)->values();
        }

        // Filter by rows
        if(!empty($rows)){
            $trips = $trips->filter(fn($trip) => $trip->availableSeatsInRows($rows) >= $seats)->values();
        }

        return view('client.trips.index', compact('trips'));
    }

    public function show(Request $request)
    {
        $tripId = $request->query('trip_id');
        if(!$tripId) abort(404,'Missing trip_id');

        $trip = Trip::with(['route.fromCity','route.toCity','bus','tickets.passengers'])->findOrFail($tripId);
        $bookedSeats = $trip->getBookedSeats();

        return view('client.trips.show', compact('trip','bookedSeats'));
    }
}
