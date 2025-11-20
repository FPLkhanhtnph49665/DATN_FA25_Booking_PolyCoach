<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bus;
use App\Models\Trip;
use App\Models\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DragonCode\Support\Facades\Helpers\Str;

class TripController extends Controller
{
    /**
     * Display a list of trips.
     */
    public function index(Request $request)
    {
        $query = Trip::with(['route', 'bus'])->orderByDesc('departure_date');

        // Filter by route
        if ($request->filled('route_id')) {
            $query->where('route_id', $request->route_id);
        }

        // Filter by bus
        if ($request->filled('bus_id')) {
            $query->where('bus_id', $request->bus_id);
        }

        // Filter by departure date
        if ($request->filled('departure_date')) {
            $query->whereDate('departure_date', $request->departure_date);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $trips  = $query->paginate(10);
        $routes = Route::orderBy('id')->get();
        $buses  = Bus::orderBy('plate_number')->get();

        return view('admin.trips.index', compact('trips', 'routes', 'buses'));
    }

    /**
     * Show form to create a new trip.
     */
    public function create()
    {
        $routes = Route::where('status', 1)
            ->with(['fromCity', 'toCity'])
            ->orderBy('id') // hoặc cột khác có thật trong routes
            ->get();

        $buses  = Bus::where('status', 1)
            ->orderBy('plate_number')
            ->get();

        return view('admin.trips.create', compact('routes', 'buses'));
    }



    /**
     * Store a newly created trip.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'route_id'       => 'required|exists:routes,id',
            'bus_id'         => 'required|exists:buses,id',
            'departure_date' => 'required|date',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time'   => 'required|date_format:H:i',
            'ticket_price'   => 'required|integer|min:0',
            'status'         => 'nullable|in:0,1',
        ]);

        // Nếu status không gửi, mặc định là 1
        $data['status'] = $data['status'] ?? 1;

        // Generate mã chuyến (ông thích format nào thì tự chỉnh)
        $data['trip_code'] = 'TRIP-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4));

        Trip::create($data);

        return redirect()
            ->route('admin.trips.index')
            ->with('success', 'Tạo chuyến thành công!');
    }

    /**
     * Show a specific trip.
     */
    public function show(Trip $trip)
    {
        $trip->load(['route', 'bus']);

        return view('admin.trips.show', compact('trip'));
    }

    /**
     * Show form to edit a trip.
     */
    public function edit(Trip $trip)
    {
        $routes = Route::where('status', 1)
            ->with(['fromCity', 'toCity'])
            ->orderBy('id')
            ->get();

        $buses = Bus::where('status', 1)
            ->orderBy('plate_number')
            ->get();

        return view('admin.trips.edit', compact('trip', 'routes', 'buses'));
    }


    /**
     * Update a trip.
     */
    public function update(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'route_id'       => 'required|exists:routes,id',
            'bus_id'         => 'required|exists:buses,id',
            'departure_date' => 'required|date',
            'departure_time' => 'required',
            'ticket_price'   => 'required|numeric|min:0',
            'status'         => 'required|in:0,1',
            'arrival_time'   => 'nullable',
        ]);

        $trip->update($validated);

        return redirect()->route('admin.trips.index')
            ->with('success', 'Chuyến đi đã được cập nhật thành công!');
    }

    /**
     * Delete a trip (soft delete).
     */
    public function destroy(Trip $trip)
    {
        // Prevent deletion if there are booked tickets
        if ($trip->tickets()->count() > 0) {
            return redirect()->route('admin.trips.index')
                ->withErrors('Không thể xóa chuyến đi này vì vé đã được đặt!');
        }

        $trip->delete();

        return redirect()->route('admin.trips.index')
            ->with('success', 'Chuyến đi đã được xóa thành công!');
    }

    /**
     * Search trips by trip code / departure / destination.
     */
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $trips = Trip::with('route', 'bus')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('trip_code', 'like', "%{$keyword}%")
                    ->orWhereHas('route', function ($q) use ($keyword) {
                        $q->where('departure_date', 'like', "%{$keyword}%")
                            ->orWhere('destination_point', 'like', "%{$keyword}%");
                    });
            })
            ->orderBy('departure_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        $routes = Route::orderBy('departure_date')->get();
        $buses  = Bus::orderBy('license_plate')->get();

        return view('admin.trips.index', compact('trips', 'routes', 'buses'));
    }

    /**
     * Display soft-deleted trips (trash).
     */
    public function trash()
    {
        $trips = Trip::onlyTrashed()->paginate(10);
        return view('admin.trips.trash', compact('trips'));
    }

    /**
     * Restore a soft-deleted trip.
     */
    public function restore($id)
    {
        $trip = Trip::onlyTrashed()->findOrFail($id);
        $trip->restore();

        return redirect()->route('admin.trips.index')
            ->with('success', 'Trip restored successfully!');
    }

    /**
     * Permanently delete a trip.
     */
    public function forceDelete($id)
    {
        $trip = Trip::onlyTrashed()->findOrFail($id);
        $trip->forceDelete();

        return redirect()->route('admin.trips.trash')
            ->with('success', 'Trip permanently deleted!');
    }
}
