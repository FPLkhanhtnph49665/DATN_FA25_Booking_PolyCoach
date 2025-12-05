<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Route;
use App\Models\City; // Cần import model City

class HomeController extends Controller
{
    /**
     * Trang chủ Client
     */
    public function index()
    {
        // 1. Lấy 8 chuyến đi phổ biến (Dựa trên số lượng vé đã bán)
        // Lưu ý: Vẫn cần đảm bảo model Trip có function tickets() return hasMany
        $popularTrips = Trip::with(['route.fromCity', 'route.toCity', 'bus'])
            ->where('status', 1) // Chỉ lấy các chuyến đi đang hoạt động (Active)
            ->where('departure_date', '>=', now()->toDateString()) // Chỉ lấy các chuyến chưa khởi hành
            ->withCount('tickets') // Đếm số vé
            ->orderBy('tickets_count', 'desc') // Sắp xếp theo số vé giảm dần
            ->take(8)
            ->get();

        // 2. Lấy tất cả các thành phố để làm điểm đi / điểm đến (từ bảng cities)
        // Lấy tên và ID của tất cả các thành phố đang hoạt động
        $allCities = City::select('id', 'name')
                            ->where('status', 1)
                            ->orderBy('name', 'asc')
                            ->get();

        // Tách ra thành điểm đi và điểm đến (hoặc có thể dùng chung $allCities)
        $allFrom = $allCities;
        $allTo = $allCities;

        // Lưu ý: Nếu bạn muốn filter chỉ các thành phố CÓ TUYẾN ĐƯỜNG, 
        // bạn cần query join hoặc dùng whereHas. 
        // Ví dụ:
        // $allFromIds = Route::distinct()->pluck('from_city_id');
        // $allFrom = City::whereIn('id', $allFromIds)->get();
        return view('client.home', compact('allFrom', 'allTo', 'popularTrips'));
    }
}