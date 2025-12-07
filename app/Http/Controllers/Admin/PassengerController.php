<?php

namespace App\Http\Controllers\Admin;

use App\Models\Trip;
use App\Models\Ticket;
use App\Models\Passenger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PassengerController extends Controller
{
    /**
     * Display a list of passengers.
     */
    public function index(Request $request)
    {
        $query = Passenger::with('ticket');

        // Search by name, email, or phone
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                  ->orWhere('email', 'like', "%$keyword%")
                  ->orWhere('phone', 'like', "%$keyword%");
            });
        }

        $passengers = $query->latest()->paginate(25);
        return view('admin.passengers.index', compact('passengers'));
    }

    /**
     * Show form to create a new passenger.
     */
    public function create()
{
    $tickets = Ticket::with('user')->get();
    $trips = Trip::with('route.fromCity', 'route.toCity')->get();

    return view('admin.passengers.create', compact('tickets', 'trips'));
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
        'seat_number' => 'required|string|max:10',
        'ticket_id' => 'nullable|exists:tickets,id',
        'trip_id' => 'required|exists:trips,id',
    ], [
        'name.required' => 'Vui lòng nhập tên hành khách.',
        'seat_number.required' => 'Vui lòng nhập số ghế.',
        'trip_id.required' => 'Vui lòng chọn chuyến.',
    ]);

    Passenger::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'seat_number' => $request->seat_number,
        'ticket_id' => $request->ticket_id,
        'trip_id' => $request->trip_id,
    ]);

    return redirect()
        ->route('admin.passengers.index')
        ->with('success_toast', 'Thêm hành khách thành công!');
}


    /**
     * Display the specified passenger.
     */
    public function show(Passenger $passenger)
    {
        $passenger->load('ticket');
        return view('admin.passengers.show', compact('passenger'));
    }

    /**
     * Show form to edit a passenger.
     */
    public function edit(Passenger $passenger)
    {
        $tickets = Ticket::all();
        return view('admin.passengers.edit', compact('passenger', 'tickets'));
    }

    /**
     * Update the specified passenger.
     */
    public function update(Request $request, Passenger $passenger)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'nullable|email|max:255',
            'phone'     => 'required|string|max:15',
            'gender'    => 'required|in:male,female,other',
            'ticket_id' => 'required|exists:tickets,id',
        ]);

        $passenger->update($data);

        return redirect()->route('admin.passengers.index')
                         ->with('success', 'Passenger updated successfully!');
    }

    /**
     * Soft delete the specified passenger.
     */
    public function destroy(Passenger $passenger)
    {
        $passenger->delete();

        return redirect()->route('admin.passengers.index')
                         ->with('success', 'Passenger deleted successfully!');
    }

    /**
     * Display soft-deleted passengers.
     */
    public function trash()
    {
        $passengers = Passenger::onlyTrashed()->paginate(25);
        return view('admin.passengers.trash', compact('passengers'));
    }

    /**
     * Restore a soft-deleted passenger.
     */
    public function restore($id)
    {
        $passenger = Passenger::onlyTrashed()->findOrFail($id);
        $passenger->restore();

        return redirect()->route('admin.passengers.index')
                         ->with('success', 'Passenger restored successfully!');
    }

    /**
     * Permanently delete a passenger.
     */
    public function forceDelete($id)
    {
        $passenger = Passenger::onlyTrashed()->findOrFail($id);
        $passenger->forceDelete();

        return redirect()->route('admin.passengers.trash')
                         ->with('success', 'Passenger permanently deleted!');
    }
}
