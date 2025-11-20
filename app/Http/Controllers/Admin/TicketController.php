<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets.
     */
    public function index()
    {
        $tickets = Ticket::with(['trip', 'user'])->paginate(25);
        return view('admin.tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        return view('admin.tickets.create');
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'trip_id'        => 'required|exists:trips,id',
            'user_id'        => 'required|exists:users,id',
            'seat_number'    => 'required|string|max:10',
            'price'          => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:50',
        ]);

        $ticket = Ticket::create($validated);

        if ($request->hasFile('invoice')) {
            $path = $request->file('invoice')->store('invoices', 'public');
            $ticket->invoice_path = $path;
            $ticket->save();
        }

        return redirect()->route('admin.tickets.index')->with('success', 'Ticket created successfully!');
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket)
    {
        $invoiceUrl = $ticket->invoice_path ? Storage::url($ticket->invoice_path) : null;
        return view('admin.tickets.show', compact('ticket', 'invoiceUrl'));
    }

    /**
     * Show the form for editing the specified ticket.
     */
    public function edit(Ticket $ticket)
    {
        return view('admin.tickets.edit', compact('ticket'));
    }

    /**
     * Update the specified ticket in storage.
     */
    public function update(Request $request, Ticket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'seat_number'    => 'required|string|max:10',
            'price'          => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:50',
        ]);

        $ticket->update($validated);

        if ($request->hasFile('invoice')) {
            if ($ticket->invoice_path && Storage::disk('public')->exists($ticket->invoice_path)) {
                Storage::disk('public')->delete($ticket->invoice_path);
            }
            $path = $request->file('invoice')->store('invoices', 'public');
            $ticket->invoice_path = $path;
            $ticket->save();
        }

        return redirect()->route('admin.tickets.index')->with('success', 'Ticket updated successfully!');
    }

    /**
     * Soft delete the specified ticket.
     */
    public function destroy(Ticket $ticket): RedirectResponse
    {
        $ticket->delete();
        return redirect()->route('admin.tickets.index')->with('success', 'Ticket deleted successfully!');
    }

    /**
     * Display soft-deleted tickets (trash).
     */
    public function trash()
    {
        $tickets = Ticket::onlyTrashed()->paginate(25);
        return view('admin.tickets.trash', compact('tickets'));
    }

    /**
     * Restore a soft-deleted ticket.
     */
    public function restore($id): RedirectResponse
    {
        $ticket = Ticket::onlyTrashed()->findOrFail($id);
        $ticket->restore();
        return redirect()->route('admin.tickets.index')->with('success', 'Ticket restored successfully!');
    }

    /**
     * Permanently delete a ticket.
     */
    public function forceDelete($id): RedirectResponse
    {
        $ticket = Ticket::onlyTrashed()->findOrFail($id);
        $ticket->forceDelete();
        return redirect()->route('admin.tickets.trash')->with('success', 'Ticket permanently deleted!');
    }
}
