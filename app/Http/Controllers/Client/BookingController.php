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
        // 🔒 Bắt buộc đăng nhập
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Bạn cần đăng nhập để đặt vé.');
        }

        // ✅ Validate dữ liệu từ form
        $request->validate([
            'trip_id'        => 'required|exists:trips,id',
            'seat_codes'     => 'required|string',           // A01,A02,...
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email',
            'pickup_point'   => 'nullable|string|max:255',
            'drop_point'     => 'nullable|string|max:255',
        ]);

        // Lấy chuyến + xe + hành khách hiện có
        $trip = Trip::with(['bus', 'passengers'])->findOrFail($request->trip_id);

        // Tách danh sách ghế từ input
        $seatCodes = array_filter(array_map('trim', explode(',', $request->seat_codes)));
        $seatCodes = array_unique($seatCodes);
        $seatCount = count($seatCodes);

        if ($seatCount === 0) {
            return back()
                ->withErrors(['seat_codes' => 'Bạn chưa chọn ghế nào.'])
                ->withInput();
        }

        // Tổng số ghế của xe
        $totalSeats = (int) ($trip->bus->so_ghe ?? 0);
        if ($totalSeats <= 0) {
            return back()
                ->withErrors(['trip_id' => 'Xe của chuyến này chưa được cấu hình số ghế.'])
                ->withInput();
        }

        // Ghế đã được đặt trước đó (từ passengers)
        $booked = $trip->booked_seats;    // accessor trong Trip model → array seat_number

        // Kiểm tra ghế trùng
        $conflict = array_intersect($seatCodes, $booked);
        if (!empty($conflict)) {
            $conflictStr = implode(', ', $conflict);
            return back()
                ->withErrors(['seat_codes' => "Các ghế $conflictStr đã có người đặt, vui lòng chọn ghế khác."])
                ->withInput();
        }

        // Kiểm tra không vượt quá số ghế trống
        $available = $trip->so_ghe_trong; // accessor trong Trip model
        if ($seatCount > $available) {
            return back()
                ->withErrors(['seat_codes' => "Chỉ còn $available ghế trống trên chuyến này."])
                ->withInput();
        }

        // Tính tổng tiền
        $unitPrice  = (int) $trip->gia_ve;
        $totalPrice = $seatCount * $unitPrice;

        DB::beginTransaction();
        try {
            // 1️⃣ Tạo booking (đơn đặt vé tổng)
            $booking = Booking::create([
                'user_id'                => Auth::id(),
                'trip_id'                => $trip->id,
                'ngay_dat'               => now(),
                'tong_tien'              => $totalPrice,
                'trang_thai'             => 'Đang chờ',   // theo enum trong migration
                'phuong_thuc_thanh_toan' => 'cash',      // tạm để cash, sau này thêm Momo/ VNPay
            ]);

            // 2️⃣ Tạo ticket chính cho user này
            $ticket = Ticket::create([
                'trip_id'               => $trip->id,
                'user_id'               => Auth::id(),
                'so_ghe'                => $seatCount,
                'trang_thai'            => 'pending',    // khớp Ticket::getTrangThaiLabelAttribute
                'phuong_thuc_thanh_toan'=> 'cash',
            ]);

            // 3️⃣ Tạo passengers theo từng ghế
            foreach ($seatCodes as $code) {
                Passenger::create([
                    'ticket_id'   => $ticket->id,
                    'name'        => $request->customer_name,
                    'phone'       => $request->customer_phone,
                    'age'         => null,
                    'seat_number' => $code,
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withErrors(['general' => 'Có lỗi khi tạo đơn đặt vé, vui lòng thử lại.'])
                ->withInput();
        }

        return redirect()
            ->route('client.trips.show', $trip->id)
            ->with('success', 'Đặt vé thành công!');
    }
}
