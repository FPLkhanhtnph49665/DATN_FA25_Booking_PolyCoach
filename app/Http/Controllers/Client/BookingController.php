<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Trip;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Passenger;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // Bắt buộc đăng nhập
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Bạn cần đăng nhập để đặt vé.');
        }

        // Validate
        $request->validate([
            'trip_id'        => 'required|exists:trips,id',
            'seat_codes'     => 'required|string',          // <— KHỚP input
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email',
            'pickup_point'   => 'nullable|string|max:255',
            'drop_point'     => 'nullable|string|max:255',
        ]);

        $trip = Trip::with(['bus', 'passengers'])->findOrFail($request->trip_id);

        // Chuẩn hoá ghế
        $seatCodes = collect(explode(',', $request->seat_codes))
            ->map(fn($v) => trim($v))
            ->filter()
            ->unique()
            ->values();

        if ($seatCodes->isEmpty()) {
            return back()->withErrors(['seat_codes' => 'Bạn chưa chọn ghế nào.'])->withInput();
        }

        // Ghế đã đặt
        $booked = $trip->booked_seats;

        // Kiểm tra trùng
        $conflict = $seatCodes->intersect($booked);
        if ($conflict->isNotEmpty()) {
            return back()
                ->withErrors(['seat_codes' => 'Ghế ' . $conflict->implode(', ') . ' đã có người đặt.'])
                ->withInput();
        }

        // Tính giá
        $seatCount  = $seatCodes->count();
        $unitPrice  = (int)$trip->gia_ve;
        $totalPrice = $seatCount * $unitPrice;

        DB::beginTransaction();
        try {
            // 1) Tạo booking
            $booking = Booking::create([
                'user_id'   => Auth::id(),
                'trip_id'   => $trip->id,
                'tong_tien' => $totalPrice,
                'trang_thai'=> 'Đang chờ',
            ]);

            // 2) Ticket
            $ticket = Ticket::create([
                'trip_id'    => $trip->id,
                'user_id'    => Auth::id(),
                'so_ghe'     => $seatCount,
                'trang_thai' => 'pending',
            ]);

            // 3) Passenger mỗi ghế
            foreach ($seatCodes as $code) {
                Passenger::create([
                    'ticket_id'   => $ticket->id,
                    'name'        => $request->customer_name,
                    'phone'       => $request->customer_phone,
                    'seat_number' => $code,
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['general' => 'Lỗi hệ thống, vui lòng thử lại.']);
        }

        return redirect()
            ->route('client.bookings.show', $booking->id)   // <— SỬA ĐÚNG
            ->with('success', 'Đặt vé thành công!');
    }
}
