<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bus;
use App\Models\Trip;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalBuses = Bus::count();
        $activeBuses = Bus::where('trang_thai', 1)->count();
        $totalTrips = Trip::count();
        $activeTrips = Trip::where('trang_thai', 1)->count();

        // 💰 Tổng hợp thanh toán
        $totalPayments = Payment::sum('so_tien');
        $totalCash = Payment::where('phuong_thuc', 'Tiền mặt')->sum('so_tien');
        $totalMomo = Payment::where('phuong_thuc', 'MoMo')->sum('so_tien');

        // 📊 Biểu đồ Payments theo tháng
        $paymentsByMonth = Payment::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw("SUM(CASE WHEN phuong_thuc = 'Tiền mặt' THEN so_tien ELSE 0 END) as cash_total"),
            DB::raw("SUM(CASE WHEN phuong_thuc = 'MoMo' THEN so_tien ELSE 0 END) as momo_total")
        )
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalBuses',
            'activeBuses',
            'totalTrips',
            'activeTrips',
            'totalPayments',
            'totalCash',
            'totalMomo',
            'paymentsByMonth'
        ));
    }
}
