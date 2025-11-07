<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Passenger;
use App\Models\Ticket;
use Illuminate\Http\Request;

class PassengerController extends Controller
{
    /**
     * Hiển thị danh sách hành khách.
     */
    public function index(Request $request)
    {
        $query = Passenger::query()->with('ticket');

        // Tìm kiếm theo tên, email hoặc số điện thoại
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('ten_hanh_khach', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%")
                    ->orWhere('so_dien_thoai', 'like', "%$keyword%");
            });
        }

        $passengers = $query->latest()->paginate(25);
        return view('admin.passengers.index', compact('passengers'));
    }

    /**
     * Hiển thị form thêm hành khách mới.
     */
    public function create()
    {
        $tickets = Ticket::all();
        return view('admin.passengers.create', compact('tickets'));
    }

    /**
     * Lưu hành khách mới.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'ten_hanh_khach' => 'required|string|max:255',
            'email'          => 'nullable|email|max:255',
            'so_dien_thoai'  => 'required|string|max:15',
            'gioi_tinh'      => 'required|in:nam,nu,khac',
            'tuoi'           => 'nullable|integer|min:0|max:120',
            'ticket_id'      => 'required|exists:tickets,id',
        ]);

        Passenger::create($data);

        return redirect()->route('admin.passengers.index')
            ->with('success', 'Thêm hành khách thành công!');
    }

    /**
     * Hiển thị chi tiết hành khách.
     */
    public function show(Passenger $passenger)
    {
        $passenger->load('ticket');
        return view('admin.passengers.show', compact('passenger'));
    }

    /**
     * Hiển thị form chỉnh sửa hành khách.
     */
    public function edit(Passenger $passenger)
    {
        $tickets = Ticket::all();
        return view('admin.passengers.edit', compact('passenger', 'tickets'));
    }

    /**
     * Cập nhật thông tin hành khách.
     */
    public function update(Request $request, Passenger $passenger)
    {
        $data = $request->validate([
            'ten_hanh_khach' => 'required|string|max:255',
            'email'          => 'nullable|email|max:255',
            'so_dien_thoai'  => 'required|string|max:15',
            'gioi_tinh'      => 'required|in:nam,nu,khac',
            'tuoi'           => 'nullable|integer|min:0|max:120',
            'ticket_id'      => 'required|exists:tickets,id',
        ]);

        $passenger->update($data);

        return redirect()->route('admin.passengers.index')
            ->with('success', 'Cập nhật hành khách thành công!');
    }

    /**
     * Xóa mềm hành khách.
     */
    public function destroy(Passenger $passenger)
    {
    }
}
