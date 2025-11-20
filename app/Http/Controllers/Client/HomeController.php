<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trip;

class HomeController extends Controller
{
    /**
     * Trang chủ Client
     */
    public function index()
    {
        // Lấy 5 chuyến sắp tới
        $trips = Trip::with(['route', 'bus'])
                     ->where('departure_date', '>=', now())
                     ->orderBy('departure_time', 'asc')
                     ->take(5)
                     ->get();

        // Có thể thêm các dữ liệu khác như banner, reviews, products tùy hệ thống
        return view('client.home', compact('trips'));
    }
}
