<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    /**
     * ------------------------------------------------------------------
     * LIST BOOKING + SEARCH
     * ------------------------------------------------------------------
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'trip']);

        // SEARCH theo tên khách hoặc mã chuyến
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQ) use ($search) {
                    $userQ->where('full_name', 'like', "%{$search}%");
                })
                ->orWhereHas('trip', function ($tripQ) use ($search) {
                    $tripQ->where('id', $search);
                });
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * ------------------------------------------------------------------
     * SHOW BOOKING DETAIL
     * ------------------------------------------------------------------
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'trip', 'tickets']);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * ------------------------------------------------------------------
     * EDIT BOOKING
     * ------------------------------------------------------------------
     */
    public function edit(Booking $booking)
    {
        $booking->load(['user', 'trip']);
        return view('admin.bookings.edit', compact('booking'));
    }

    /**
     * ------------------------------------------------------------------
     * UPDATE BOOKING
     * ------------------------------------------------------------------
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status'          => 'required|in:pending,confirmed,paid,cancelled',
            'payment_method'  => 'nullable|string|max:50',
            'total_amount'    => 'required|numeric|min:0',
        ], [
            'status.required' => 'Trạng thái không được để trống',
            'total_amount.numeric' => 'Tổng tiền phải là số',
        ]);

        $booking->update([
            'status'         => $request->status,
            'payment_method' => $request->payment_method,
            'total_amount'   => $request->total_amount,
        ]);

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'Cập nhật booking thành công!');
    }

    /**
     * ------------------------------------------------------------------
     * DELETE (SOFT DELETE)
     * ------------------------------------------------------------------
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'Đã xóa booking thành công!');
    }
}
