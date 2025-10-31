<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bus;
use App\Models\Trip;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalBuses = Bus::count();
        $activeBuses = Bus::where('trang_thai', 1)->count();
        $totalTrips = Trip::count();
        $activeTrips = Trip::where('trang_thai', 1)->count();
        $totalPayments = Payment::count(); // Thay Customers bằng Payments

        // Dữ liệu cho biểu đồ (ví dụ: số Payment theo tháng)
        $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $paymentsPerMonth = Payment::selectRaw('MONTH(created_at) as month, count(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total')
            ->toArray();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalBuses',
            'activeBuses',
            'totalTrips',
            'activeTrips',
            'totalPayments',
            'months',
            'paymentsPerMonth'
        ));
    }
}
