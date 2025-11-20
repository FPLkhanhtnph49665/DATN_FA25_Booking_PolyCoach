<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a list of payments.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['user', 'ticket']);

        // Search by transaction code or user
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('transaction_code', 'like', "%$keyword%")
                ->orWhereHas('user', function ($q) use ($keyword) {
                    $q->where('first_name', 'like', "%$keyword%")
                      ->orWhere('last_name', 'like', "%$keyword%");
                });
        }

        $payments = $query->latest()->paginate(25);
        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show form to create a new payment.
     */
    public function create()
    {
        $users = User::all();
        $tickets = Ticket::all();
        return view('admin.payments.create', compact('users', 'tickets'));
    }

    /**
     * Store a newly created payment.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'          => 'required|exists:users,id',
            'ticket_id'        => 'required|exists:tickets,id',
            'transaction_code' => 'required|string|max:50|unique:payments,transaction_code',
            'amount'           => 'required|numeric|min:0',
            'method'           => 'required|in:cash,momo,bank',
            'status'           => 'required|in:0,1',
        ], [
            'transaction_code.unique' => 'This transaction code already exists!',
        ]);

        Payment::create($data);

        return redirect()->route('admin.payments.index')
                         ->with('success', 'Payment created successfully!');
    }

    /**
     * Display a specific payment.
     */
    public function show(Payment $payment)
    {
        $payment->load(['user', 'ticket']);
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show form to edit a payment.
     */
    public function edit(Payment $payment)
    {
        $users = User::all();
        $tickets = Ticket::all();
        return view('admin.payments.edit', compact('payment', 'users', 'tickets'));
    }

    /**
     * Update a payment.
     */
    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'user_id'          => 'required|exists:users,id',
            'ticket_id'        => 'required|exists:tickets,id',
            'transaction_code' => 'required|string|max:50|unique:payments,transaction_code,' . $payment->id,
            'amount'           => 'required|numeric|min:0',
            'method'           => 'required|in:cash,momo,bank',
            'status'           => 'required|in:0,1',
        ]);

        $payment->update($data);

        return redirect()->route('admin.payments.index')
                         ->with('success', 'Payment updated successfully!');
    }

    /**
     * Soft delete a payment.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payments.index')
                         ->with('success', 'Payment deleted successfully!');
    }

    /**
     * Display soft-deleted payments (trash).
     */
    public function trash()
    {
        $payments = Payment::onlyTrashed()->paginate(25);
        return view('admin.payments.trash', compact('payments'));
    }

    /**
     * Restore a soft-deleted payment.
     */
    public function restore($id)
    {
        $payment = Payment::onlyTrashed()->findOrFail($id);
        $payment->restore();

        return redirect()->route('admin.payments.index')
                         ->with('success', 'Payment restored successfully!');
    }

    /**
     * Permanently delete a payment.
     */
    public function forceDelete($id)
    {
        $payment = Payment::onlyTrashed()->findOrFail($id);
        $payment->forceDelete();

        return redirect()->route('admin.payments.trash')
                         ->with('success', 'Payment permanently deleted!');
    }
}
