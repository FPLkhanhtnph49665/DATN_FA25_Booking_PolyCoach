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
        // 1. Lấy 8 chuyến đi phổ biến (Dựa trên số lượng vé đã bán)
        // Lưu ý: Cần đảm bảo model Trip có function tickets() return hasMany
        $popularTrips = Trip::with(['route', 'bus'])
            ->withCount('tickets') // Đếm số vé
            //  ->where('ngay_khoi_hanh', '<=', now())
            ->orderBy('tickets_count', 'desc') // Sắp xếp theo số vé giảm dần
            ->take(8)
            ->get();

        // Có thể thêm các dữ liệu khác như banner, reviews, products tùy hệ thống

        // Lấy tất cả điểm đi / điểm đến từ bảng routes
        $allFrom = Route::select('from_city_id')->distinct()->pluck('from_city_id');
        $allTo = Route::select('to_city_id')->distinct()->pluck('to_city_id');

        return view('client.home', compact('allFrom', 'allTo', 'popularTrips'));
    }
}
