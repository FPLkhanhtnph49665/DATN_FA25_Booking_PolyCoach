{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PolyCoach Admin - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <link rel="icon" href="{{ asset('iconPoLyCoach.png') }}">
    @stack('styles')
</head>

<body class="admin-body">
    <div id="admin-wrapper" class="d-flex min-vh-100">

        <!-- SIDEBAR -->
        <nav id="sidebar" class="admin-sidebar d-flex flex-column">

            <!-- Logo -->
            <div class="sidebar-header text-center py-4">
                <a href="{{ route('admin.dashboard') }}"
                    class="d-flex flex-column align-items-center text-decoration-none">
                    <div class="sidebar-logo-wrapper mb-2">
                        <img src="{{ asset('logoPoLyCoach.png') }}" class="sidebar-logo img-fluid" alt="Logo PolyCoach">
                    </div>
                    <span class="sidebar-brand">PolyCoach Admin</span>
                </a>
            </div>

            <!-- Menu -->
            <div class="sidebar-menu flex-grow-1">
                <ul class="nav flex-column px-2">

                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="bi bi-people me-2"></i> Quản lý người dùng
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.cities.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.cities.*') ? 'active' : '' }}">
                            <i class="bi bi-geo-alt me-2"></i> Quản lý Thành phố
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.pickup-dropoff-points.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.pickup-dropoff-points.*') ? 'active' : '' }}">
                            <i class="bi bi-pin-map me-2"></i> Điểm đón/trả
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.point_fares.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.point_fares.*') ? 'active' : '' }}">
                            <i class="bi bi-pin-map me-2"></i> Giá vé chặng
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.routes.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.routes.*') ? 'active' : '' }}">
                            <i class="bi bi-signpost-split me-2"></i> Quản lý Tuyến
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.buses.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.buses.*') ? 'active' : '' }}">
                            <i class="bi bi-truck-front me-2"></i> Quản lý Xe
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.trips.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.trips.*') ? 'active' : '' }}">
                            <i class="bi bi-calendar2-week me-2"></i> Quản lý Chuyến
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.bookings.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                            <i class="bi bi-ticket-detailed me-2"></i> Quản lý Booking
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.tickets.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
                            <i class="bi bi-ticket-perforated me-2"></i> Quản lý Vé
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.payments.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                            <i class="bi bi-credit-card-2-front me-2"></i> Quản lý Thanh toán
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.news.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                             <i class="bi bi-newspaper me-2"></i>Quản lý Bài viết
                        </a>
                    </li>


                    <li class="nav-item">
                        <a href="{{ route('admin.reviews.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                            <i class="bi bi-chat-dots me-2"></i> Quản lý Đánh giá
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.contacts.index') }}"
                            class="nav-link admin-nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                            <i class="bi bi-envelope-paper me-2"></i> Quản lý Liên hệ
                        </a>
                    </li>

                </ul>
            </div>

            <div class="sidebar-footer text-center py-3 small text-white">
                © {{ now()->year }} DATN_FA25_PolyCoach
            </div>
        </nav>

        <!-- MAIN CONTENT -->
        <div class="layout-main flex-grow-1 d-flex flex-column">

            <!-- NAVBAR -->
            <nav class="navbar navbar-expand-lg admin-navbar shadow-sm">
                <div class="container-fluid">

                    <!-- Sidebar Toggle -->
                    <button class="btn btn-sm btn-outline-light me-3" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>

                    <!-- Page Title -->
                    <div class="d-flex flex-column">
                        <span class="admin-title">Quản trị hệ thống PolyCoach</span>
                        <span class="admin-subtitle">@yield('title')</span>
                    </div>

                    <!-- User Dropdown -->
                    <ul class="navbar-nav ms-auto align-items-center">
                        @php $user = Auth::user(); @endphp

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-light"
                                href="#" id="userDropdown" data-bs-toggle="dropdown">

                                {{-- Phần Avatar --}}
                                <div
                                    class="avatar-circle overflow-hidden d-flex align-items-center justify-content-center border border-2 border-primary-subtle">
                                    @if ($user->image)
                                        {{-- Hiển thị ảnh nếu có --}}
                                        <img src="{{ asset($user->image) }}" alt="Avatar"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        {{-- Hiển thị chữ cái đầu nếu không có ảnh --}}
                                        <span
                                            class="fw-bold">{{ strtoupper(mb_substr($user->first_name ?? $user->name, 0, 1)) }}</span>
                                    @endif
                                </div>

                                <div class="d-flex flex-column text-start">
                                    <span class="fw-semibold user-name">
                                        {{ $user->full_name ?? $user->name }}
                                    </span>
                                    <span class="user-role small opacity-75 text-capitalize">
                                        {{ $user->role ?? 'admin' }}
                                    </span>
                                </div>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-person-bounding-box me-2"></i> Hồ sơ cá nhân
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>

                </div>
            </nav>

            <!-- Content -->
            <main class="admin-main p-4 flex-grow-1">
                <div class="admin-main-inner">
                    @yield('content')
                </div>
            </main>

        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.body.classList.toggle('sidebar-collapsed');
        });
    </script>

    @stack('scripts')

</body>

</html>
