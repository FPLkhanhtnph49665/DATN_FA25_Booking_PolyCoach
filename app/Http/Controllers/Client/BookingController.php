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

        // Validate input
        $request->validate([
            'trip_id'        => 'required|exists:trips,id',
            'seat_codes'     => 'required|string',   // "A01,A02"
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email',
            'pickup_point_id' => 'nullable|integer',  // nếu bạn dùng id
            'drop_point_id'  => 'nullable|integer',
        ]);

        $trip = Trip::with(['bus', 'passengers'])->findOrFail($request->trip_id);

        // Chuẩn hoá danh sách ghế
        $seatCodes = collect(explode(',', $request->seat_codes))
            ->map(fn($v) => trim($v))
            ->filter()
            ->unique()
            ->values();

        if ($seatCodes->isEmpty()) {
            return back()
                ->withErrors(['seat_codes' => 'Bạn chưa chọn ghế nào.'])
                ->withInput();
        }

        // Lấy danh sách ghế đã đặt (tuỳ bạn implement trong Trip)
        $booked = $trip->booked_seats ?? [];

        // Kiểm tra trùng ghế
        $conflict = $seatCodes->intersect($booked);
        if ($conflict->isNotEmpty()) {
            return back()
                ->withErrors(['seat_codes' => 'Ghế ' . $conflict->implode(', ') . ' đã có người đặt.'])
                ->withInput();
        }

        // Tính giá theo ticket_price (đúng với migration trips mới)
        $seatCount  = $seatCodes->count();
        $unitPrice  = (int) ($trip->ticket_price ?? 0);
        $totalPrice = $seatCount * $unitPrice;

        DB::beginTransaction();
        try {
            // 1) Tạo Booking
            $booking = Booking::create([
                'user_id'       => Auth::id(),
                'trip_id'       => $trip->id,
                'total_amount'  => $totalPrice,
                'status'        => 'Đang chờ', // hoặc 'confirmed', tuỳ bạn
                // nếu Booking có thêm cột: customer_name, phone, pickup_point_id, drop_point_id...
                // thì set thêm ở đây
            ]);

            // 2) Tạo Ticket (1 vé cho nhiều ghế)
            $ticket = Ticket::create([
                'trip_id'        => $trip->id,
                'user_id'        => Auth::id(),
                'seat_number'    => $seatCount,                         // số lượng ghế
                'seat_code'      => $seatCodes->implode(','),           // vd: "A01,A02"
                'status'         => 'paid',                             // hoặc 'pending'
                'payment_method' => 'cash',                             // tạm fix, sau này đổi thành online
            ]);

            // 3) Tạo Passenger cho từng ghế
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
            // debug nhanh: dd($e->getMessage());
            return back()
                ->withErrors(['general' => 'Lỗi hệ thống, vui lòng thử lại sau.'])
                ->withInput();
        }

        return redirect()
            ->route('client.bookings.show', $booking->id)
            ->with('success', 'Đặt vé thành công!');
    }
}
