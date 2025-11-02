@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h1 class="mb-4">Chào mừng đến trang Admin Dashboard</h1>

    {{-- Dòng 1: Users, Buses, Trips, Payments --}}
    <div class="row g-4 mb-4">
        <!-- Users -->
        <div class="col-md-3 col-sm-6">
            <div class="card text-white bg-primary shadow-sm rounded-4">
                <div class="card-body text-center p-4">
                    <h5 class="card-title">Người dùng</h5>
                    <p class="card-text display-5 fw-bold">{{ $totalUsers }}</p>
                    <i class="bi bi-people-fill fs-1 mt-2"></i>
                </div>
            </div>
        </div>

        <!-- Buses -->
        <div class="col-md-3 col-sm-6">
            <div class="card text-white bg-warning shadow-sm rounded-4">
                <div class="card-body text-center p-4">
                    <h5 class="card-title">Xe khách</h5>
                    <p class="card-text display-5 fw-bold">{{ $activeBuses }} / {{ $totalBuses }}</p>
                    <small>Đang hoạt động / Tổng số</small>
                    <i class="bi bi-bus-front-fill fs-1 mt-2"></i>
                </div>
            </div>
        </div>

        <!-- Trips -->
        <div class="col-md-3 col-sm-6">
            <div class="card text-white bg-danger shadow-sm rounded-4">
                <div class="card-body text-center p-4">
                    <h5 class="card-title">Chuyến xe</h5>
                    <p class="card-text display-5 fw-bold">{{ $activeTrips }} / {{ $totalTrips }}</p>
                    <small>Đang hoạt động / Tổng số</small>
                    <i class="bi bi-signpost-2-fill fs-1 mt-2"></i>
                </div>
            </div>
        </div>

        <!-- Payments -->
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

    {{-- Dòng 2: Tiền mặt, MoMo --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card text-white bg-primary shadow-sm rounded-4">
                <div class="card-body text-center p-4">
                    <h5 class="card-title">Tiền mặt</h5>
                    <p class="card-text display-5 fw-bold">
                        {{ number_format($totalCash, 0, ',', '.') }}₫
                    </p>
                    <i class="bi bi-wallet2 fs-1 mt-2"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card text-white bg-warning shadow-sm rounded-4">
                <div class="card-body text-center p-4">
                    <h5 class="card-title">MoMo</h5>
                    <p class="card-text display-5 fw-bold">
                        {{ number_format($totalMomo, 0, ',', '.') }}₫
                    </p>
                    <i class="bi bi-phone fs-1 mt-2"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Biểu đồ --}}
    <div class="card shadow-sm rounded-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Thống kê Payments theo tháng</h5>
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
        const momoData = paymentsData.map(item => item.momo_total);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Tiền mặt',
                        data: cashData,
                        backgroundColor: 'rgb(2, 164, 185)',
                        borderRadius: 8,
                    },
                    {
                        label: 'MoMo',
                        data: momoData,
                        backgroundColor: 'rgb(250, 4, 176)',
                        borderRadius: 8,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
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
                            callback: value => new Intl.NumberFormat('vi-VN').format(value) + '₫'
                        }
                    }
                }
            }
        });
    </script>
@endsection
