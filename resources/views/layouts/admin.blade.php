{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PolyCoach Admin - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons (để dùng các icon bi bi-*) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Admin custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <link rel="icon" href="{{ asset('iconPoLyCoach.png') }}" alt="Icon PolyCoach">
    @stack('styles')
</head>

<body class="admin-body">
    <div id="admin-wrapper" class="d-flex min-vh-100">

        <!-- Sidebar -->
        <nav id="sidebar" class="admin-sidebar d-flex flex-column">
            <div class="sidebar-header text-center py-4">
                <a href="{{ route('admin.dashboard') }}"
                    class="d-flex flex-column align-items-center text-decoration-none">
                    <div class="sidebar-logo-wrapper mb-2">
                        <img src="{{ asset('logoPoLyCoach.png') }}" alt="Logo PolyCoach" class="sidebar-logo img-fluid">
                    </div>
                    <span class="sidebar-brand">PolyCoach Admin</span>
                </a>
            </div>

            <div class="sidebar-menu flex-grow-1">
                <ul class="nav flex-column px-2">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 me-2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="bi bi-people me-2"></i>
                            <span>Quản lý người dùng</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.cities.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.cities.*') ? 'active' : '' }}">
                            <i class="bi bi-geo-alt me-2"></i>
                            <span>Quản lý Thành phố</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.point_fares.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs(patterns: 'admin.point_fares.*') ? 'active' : '' }}">
                            <i class="bi bi-pin-map me-2"></i>
                            {{-- Sửa tên hiển thị --}}
                            <span>Giá vé chặng</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.routes.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.routes.*') ? 'active' : '' }}">
                            <i class="bi bi-signpost-split me-2"></i>
                            <span>Quản lý Tuyến</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.buses.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.buses.*') ? 'active' : '' }}">
                            <i class="bi bi-truck-front me-2"></i>
                            <span>Quản lý Xe</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.trips.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.trips.*') ? 'active' : '' }}">
                            <i class="bi bi-calendar2-week me-2"></i>
                            <span>Quản lý Chuyến</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.bookings.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                            <i class="bi bi-ticket-detailed me-2"></i>
                            <span>Quản lý Booking</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.tickets.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
                            <i class="bi bi-ticket-perforated me-2"></i>
                            <span>Quản lý Vé</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.payments.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                            <i class="bi bi-credit-card-2-front me-2"></i>
                            <span>Quản lý Thanh toán</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.passengers.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.passengers.*') ? 'active' : '' }}">
                            <i class="bi bi-person-walking me-2"></i>
                            <span>Quản lý Hành khách</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.reviews.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                            <i class="bi bi-chat-dots me-2"></i>
                            <span>Quản lý Đánh giá</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.contacts.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                            <i class="bi bi-envelope-paper me-2"></i>
                            <span>Quản lý Liên hệ</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-footer text-center py-3 small">
                <span class="text-muted">© {{ now()->year }} PolyCoach</span>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="layout-main flex-grow-1 d-flex flex-column">

            <!-- Header -->
            <nav class="navbar navbar-expand-lg admin-navbar shadow-sm">
                <div class="container-fluid">

                    <!-- Toggle Sidebar -->
                    <button class="btn btn-sm btn-outline-light me-3" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>

                    <!-- Title -->
                    <div class="d-flex flex-column">
                        <span class="admin-title">Quản trị hệ thống PolyCoach</span>
                        <span class="admin-subtitle">@yield('title', 'Tổng quan hệ thống')</span>
                    </div>

                    <!-- User Info + Logout -->
                    <ul class="navbar-nav ms-auto align-items-center">
                        @php
                            $user = Auth::user();
                        @endphp

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-light"
                                href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">

                                {{-- Avatar tròn --}}
                                <div class="avatar-circle">
                                    <span>{{ strtoupper(mb_substr($user->first_name ?? ($user->name ?? 'A'), 0, 1)) }}</span>
                                </div>

                                {{-- Tên & Role --}}
                                <div class="d-flex flex-column text-start">
                                    <span class="user-name fw-semibold">
                                        {{ $user->full_name ?? ($user->name ?? 'Admin') }}
                                    </span>
                                    <span class="user-role small text-capitalize opacity-75">
                                        {{ $user->role ?? 'admin' }}
                                    </span>
                                </div>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-gear me-2"></i> Hồ sơ
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    {{-- KHÔNG JS, KHÔNG FORM ẨN, GỬI THẲNG POST --}}
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger w-100 text-start">
                                            <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>


                </div>
            </nav>

            <!-- Main Content -->
            <main class="admin-main p-4 flex-grow-1">
                <div class="admin-main-inner">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        const body = document.body;

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            body.classList.toggle('sidebar-collapsed');
        });
    </script>

    @stack('scripts')
</body>

</html>
