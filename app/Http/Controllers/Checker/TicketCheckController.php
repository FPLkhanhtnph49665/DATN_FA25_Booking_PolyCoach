<?php

namespace App\Http\Controllers\Checker;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketCheckController extends Controller
{
    public function index()
    {
        $tickets = Ticket::latest()->paginate(20);
        return view('checker.tickets', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('checker.tickets.show', compact('ticket'));
    }

    public function verify()
    {
        return view('checker.verify');
    }

    public function checkTicket(Request $request)
    {
        $ticket = Ticket::where('code', $request->code)->first();

        if (!$ticket) {
            return back()->with('error', 'Vé không tồn tại');
        }

        if ($ticket->status !== 'paid') {
            return back()->with('error', 'Vé chưa thanh toán hoặc không hợp lệ');
        }

        return back()->with('success', 'Vé hợp lệ!')->with('ticket', $ticket);
    }
}
