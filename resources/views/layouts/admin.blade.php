{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị viên - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body>
<div id="admin-wrapper" class="d-flex min-vh-100">

    <!-- Sidebar -->
    <nav id="sidebar">
        <h3 class="text-center">Quản Trị Viên</h3>
        <ul class="nav flex-column mt-4">
            <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a></li>
            <li class="nav-item"><a href="{{ route("admin.users.index") }}" class="nav-link">Quản lý Users</a></li>
            <li class="nav-item"><a href="{{ route('admin.routes.index') }}" class="nav-link">Quản lý Tuyến</a></li>
            <li class="nav-item"><a href="{{ route('admin.buses.index') }}" class="nav-link">Quản lý Xe</a></li>
            <li class="nav-item"><a href="{{ route('admin.trips.index') }}" class="nav-link">Quản lý Chuyến</a></li>
            <li class="nav-item"><a href="{{ route('admin.tickets.index') }}" class="nav-link">Quản lý Vé</a></li>
            <li class="nav-item"><a href="{{ route('admin.payments.index') }}" class="nav-link">Quản lý Thanh toán</a></li>
            <li class="nav-item"><a href="{{ route('admin.passengers.index') }}" class="nav-link">Quản lý Hành khách</a></li>
            <li class="nav-item"><a href="{{ route('admin.reviews.index') }}" class="nav-link">Quản lý Đánh giá</a></li>
            <li class="nav-item"><a href="{{ route('admin.contacts.index') }}" class="nav-link">Quản lý Liên hệ</a></li>
        </ul>
    </nav>

    <!-- Page Content -->
    <div class="flex-grow-1 d-flex flex-column">

        <!-- Header -->
        <nav class="navbar navbar-expand navbar-light bg-light">
            <div class="container-fluid">
                <!-- Toggle Button -->
                <button class="btn btn-sm btn-outline-secondary me-3" id="sidebarToggle">☰</button>
                <span class="navbar-text fw-bold fs-2 me-auto">Quản trị hệ thống PolyCoach</span>
                <span class="navbar-brand mb-0 h1">@yield('title', 'Dashboard')</span>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="p-4 flex-grow-1">
            @yield('content')
        </div>
    </div>
</div>

</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
    });
</script>
@stack('scripts')
</body>
</html>
