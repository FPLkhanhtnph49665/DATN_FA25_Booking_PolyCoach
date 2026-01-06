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
                return redirect()->away($this->createVnpayUrl($booking));
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
     * Tạo URL thanh toán VNPAY
     */
    private function createVnpayUrl($booking)
    {
        $vnp_TmnCode = trim(env('VNP_TMN_CODE'));
        $vnp_HashSecret = trim(env('VNP_HASH_SECRET'));
        $vnp_Url = env('VNP_URL');
        $vnp_Returnurl = env('VNP_RETURN_URL');
        
        // Debug log
        Log::info('VNPAY Config Check', [
            'tmn_code' => $vnp_TmnCode,
            'secret_length' => strlen($vnp_HashSecret),
            'secret_preview' => substr($vnp_HashSecret, 0, 10) . '...'
        ]);

        $vnp_TxnRef = $booking->id;
        $vnp_OrderInfo = "Thanhtoandonhang" . $booking->id; // Bỏ dấu cách
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $booking->total_amount * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        ksort($inputData);
        
        $query = "";
        $i = 0;
        $hashdata = "";
        
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . $value;
            } else {
                $hashdata .= urlencode($key) . "=" . $value;
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        // Log để debug
        Log::info('VNPAY Request', [
            'hashdata' => $hashdata,
            'secure_hash' => $vnpSecureHash ?? 'N/A',
            'url' => $vnp_Url
        ]);

        return $vnp_Url;
    }

    /**
     * Xử lý callback từ VNPAY
     */
    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = trim(env('VNP_HASH_SECRET'));
        
        // Debug log
        Log::info('VNPAY Return Config Check', [
            'secret_length' => strlen($vnp_HashSecret),
            'secret_preview' => substr($vnp_HashSecret, 0, 10) . '...'
        ]);
        
        // Lấy tất cả tham số từ URL (VNPAY gửi qua GET)
        $inputData = [];
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                // QUAN TRỌNG: Decode URL trước khi xử lý
                $inputData[$key] = urldecode($value);
            }
        }

        // Log raw data từ VNPAY
        Log::info('VNPAY Return Raw', [
            'get_params' => $_GET,
            'input_data' => $inputData
        ]);

        if (!isset($inputData['vnp_SecureHash'])) {
            Log::error('VNPAY: Missing vnp_SecureHash');
            return redirect()->route('client.trips')->with('error', 'Thiếu chữ ký bảo mật.');
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);

        // Sắp xếp theo key
        ksort($inputData);
        
        // Tạo chuỗi hash GIỐNG HỆT lúc tạo request
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . $value;
            } else {
                $hashdata .= urlencode($key) . "=" . $value;
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        // Log để so sánh chi tiết
        Log::info('VNPAY Hash Compare', [
            'hashdata' => $hashdata,
            'calculated_hash' => $secureHash,
            'vnpay_hash' => $vnp_SecureHash,
            'hash_secret_length' => strlen($vnp_HashSecret),
            'match' => ($secureHash === $vnp_SecureHash)
        ]);

        // Lấy booking
        $bookingId = $inputData['vnp_TxnRef'] ?? null;
        
        if (!$bookingId) {
            Log::error('VNPAY: Missing vnp_TxnRef');
            return redirect()->route('client.trips')->with('error', 'Không tìm thấy mã đơn hàng.');
        }

        $booking = Booking::find($bookingId);

        if (!$booking) {
            Log::error('VNPAY: Booking not found', ['booking_id' => $bookingId]);
            return redirect()->route('client.trips')->with('error', 'Đơn hàng không tồn tại.');
        }

        // Kiểm tra chữ ký
        if ($secureHash === $vnp_SecureHash) {
            if ($inputData['vnp_ResponseCode'] == '00') {
                // Thanh toán thành công
                DB::beginTransaction();
                try {
                    $booking->update([
                        'status' => 'confirmed',
                        'payment_status' => 'paid'
                    ]);

                    Ticket::where('booking_id', $booking->id)->update([
                        'status' => 'sold'
                    ]);

                    DB::commit();
                    
                    Log::info('VNPAY Payment Success', ['booking_id' => $bookingId]);
                    
                    return redirect()->route('client.account.tickets')
                        ->with('success', 'Thanh toán VNPAY thành công! Vé của bạn đã được xác nhận.');

                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Update Booking Error: ' . $e->getMessage());
                    
                    return redirect()->route('client.account.tickets')
                        ->with('error', 'Lỗi cập nhật trạng thái đơn hàng.');
                }
            } else {
                // Thanh toán thất bại
                Log::warning('VNPAY Payment Failed', [
                    'booking_id' => $bookingId,
                    'response_code' => $inputData['vnp_ResponseCode']
                ]);
                
                $booking->update(['status' => 'cancelled']);
                Ticket::where('booking_id', $booking->id)->update(['status' => 'cancelled']);

                return redirect()->route('client.account.tickets')
                    ->with('error', 'Giao dịch thanh toán thất bại hoặc bị hủy bỏ. Mã lỗi: ' . $inputData['vnp_ResponseCode']);
            }
        } else {
            Log::error('VNPAY Signature Mismatch', [
                'booking_id' => $bookingId,
                'calculated' => $secureHash,
                'received' => $vnp_SecureHash,
                'hashdata' => $hashdata
            ]);
            
            return redirect()->route('client.trips')
                ->with('error', 'Chữ ký bảo mật không hợp lệ! Vui lòng liên hệ hỗ trợ.');
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