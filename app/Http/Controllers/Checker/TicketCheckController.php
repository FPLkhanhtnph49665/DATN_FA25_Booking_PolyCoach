<?php

namespace App\Http\Controllers\Checker;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketCheckController extends Controller
{
    /**
     * Danh sách vé
     */
    public function index(Request $request)
    {
        $query = Ticket::query()->with(['trip.route.fromCity', 'trip.route.toCity', 'passengers']);

        // Lọc theo mã vé
        if ($request->code) {
            $query->where('code', 'LIKE', '%' . $request->code . '%');
        }

        // Lọc trạng thái
        if ($request->status === 'checked') {
            $query->whereNotNull('checked_at');
        } elseif ($request->status === 'unchecked') {
            $query->whereNull('checked_at');
        }

        // Lọc theo chuyến
        if ($request->route_id) {
            $query->whereHas('trip.route', function ($q) use ($request) {
                $q->where('id', $request->route_id);
            });
        }

        $tickets = $query->latest()->paginate(20);

        // Danh sách tuyến để đổ vào filter
        $routes = \App\Models\Route::with(['fromCity', 'toCity'])->get();

        return view('checker.tickets', compact('tickets', 'routes'));
    }
    // trạng thái vé
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->status = $request->status;

        // Nếu chuyển sang 'paid' HOẶC 'cancelled', và trước đó chưa có thời gian kiểm
        if (in_array($request->status, ['paid', 'cancelled']) && !$ticket->checked_at) {
            $ticket->checked_at = now();
            $ticket->checked_by = auth()->id();
        }

        // Tùy chọn: Nếu chuyển ngược về 'pending', bạn có thể muốn xóa luôn checked_at
        if ($request->status == 'pending') {
            $ticket->checked_at = null;
        }

        $ticket->save();

        return back()->with('success', 'Cập nhật trạng thái vé thành công!');
    }

    /**
     * Chi tiết vé
     */
    public function show($id)
    {
        $ticket = Ticket::with(['trip', 'user'])
            ->findOrFail($id);

        return view('checker.tickets.show', compact('ticket'));
    }

    /**
     * Form kiểm tra vé
     */
    public function verify()
    {
        return view('checker.verify');
    }

    /**
     * Xử lý kiểm tra vé (scan hoặc nhập tay)
     */
    public function checkTicket(Request $request)
    {
        // Validate đầu vào
        $request->validate([
            'code' => 'required|string|max:50'
        ], [
            'code.required' => 'Vui lòng nhập mã vé',
        ]);

        // Tìm vé
        $ticket = Ticket::where('code', $request->code)->first();

        if (!$ticket) {
            return back()->with('error', '❌ Vé không tồn tại');
        }

        // Vé phải được thanh toán mới hợp lệ
        if ($ticket->status !== 'paid') {
            return back()->with('error', '⚠ Vé chưa thanh toán hoặc không hợp lệ');
        }

        // Nếu muốn tránh quét trùng → đánh dấu đã check
        if ($ticket->is_checked ?? false) {
            return back()->with('error', '⚠ Vé này đã được quét trước đó!');
        }

        // Nếu muốn tự động đánh dấu check khi quét
        $ticket->update(['is_checked' => true]);

        return back()
            ->with('success', '✅ Vé hợp lệ!')
            ->with('ticket', $ticket);
    }
}
