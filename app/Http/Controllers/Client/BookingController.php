<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Trip;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\PointFare;
use App\Models\Passenger;
use App\Models\Payment;
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
            'seat_codes' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email',
            'pickup_point_id' => 'nullable',
            'dropoff_point_id' => 'nullable',
            'payment_method' => 'required|in:cash,vnpay,momo',
        ]);

        $trip = Trip::with('bus')->findOrFail($request->trip_id);

        // 3. Xử lý danh sách ghế
        $seatCodes = collect(explode(',', $request->seat_codes))
            ->map(fn($s) => trim($s))
            ->unique()
            ->filter()
            ->values();

        if ($seatCodes->isEmpty()) {
            return back()->withErrors(['seat_codes' => 'Vui lòng chọn ít nhất một ghế.'])->withInput();
        }

        // 4. Kiểm tra ghế đã bị đặt trước hay chưa
        $existing = Ticket::where('trip_id', $trip->id)
            ->whereIn('seat_code', $seatCodes)
            ->where('status', '!=', 'cancelled')
            ->pluck('seat_code')
            ->toArray();

        if (!empty($existing)) {
            return back()->withErrors([
                'seat_codes' => 'Ghế ' . implode(', ', $existing) . ' đã có người đặt trước.'
            ])->withInput();
        }

        // 5. Lấy thông tin giá vé
        $fareInfo = $this->calculateFareInfo($trip, $request->pickup_point_id, $request->dropoff_point_id);
        $totalAmount = $fareInfo->price * $seatCodes->count();

        DB::beginTransaction();
        try {
            // 6. Tạo Booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'trip_id' => $trip->id,
                'booking_datetime' => now(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
            ]);

            // 7. Tạo Ticket cho từng ghế
            foreach ($seatCodes as $seatCode) {
                $seatNumber = (int) preg_replace('/[^0-9]/', '', $seatCode);

                $ticket = Ticket::create([
                    'trip_id' => $trip->id,
                    'user_id' => Auth::id(),
                    'booking_id' => $booking->id,
                    'point_fare_id' => $fareInfo->point_fare_id,
                    'seat_code' => $seatCode,
                    'seat_number' => $seatNumber,
                    'price' => $fareInfo->price,
                    'status' => 'pending',
                    'payment_method' => $request->payment_method,
                ]);

                // 8. Tạo Passenger cho từng ghế
                Passenger::create([
                    'ticket_id' => $ticket->id,
                    'name' => $request->customer_name,
                    'phone' => $request->customer_phone,
                    'email' => $request->customer_email,
                    'age' => null,
                    'seat_number' => $seatNumber,
                ]);
            }

            DB::commit();

            // 9. Xử lý thanh toán nếu chọn VNPAY
            if ($request->payment_method === 'vnpay') {
                $vnpayUrl = $this->vnpay_payment($booking);
                return redirect()->away($vnpayUrl);
            }

            // Nếu là tiền mặt
            return redirect()->route('client.account.tickets')
                ->with('success', 'Đặt vé thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Lỗi đặt vé: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Tạo URL thanh toán VNPAY (ĐÃ SỬA LỖI CHỮ KÝ)
     */
    public function vnpay_payment($booking)
    {
        // Cấu hình từ ảnh bạn cung cấp
        $vnp_TmnCode = "LCI5IE58";
        $vnp_HashSecret = "CU8UV3X75ESW6JTTQYWELYQMOZ17HKCG";
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.return');

        $vnp_TxnRef = $booking->id . '_' . time();
        $vnp_OrderInfo = "Thanh toan ve xe " . $booking->id;
        $vnp_Amount = (int) $booking->total_amount * 100;

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => request()->ip(),
            "vnp_Locale" => 'vn',
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => 'billpayment',
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        return $vnp_Url;
    }

    /**
     * Xử lý callback từ VNPAY
     */
    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = "CU8UV3X75ESW6JTTQYWELYQMOZ17HKCG";
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = [];

        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // 1. Kiểm tra chữ ký
        if ($secureHash !== $vnp_SecureHash) {
            Log::error("VNPAY Error: Sai chữ ký xác thực.");
            return redirect()->route('client.account.tickets')->with('error', 'Sai chữ ký xác thực từ VNPAY.');
        }

        // 2. Lấy và kiểm tra tồn tại của Booking
        $txnRefParts = explode('_', $request->vnp_TxnRef);
        $bookingId = $txnRefParts[0];
        $booking = Booking::with('tickets')->find($bookingId);

        if (!$booking) {
            Log::error("VNPAY Error: Không tìm thấy Booking ID: " . $bookingId);
            return redirect()->route('client.account.tickets')->with('error', 'Đơn hàng không tồn tại trên hệ thống.');
        }

        // 3. Kiểm tra trạng thái thanh toán từ VNPAY
        if ($request->vnp_ResponseCode !== '00') {
            Log::warning("VNPAY Warning: Giao dịch thất bại. Mã lỗi: " . $request->vnp_ResponseCode);
            return redirect()->route('client.account.tickets')->with('error', 'Thanh toán thất bại hoặc đã bị hủy.');
        }

        // 4. Xử lý lưu database trong Transaction
        DB::beginTransaction();
        try {
            // Cập nhật trạng thái Booking
            $booking->status = 'paid';
            $booking->save();

            // Cập nhật trạng thái cho toàn bộ vé thuộc booking này
            if ($booking->tickets->isNotEmpty()) {
                foreach ($booking->tickets as $ticket) {
                    $ticket->status = 'paid';
                    $ticket->save();
                }
            }

            // 5. LƯU VÀO BẢNG PAYMENTS (Chỉ tạo 1 bản ghi duy nhất cho Booking)
            // Lưu ý: Đã thay ticket_id bằng booking_id
            Payment::create([
                'booking_id' => $booking->id, // Sử dụng booking_id mới
                'user_id' => $booking->user_id,
                'amount' => $request->vnp_Amount / 100, // VNPAY gửi số tiền nhân với 100
                'payment_method' => 'vnpay',
                'status' => 'success',
                'transaction_code' => $request->vnp_TransactionNo,
            ]);

            DB::commit();
            return redirect()->route('client.account.tickets')->with('success', 'Thanh toán thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Database Error tại vnpayReturn: " . $e->getMessage());
            return redirect()->route('client.account.tickets')->with('error', 'Lỗi hệ thống khi cập nhật đơn hàng.');
        }
    }
    /**
     * API lấy giá vé
     */
    public function getFare(Request $request)
    {
        $trip = Trip::find($request->trip_id);

        $fareInfo = $this->calculateFareInfo($trip, $request->pickup_id, $request->dropoff_id);

        return response()->json([
            'price' => $fareInfo->price,
            'point_fare_id' => $fareInfo->point_fare_id,
            'formatted_price' => number_format($fareInfo->price, 0, ',', '.') . 'đ',
        ]);
    }

    /**
     * Hàm tính đơn giá
     */
    private function calculateFareInfo($trip, $pickupId, $dropoffId)
    {
        $info = [
            'price' => (int) ($trip->ticket_price ?? $trip->price ?? 0),
            'point_fare_id' => null
        ];

        if (!empty($pickupId) && !empty($dropoffId)) {
            $pf = PointFare::where('route_id', $trip->route_id)
                ->where('pickup_point_id', $pickupId)
                ->where('dropoff_point_id', $dropoffId)
                ->first();

            if ($pf) {
                $info['price'] = (int) $pf->price;
                $info['point_fare_id'] = $pf->id;
            }
        }

        return (object) $info;
    }
}