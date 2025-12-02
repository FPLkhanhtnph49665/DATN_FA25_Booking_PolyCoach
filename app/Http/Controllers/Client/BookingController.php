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
use App\Models\PointFare;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // Bắt buộc đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để đặt vé.');
        }
        // ✅ FIX 1: Validate đúng tên trường gửi từ Form (UI)
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'seat_codes' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email',
            'pickup_point_id' => 'required|exists:pickup_points,id', // Sửa tên và thêm exists
            'dropoff_point_id' => 'required|exists:dropoff_points,id', // Sửa tên và thêm exists
        ]);

        $trip = Trip::with(['bus', 'passengers'])->findOrFail($request->trip_id);
        // Xử lý danh sách ghế
        $seatCodes = array_filter(array_map('trim', explode(',', $request->seat_codes)));
        $seatCodes = array_unique($seatCodes);
        $seatCount = count($seatCodes);

        if ($seatCount === 0) {
            return back()->withErrors(['seat_codes' => 'Bạn chưa chọn ghế nào.'])->withInput();
        }

        // Kiểm tra logic ghế (Đã có - Giữ nguyên)
        $totalSeats = (int) ($trip->bus->so_ghe ?? 0);
        if ($totalSeats <= 0) {
            return back()->withErrors(['trip_id' => 'Cấu hình xe lỗi.'])->withInput();
        }

        $booked = $trip->booked_seats ?? []; // Sử dụng null coalescing cho an toàn
        $conflict = array_intersect($seatCodes, $booked);
        if (!empty($conflict)) {
            return back()->withErrors(['seat_codes' => "Ghế " . implode(', ', $conflict) . " đã có người đặt."])->withInput();
        }

        $available = $trip->so_ghe_trong;
        if ($seatCount > $available) {
            return back()->withErrors(['seat_codes' => "Chỉ còn $available ghế trống."])->withInput();
        }

        // ✅ FIX 2: Logic tính giá (QUAN TRỌNG)
        // Gọi hàm private để tính đơn giá chính xác theo điểm đón trả
        $unitPrice = $this->calculateUnitPrice($trip, $request->pickup_point_id, $request->dropoff_point_id);
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
        $seatCount = $seatCodes->count();
        $unitPrice = (int) ($trip->ticket_price ?? 0);
        $totalPrice = $seatCount * $unitPrice;

        DB::beginTransaction();
        try {
            // 1️⃣ Tạo booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'trip_id' => $trip->id,
                'ngay_dat' => now(),
                'tong_tien' => $totalPrice,
                'trang_thai' => 'Đang chờ',
                'phuong_thuc_thanh_toan' => 'cash',
                // ✅ FIX 4: Lưu thông tin điểm đón trả (Nếu bảng bookings có cột này)
                // Nếu bạn thiết kế lưu ở Ticket thì sửa ở dưới
            ]);

            // 2️⃣ Tạo ticket
            $ticket = Ticket::create([
                'booking_id' => $booking->id, // Thường Ticket thuộc về Booking
                'trip_id' => $trip->id,
                'user_id' => Auth::id(),
                'so_ghe' => $seatCount,
                'trang_thai' => 'pending',
                'phuong_thuc_thanh_toan' => 'cash',
                // ✅ FIX 4: Lưu điểm đón trả vào Ticket (hoặc Booking tùy DB)
                'pickup_point_id' => $request->pickup_point_id,
                'dropoff_point_id' => $request->dropoff_point_id,
                'gia_ve_thuc_te' => $unitPrice // Nên lưu lại giá tại thời điểm đặt
            ]);

            // 3️⃣ Tạo passengers
            foreach ($seatCodes as $code) {
                Passenger::create([
                    'ticket_id' => $ticket->id,
                    'name' => $request->customer_name,
                    'phone' => $request->customer_phone,
                    'seat_number' => $code,
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            // Log lỗi để debug: \Log::error($e->getMessage());
            return back()->withErrors(['general' => 'Lỗi hệ thống: ' . $e->getMessage()])->withInput();
        }

        return redirect()
            ->route('client.trips.show', $trip->id)
            ->with('success', 'Đặt vé thành công! Tổng tiền: ' . number_format($totalPrice) . 'đ');
    }

    // API Lấy giá vé (Đã sửa)
    public function getFare(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'pickup_id' => 'required',  // Javascript gửi tên là pickup_id
            'dropoff_id' => 'required', // Javascript gửi tên là dropoff_id
        ]);

        $trip = Trip::find($request->trip_id);

        if (!$trip) {
            return response()->json(['error' => 'Chuyến đi không tồn tại'], 404);
        }

        // Sử dụng hàm chung để tính giá
        $finalPrice = $this->calculateUnitPrice($trip, $request->pickup_id, $request->dropoff_id);

        // Xác định ghi chú (chỉ để hiển thị)
        $isSpecial = $finalPrice != $trip->gia_ve;
        $note = $isSpecial ? "Áp dụng giá chặng cụ thể." : "Áp dụng giá toàn tuyến.";

        return response()->json([
            'trip_id' => $trip->id,
            'final_price' => $finalPrice,
            'formatted_price' => number_format($finalPrice, 0, '.', '.') . ' đ',
            'note' => $note
        ]);
    }

    /**
     * Hàm phụ trợ: Tính đơn giá dựa trên Trip và Điểm đón/trả
     * Giúp code không bị lặp lại và đồng nhất logic
     */
    private function calculateUnitPrice($trip, $pickupId, $dropoffId)
    {
        // ✅ FIX 3: Tìm giá vé đặc biệt
        $specialFare = PointFare::where('route_id', $trip->route_id) // Nên thêm điều kiện route_id cho chắc chắn
            ->where('pickup_point_id', $pickupId)
            ->where('dropoff_point_id', $dropoffId)
            ->first();

        if ($specialFare) {
            return (int) $specialFare->price;
        }

        // ✅ FIX 3: Dùng đúng tên cột gia_ve thay vì price
        return (int) $trip->gia_ve;
    }
}