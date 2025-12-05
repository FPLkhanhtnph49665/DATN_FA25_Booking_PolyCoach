<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Trip;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\PointFare;
use App\Models\BusSeat;

class BookingController extends Controller
{
    /**
     * Xử lý đặt vé
     */
    public function store(Request $request)
    {
        // 1. Kiểm tra đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để đặt vé.');
        }

        // 2. Validate dữ liệu đầu vào
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'seat_codes' => 'required|string', // Chuỗi ghế chọn, ví dụ: "A01,A02"
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email',
            // Cho phép null
            'pickup_point_id' => 'nullable',
            'dropoff_point_id' => 'nullable',
            'payment_method' => 'required|in:cash,vnpay,momo', // Ví dụ các phương thức
        ]);

        $trip = Trip::with('bus')->findOrFail($request->trip_id);

        // 3. Xử lý danh sách ghế
        $seatCodesInput = explode(',', $request->seat_codes);
        $seatCodes = collect($seatCodesInput)
            ->map(fn($s) => trim($s))
            ->unique()
            ->filter()
            ->values();

        if ($seatCodes->isEmpty()) {
            return back()->withErrors(['seat_codes' => 'Vui lòng chọn ít nhất một ghế.'])->withInput();
        }

        // 4. Kiểm tra ghế đã bị đặt chưa (Dựa vào bảng tickets)
        // Tìm các vé của chuyến này, có mã ghế nằm trong danh sách khách chọn, và trạng thái KHÔNG PHẢI là đã hủy
        $existingTickets = Ticket::where('trip_id', $trip->id)
            ->whereIn('seat_code', $seatCodes)
            ->where('status', '!=', 'cancelled') // Giả sử status 'cancelled' là vé hủy
            ->pluck('seat_code')
            ->toArray();

        if (!empty($existingTickets)) {
            return back()
                ->withErrors(['seat_codes' => 'Ghế ' . implode(', ', $existingTickets) . ' đã có người nhanh tay đặt trước.'])
                ->withInput();
        }

        // 2. Tính giá: Truyền tham số vào, nếu null hàm sẽ tự xử lý
        $unitPrice = $this->calculateUnitPrice($trip, $request->pickup_point_id, $request->dropoff_point_id);
        $totalAmount = $unitPrice * $seatCodes->count();

        DB::beginTransaction();
        try {
            // 6. Tạo Booking (Lưu tổng quan đơn hàng)
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'trip_id' => $trip->id,
                'booking_datetime' => now(),
                'total_amount' => $totalAmount,
                'status' => 'pending', // Trạng thái chờ thanh toán/xác nhận
                'payment_method' => $request->payment_method,
                // Lưu ý: Nếu bảng bookings không có cột lưu điểm đón/trả, bạn có thể cân nhắc thêm vào hoặc chỉ lưu ở ticket
            ]);

            // 7. Tạo từng Ticket cho từng ghế (Logic chuẩn: 1 ghế = 1 vé)
            foreach ($seatCodes as $seatCode) {
                // Tùy chọn: Lấy thông tin chi tiết ghế từ bảng bus_seats nếu cần seat_number dạng số
                // $busSeat = BusSeat::where('bus_id', $trip->bus_id)->where('code', $seatCode)->first();
                // 1. Loại bỏ các ký tự chữ (A, B, C...) khỏi chuỗi seat_code
                // Ví dụ: 'A01' => '01' | 'B10' => '10'
                $seatNumberString = preg_replace('/[^0-9]/', '', $seatCode);

                // 2. Chuyển chuỗi số thành số nguyên (int). 
                // PHP sẽ tự động loại bỏ số 0 ở đầu (ví dụ: '01' => 1)
                $seatNumber = (int) $seatNumberString;
                Ticket::create([
                    'trip_id' => $trip->id,
                    'user_id' => Auth::id(),
                    'booking_id' => $booking->id,
                    'seat_code' => $seatCode,
                    'seat_number' => $seatNumber,
                    'status' => 'pending', // Trạng thái vé
                    'payment_method' => $request->payment_method,
                ]);
            }

            // Lưu ý: Bảng payments thường được tạo khi có callback từ cổng thanh toán hoặc admin xác nhận tiền mặt.
            // Nếu muốn tạo ngay payment ở trạng thái pending, bạn có thể thêm logic ở đây.

            DB::commit();

            return redirect()
                ->route('client.account.tickets') // Hoặc trang "Cảm ơn/Lịch sử vé"
                ->with('success', 'Đặt vé thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error($e->getMessage()); // Nên log lỗi ra file
            return back()->withErrors(['error' => 'Có lỗi xảy ra khi xử lý đặt vé: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * API: Lấy giá vé cho Frontend hiển thị (AJAX)
     */
    public function getFare(Request $request)
    {
        // Validate API cũng cho phép null
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'pickup_id' => 'nullable',
            'dropoff_id' => 'nullable',
        ]);

        $trip = Trip::find($request->trip_id);
        if (!$trip) {
            return response()->json(['error' => 'Chuyến không tồn tại'], 404);
        }

        $price = $this->calculateUnitPrice($trip, $request->pickup_id, $request->dropoff_id);

        return response()->json([
            'price' => $price,
            'formatted_price' => number_format($price, 0, ',', '.') . 'đ',
        ]);
    }

    /**
     * Hàm tính đơn giá (Private)
     */
    // Hàm tính giá (QUAN TRỌNG)
    private function calculateUnitPrice($trip, $pickupId, $dropoffId)
    {
        // Nếu khách KHÔNG chọn điểm đón hoặc điểm trả -> Trả về giá gốc luôn
        if (empty($pickupId) || empty($dropoffId)) {
            return (int) ($trip->ticket_price ?? $trip->price ?? 0);
        }

        // Nếu có chọn cả 2, tìm giá chặng
        $pointFare = PointFare::where('route_id', $trip->route_id)
            ->where('pickup_point_id', $pickupId)
            ->where('dropoff_point_id', $dropoffId)
            ->first();

        if ($pointFare) {
            return (int) $pointFare->price;
        }

        // Không thấy giá chặng -> Về giá gốc
        return (int) ($trip->ticket_price ?? $trip->price ?? 0);
    }
}