<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy danh sách vé cùng thông tin chuyến và người dùng
        $tickets = Ticket::with(['trip', 'user'])->paginate(25);
        return view('admin.tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Form tạo vé mới (nếu cần)
        return view('admin.tickets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'user_id' => 'required|exists:users,id',
            'seat_number' => 'required|string|max:10',
            'price' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:50',
        ]);

        $ticket = Ticket::create($validated);

        // Nếu có file hóa đơn (PDF hoặc ảnh vé)
        if ($request->hasFile('invoice')) {
            $path = $request->file('invoice')->store('invoices', 'public');
            $ticket->invoice_path = $path;
            $ticket->save();
        }

        return redirect()->route('admin.tickets.index')->with('success', 'Thêm vé thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        // Nếu có file hóa đơn -> lấy đường dẫn
        $invoiceUrl = $ticket->invoice_path ? Storage::url($ticket->invoice_path) : null;
        return view('admin.tickets.show', compact('ticket', 'invoiceUrl'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        return view('admin.tickets.edit', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'seat_number' => 'required|string|max:10',
            'price' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:50',
        ]);

        $ticket->update($validated);

        if ($request->hasFile('invoice')) {
            // Xóa file cũ nếu có
            if ($ticket->invoice_path && Storage::disk('public')->exists($ticket->invoice_path)) {
                Storage::disk('public')->delete($ticket->invoice_path);
            }

            // Lưu file mới
            $path = $request->file('invoice')->store('invoices', 'public');
            $ticket->invoice_path = $path;
            $ticket->save();
        }

        return redirect()->route('admin.tickets.index')->with('success', 'Cập nhật vé thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        
    }
}
