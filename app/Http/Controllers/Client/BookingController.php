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

        // 5. Lấy thông tin giá vé (Cập nhật ở đây)
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
            return redirect()->route('client.account.tickets')
                ->with('success', 'Đặt vé thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi đặt vé: ' . $e->getMessage()])->withInput();
        }
    }


    /**
     * API lấy giá vé
     */
    public function getFare(Request $request)
    {
        $trip = Trip::find($request->trip_id);

        // Gọi hàm mới
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
        // Mặc định
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
