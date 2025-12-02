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
        $allFrom = Route::select('diem_di')->distinct()->pluck('diem_di');
        $allTo = Route::select('diem_den')->distinct()->pluck('diem_den');

        return view('client.home', compact('allFrom', 'allTo', 'popularTrips'));
    }
}
