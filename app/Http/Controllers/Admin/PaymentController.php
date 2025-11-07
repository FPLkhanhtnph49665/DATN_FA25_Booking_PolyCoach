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
     * Hiển thị danh sách thanh toán.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['user', 'ticket']);

        // Tìm kiếm theo mã thanh toán hoặc người dùng
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('ma_giao_dich', 'like', "%$keyword%")
                ->orWhereHas('user', function ($q) use ($keyword) {
                    $q->where('first_name', 'like', "%$keyword%")
                      ->orWhere('last_name', 'like', "%$keyword%");
                });
        }

        $payments = $query->latest()->paginate(25);
        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Hiển thị form tạo mới thanh toán.
     */
    public function create()
    {
        $users = User::all();
        $tickets = Ticket::all();
        return view('admin.payments.create', compact('users', 'tickets'));
    }

    /**
     * Lưu thanh toán mới.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'       => 'required|exists:users,id',
            'ticket_id'     => 'required|exists:tickets,id',
            'ma_giao_dich'  => 'required|string|max:50|unique:payments,ma_giao_dich',
            'so_tien'       => 'required|numeric|min:0',
            'phuong_thuc'   => 'required|in:cash,momo,bank',
            'trang_thai'    => 'required|in:0,1',
        ], [
            'ma_giao_dich.unique' => 'Mã giao dịch này đã tồn tại!',
        ]);

        Payment::create($data);

        return redirect()->route('admin.payments.index')->with('success', 'Thêm thanh toán thành công!');
    }

    /**
     * Hiển thị chi tiết thanh toán.
     */
    public function show(Payment $payment)
    {
        $payment->load(['user', 'ticket']);
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Hiển thị form chỉnh sửa thanh toán.
     */
    public function edit(Payment $payment)
    {
        $users = User::all();
        $tickets = Ticket::all();
        return view('admin.payments.edit', compact('payment', 'users', 'tickets'));
    }

    /**
     * Cập nhật thông tin thanh toán.
     */
    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'user_id'       => 'required|exists:users,id',
            'ticket_id'     => 'required|exists:tickets,id',
            'ma_giao_dich'  => 'required|string|max:50|unique:payments,ma_giao_dich,' . $payment->id,
            'so_tien'       => 'required|numeric|min:0',
            'phuong_thuc'   => 'required|in:cash,momo,bank',
            'trang_thai'    => 'required|in:0,1',
        ]);

        $payment->update($data);

        return redirect()->route('admin.payments.index')->with('success', 'Cập nhật thanh toán thành công!');
    }

    /**
     * Xóa mềm thanh toán.
     */
    public function destroy(Payment $payment)
    {
    }
}
