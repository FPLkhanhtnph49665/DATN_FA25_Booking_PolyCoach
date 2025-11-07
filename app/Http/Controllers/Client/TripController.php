<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trip;

class TripController extends Controller
{
    // Trang danh sách chuyến
    public function index(Request $request)
    {
        $query = Trip::query();

        // Lọc theo điểm đi, điểm đến, ngày đi
        if ($request->filled('from')) {
            $query->where('from', 'like', '%' . $request->from . '%');
        }

        if ($request->filled('to')) {
            $query->where('to', 'like', '%' . $request->to . '%');
        }

        if ($request->filled('date')) {
            $query->whereDate('departure_time', $request->date);
        }

        $trips = $query->paginate(10);

        return view('client.trips.index', compact('trips'));
    }

    // Form tìm kiếm chuyến

    public function search(Request $request)
{
    $query = Trip::query();

    // Lọc theo điểm đi
    if ($request->filled('from')) {
        $query->where('from', 'like', '%' . $request->from . '%');
    }

    // Lọc theo điểm đến
    if ($request->filled('to')) {
        $query->where('to', 'like', '%' . $request->to . '%');
    }

    // Lọc theo ngày đi
    if ($request->filled('date')) {
        $query->whereDate('departure_time', $request->date); // giả sử cột ngày đi là departure_time
    }

    // Lọc theo số ghế trống
    if ($request->filled('seats')) {
        $query->where('available_seats', '>=', $request->seats); // giả sử cột số ghế trống là available_seats
    }

    // Phân trang 10 chuyến/ trang và giữ query string
    $trips = $query->paginate(10)->withQueryString();

    return view('client.trips.index', compact('trips'));
}
}
