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
        $totalTrips = Trip::count();
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
            'totalTrips',
            'totalPayments',
            'months',
            'paymentsPerMonth'
        ));
    }
}
