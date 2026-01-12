<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Bus, Trip, User, Payment};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard with statistics.
     */
    public function index()
    {
        // Ghi chú: Sử dụng withTrashed() để vô hiệu hóa điều kiện WHERE deleted_at IS NULL.
        // Điều này cho phép tính toán trên TẤT CẢ các bản ghi (kể cả đã xóa mềm).

        // 🔢 Basic statistics
        // Đếm tổng số Users (bao gồm cả đã xóa mềm)
        $totalUsers = User::withTrashed()->count();

        // Đếm tổng số Buses (bao gồm cả đã xóa mềm)
        $totalBuses = Bus::withTrashed()->count();

        // Đếm số lượng Buses đang hoạt động (status = 1), bao gồm cả đã xóa mềm

        $activeBuses = Bus::withTrashed()->where('status', 1)->count();

        // Đếm tổng số Trips (bao gồm cả đã xóa mềm)
        $totalTrips = Trip::withTrashed()->count();

        // Đếm số lượng Trips đang hoạt động (status = 1), bao gồm cả đã xóa mềm

        $activeTrips = Trip::withTrashed()->where('status', 1)->count();

        // 💰 Payment statistics
        // Tính tổng số tiền Payments (bao gồm cả đã xóa mềm)
        $totalPayments = Payment::withTrashed()->sum('amount');

        // Tính tổng tiền mặt (Cash), bao gồm cả đã xóa mềm
        $totalCash = Payment::withTrashed()->where('payment_method', 'cash')->sum('amount');

        // Tính tổng tiền vnpay, bao gồm cả đã xóa mềm
        $totalVnpay = Payment::withTrashed()->where('payment_method', 'vnpay')->sum('amount');

        // 📈 Payments by month/year
        // Thống kê thanh toán theo tháng/năm, bao gồm cả đã xóa mềm
        $paymentsByMonth = Payment::withTrashed()->select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw("SUM(CASE WHEN payment_method = 'cash' THEN amount ELSE 0 END) as cash_total"),
            DB::raw("SUM(CASE WHEN payment_method = 'vnpay' THEN amount ELSE 0 END) as vnpay_total")
        )
            ->groupBy('year', 'month')
            ->orderBy('year')
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
            'totalVnpay',
            'paymentsByMonth'
        ));
    }
}
