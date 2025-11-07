<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Trip;

class BookingController extends Controller
{
    // Lưu booking
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'seats' => 'required|integer|min:1',
        ]);

        $trip = Trip::findOrFail($request->trip_id);

        // Kiểm tra ghế còn trống
        $bookedSeats = $trip->bookings()->sum('seats'); // tổng ghế đã đặt
        $availableSeats = ($trip->bus->seat_count ?? 0) - $bookedSeats;

        if ($request->seats > $availableSeats) {
            return back()->withErrors(['seats' => "Chỉ còn $availableSeats ghế trống trên chuyến này."])
                         ->withInput();
        }

        // Lưu booking
        Booking::create([
            'trip_id' => $trip->id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'seats' => $request->seats,
        ]);

        return redirect()->route('client.trips.show', $trip->id)
                         ->with('success', "Đặt vé thành công!");
    }
}
