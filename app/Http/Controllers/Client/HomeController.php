<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Route;

class HomeController extends Controller
{
    /**
     * Trang chủ Client
     */
    public function index()
    {
        // Lấy 5 chuyến sắp tới
        $trips = Trip::with(['route', 'bus'])
                     ->where('ngay_khoi_hanh', '>=', now())
                     ->orderBy('ngay_khoi_hanh', 'asc')
                     ->take(5)
                     ->get();

        // Có thể thêm các dữ liệu khác như banner, reviews, products tùy hệ thống

        // Lấy tất cả điểm đi / điểm đến từ bảng routes
        $allFrom = Route::select('diem_di')->distinct()->pluck('diem_di');
        $allTo   = Route::select('diem_den')->distinct()->pluck('diem_den');

        return view('client.home', compact('trips', 'allFrom', 'allTo'));
    }
}
