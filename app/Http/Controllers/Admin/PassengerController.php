<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Passenger;
use App\Models\Ticket;
use Illuminate\Http\Request;

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
        $tickets = Ticket::all();
        return view('admin.passengers.create', compact('tickets'));
    }

    /**
     * Store a newly created passenger.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'nullable|email|max:255',
            'phone'     => 'required|string|max:15',
            'gender'    => 'required|in:male,female,other',
            'age'       => 'nullable|integer|min:0|max:120',
            'ticket_id' => 'required|exists:tickets,id',
        ]);

        Passenger::create($data);

        return redirect()->route('admin.passengers.index')
                         ->with('success', 'Passenger created successfully!');
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
            'age'       => 'nullable|integer|min:0|max:120',
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
