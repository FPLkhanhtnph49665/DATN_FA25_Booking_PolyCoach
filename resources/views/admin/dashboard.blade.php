@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<h1 class="mb-4">Chào mừng đến trang Admin Dashboard</h1>

<div class="row g-4">
    <!-- Users Card -->
    <div class="col-md-3 col-sm-6">
        <div class="card text-white bg-primary shadow-sm rounded-4">
            <div class="card-body text-center p-4">
                <h5 class="card-title">Users</h5>
                <p class="card-text display-5 fw-bold">{{ $totalUsers }}</p>
                <i class="bi bi-people-fill fs-1 mt-2"></i>
            </div>
        </div>
    </div>

    <!-- Buses Card -->
    <div class="col-md-3 col-sm-6">
        <div class="card text-white bg-warning shadow-sm rounded-4">
            <div class="card-body text-center p-4">
                <h5 class="card-title">Buses</h5>
                <p class="card-text display-5 fw-bold">{{ $totalBuses }}</p>
                <i class="bi bi-bus-front-fill fs-1 mt-2"></i>
            </div>
        </div>
    </div>

    <!-- Trips Card -->
    <div class="col-md-3 col-sm-6">
        <div class="card text-white bg-danger shadow-sm rounded-4">
            <div class="card-body text-center p-4">
                <h5 class="card-title">Trips</h5>
                <p class="card-text display-5 fw-bold">{{ $totalTrips }}</p>
                <i class="bi bi-signpost-2-fill fs-1 mt-2"></i>
            </div>
        </div>
    </div>

    <!-- Payments Card -->
    <div class="col-md-3 col-sm-6">
        <div class="card text-white bg-success shadow-sm rounded-4">
            <div class="card-body text-center p-4">
                <h5 class="card-title">Payments</h5>
                <p class="card-text display-5 fw-bold">{{ $totalPayments }}</p>
                <i class="bi bi-cash-stack fs-1 mt-2"></i>
            </div>
        </div>
    </div>
</div>

<!-- Biểu đồ Payment theo tháng -->
<div class="card mt-4 shadow-sm rounded-4">
    <div class="card-body">
        <h5 class="card-title">Payments theo tháng</h5>
        <canvas id="paymentsChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('paymentsChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($months),
        datasets: [{
            label: 'Payments',
            data: @json($paymentsPerMonth),
            backgroundColor: 'rgba(40, 167, 69, 0.6)',
            borderColor: 'rgba(40, 167, 69, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            title: { display: true, text: 'Số Payments theo tháng' }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endsection
