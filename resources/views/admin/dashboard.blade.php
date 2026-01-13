@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h1 class="mb-4">Chào mừng đến trang Admin Dashboard</h1>

    {{-- Dòng 1: Users, Buses, Trips, Payments --}}
    <div class="row g-4 mb-4">
        {{-- Users --}}
        <div class="col-md-3 col-sm-6">
            <div class="card text-white bg-primary shadow-sm rounded-4">
                <div class="card-body text-center p-4">
                    <h5 class="card-title">Người dùng</h5>
                    <p class="card-text display-5 fw-bold">{{ $totalUsers }}</p>
                    <i class="bi bi-people-fill fs-1 mt-2"></i>
                </div>
            </div>
        </div>

        {{-- Buses --}}
        <div class="col-md-3 col-sm-6">
            <div class="card text-white bg-warning shadow-sm rounded-4">
                <div class="card-body text-center p-4">
                    <h5 class="card-title">Xe khách</h5>
                    <p class="card-text display-5 fw-bold">
                        {{ $activeBuses }} / {{ $totalBuses }}
                    </p>
                    <small>Đang hoạt động / Tổng số</small>
                    <i class="bi bi-bus-front-fill fs-1 mt-2"></i>
                </div>
            </div>
        </div>

        {{-- Trips --}}
        <div class="col-md-3 col-sm-6">
            <div class="card text-white bg-danger shadow-sm rounded-4">
                <div class="card-body text-center p-4">
                    <h5 class="card-title">Chuyến xe</h5>
                    <p class="card-text display-5 fw-bold">
                        {{ $activeTrips }} / {{ $totalTrips }}
                    </p>
                    <small>Đang hoạt động / Tổng số</small>
                    <i class="bi bi-signpost-2-fill fs-1 mt-2"></i>
                </div>
            </div>
        </div>

        {{-- Total Payments --}}
        <div class="col-md-3 col-sm-6">
            <div class="card text-white bg-success shadow-sm rounded-4">
                <div class="card-body text-center p-4">
                    <h5 class="card-title">Tổng thanh toán</h5>
                    <p class="card-text display-5 fw-bold">
                        {{ number_format($totalPayments, 0, ',', '.') }}₫
                    </p>
                    <i class="bi bi-cash-stack fs-1 mt-2"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Dòng 2: Tiền mặt & VNPay --}}
    <div class="row g-4 mb-4">
        {{-- Cash --}}
        <div class="col-md-6">
            <div class="card text-white shadow-sm rounded-4" style="background-color: #0099CC;">
                <div class="card-body text-center p-4">
                    <h5 class="card-title">Tiền mặt</h5>
                    <p class="card-text display-5 fw-bold">
                        {{ number_format($totalCash, 0, ',', '.') }}₫
                    </p>
                    <i class="bi bi-wallet2 fs-1 mt-2"></i>
                </div>
            </div>
        </div>

        {{-- VNPay --}}
        <div class="col-md-6">
            <div class="card text-white shadow-sm rounded-4" style="background-color: #005BAC;">
                <div class="card-body text-center p-4">
                    <h5 class="card-title">VNPAY</h5>
                    <p class="card-text display-5 fw-bold">
                        {{ number_format($totalVnpay, 0, ',', '.') }}₫
                    </p>
                    <i class="bi bi-credit-card-2-front-fill fs-1 mt-2"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Biểu đồ --}}
    <div class="card shadow-sm rounded-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Thống kê thanh toán theo tháng</h5>
            <canvas id="paymentsChart"></canvas>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('paymentsChart').getContext('2d');
        const paymentsData = @json($paymentsByMonth);

        const labels = paymentsData.map(item => 'Tháng ' + item.month);
        const cashData = paymentsData.map(item => item.cash_total);
        const vnpayData = paymentsData.map(item => item.vnpay_total);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Tiền mặt',
                        data: cashData,
                        backgroundColor: '#0099CC',
                        borderRadius: 8,
                    },
                    {
                        label: 'VNPAY',
                        data: vnpayData,
                        backgroundColor: '#005BAC',
                        borderRadius: 8,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.dataset.label + ': ' +
                                    new Intl.NumberFormat('vi-VN').format(context.raw) + '₫';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + '₫';
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
