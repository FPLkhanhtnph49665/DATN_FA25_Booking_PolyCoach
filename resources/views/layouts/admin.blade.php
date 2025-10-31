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
    {{-- <link rel="icon" href="{{ asset('testLogo.png') }}" type="image/png"> --}}
    @stack('styles')
</head>

<body>
    <div id="admin-wrapper" class="d-flex min-vh-100">

        <!-- Sidebar -->
        <nav id="sidebar">
            <h3 class="text-center">Quản Trị Viên</h3>
            {{-- <img src="{{ asset('testLogo.png') }}" alt="Logo PolyCoach" class="img-fluid"> --}}

            <ul class="nav flex-column mt-4">
                <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a></li>
                <li class="nav-item"><a href="{{ route("admin.users.index") }}" class="nav-link">Quản lý Users</a></li>
                <li class="nav-item"><a href="{{ route('admin.bus-routes.index') }}" class="nav-link">Quản lý Tuyến</a>
                </li>
                <li class="nav-item"><a href="{{ route('admin.buses.index') }}" class="nav-link">Quản lý Xe</a></li>
                <li class="nav-item"><a href="{{ route('admin.trips.index') }}" class="nav-link">Quản lý Chuyến</a></li>
                <li class="nav-item"><a href="{{ route('admin.tickets.index') }}" class="nav-link">Quản lý Vé</a></li>
                <li class="nav-item"><a href="{{ route('admin.payments.index') }}" class="nav-link">Quản lý Thanh
                        toán</a></li>
                <li class="nav-item"><a href="{{ route('admin.passengers.index') }}" class="nav-link">Quản lý Hành
                        khách</a></li>
                <li class="nav-item"><a href="{{ route('admin.reviews.index') }}" class="nav-link">Quản lý Đánh giá</a>
                </li>
                <li class="nav-item"><a href="{{ route('admin.contacts.index') }}" class="nav-link">Quản lý Liên hệ</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div class="flex-grow-1 d-flex flex-column">

            <!-- Header -->
            <nav class="navbar navbar-expand navbar-light bg-light shadow-sm">
                <div class="container-fluid">

                    <!-- Toggle Sidebar -->
                    <button class="btn btn-sm btn-outline-secondary me-3" id="sidebarToggle">☰</button>

                    <!-- Title -->
                    <span class="navbar-text fw-bold fs-2 me-auto">Quản trị hệ thống PolyCoach</span>
                    {{-- <span class="navbar-brand mb-0 h1">@yield('title', 'Dashboard')</span> --}}

                    <!-- User Info + Logout -->
                    <ul class="navbar-nav ms-auto align-items-center">
                        <!-- Chào người dùng -->
                        <li class="nav-item me-3">
                            <span class="nav-link">Chào, {{ Auth::user()->full_name }}</span>
                        </li>

                        <!-- Dropdown hồ sơ + đăng xuất -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i> Tài khoản
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-gear"></i> Hồ sơ
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>

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
