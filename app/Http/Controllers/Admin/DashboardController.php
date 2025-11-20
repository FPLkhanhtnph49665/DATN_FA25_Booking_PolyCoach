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
        // 🔢 Basic statistics
        $totalUsers   = User::count();
        $totalBuses   = Bus::count();
        $activeBuses  = Bus::where('status', 1)->count();
        $totalTrips   = Trip::count();
        $activeTrips  = Trip::where('status', 1)->count();

        // 💰 Payment statistics
        $totalPayments = Payment::sum('amount');
        $totalCash     = Payment::where('payment_method', 'cash')->sum('amount');
        $totalMomo     = Payment::where('payment_method', 'momo')->sum('amount');

        // 📈 Payments by month/year
        $paymentsByMonth = Payment::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw("SUM(CASE WHEN payment_method = 'cash' THEN amount ELSE 0 END) as cash_total"),
            DB::raw("SUM(CASE WHEN payment_method = 'momo' THEN amount ELSE 0 END) as momo_total")
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
            'totalMomo',
            'paymentsByMonth'
        ));
    }
}
