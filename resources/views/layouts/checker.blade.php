    <!DOCTYPE html>
    <html lang="vi">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PolyCoach Checker - @yield('title', 'Dashboard')</title>

        <!-- Bootstrap -->
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
                <a class="d-flex flex-column align-items-center text-decoration-none"
                href="{{ route('checker.dashboard') }}">

                    <div class="sidebar-logo-wrapper mb-2">
                        <img src="{{ asset('logoPoLyCoach.png') }}" class="sidebar-logo img-fluid" alt="Logo PolyCoach">
                    </div>

                    <span class="sidebar-brand">PolyCoach Checker</span>
                </a>
            </div>

            <!-- Menu -->
            <div class="sidebar-menu flex-grow-1">
                <ul class="nav flex-column px-2">

                    <li class="nav-item">
                        <a href="{{ route('checker.dashboard') }}"
                        class="nav-link admin-nav-link {{ request()->routeIs('checker.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('checker.tickets.index') }}"
                        class="nav-link admin-nav-link {{ request()->routeIs('checker.tickets.*') ? 'active' : '' }}">
                            <i class="bi bi-ticket-perforated me-2"></i> Kiểm tra vé
                        </a>
                    </li>
                    {{-- kiểm tra chuyến --}}
                    <li>
                        <a href="{{ route('checker.trips.index') }}"
                        class="nav-link admin-nav-link {{ request()->routeIs('checker.trips.*') ? 'active' : '' }}">
                            <i class="bi bi-bus-front-fill me-2"></i> Kiểm tra chuyến
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('checker.verify') }}"
                        class="nav-link admin-nav-link {{ request()->routeIs('checker.verify') ? 'active' : '' }}">
                            <i class="bi bi-upc-scan me-2"></i> Xác minh mã vé
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Footer -->
            <div class="sidebar-footer text-center py-3 small text-muted">
                © {{ now()->year }} PolyCoach
            </div>

        </nav>

        <!-- MAIN -->
        <div class="layout-main flex-grow-1 d-flex flex-column">

            <!-- NAVBAR -->
            <nav class="navbar navbar-expand-lg admin-navbar shadow-sm">
                <div class="container-fluid">

                    <!-- Toggle -->
                    <button id="sidebarToggle" class="btn btn-sm btn-outline-light me-3">
                        <i class="bi bi-list"></i>
                    </button>

                    <div class="d-flex flex-column">
                        <span class="admin-title">Hệ thống kiểm soát vé</span>
                        <span class="admin-subtitle">@yield('title')</span>
                    </div>

                    <!-- User -->
                    <ul class="navbar-nav ms-auto align-items-center">
                        @php $user = Auth::user(); @endphp
                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-light"
                            href="#" id="userDropdown" data-bs-toggle="dropdown">

                                <div class="avatar-circle">
                                    <span>{{ strtoupper(mb_substr($user->first_name ?? $user->name, 0, 1)) }}</span>
                                </div>

                                <div class="d-flex flex-column text-start">
                                    <span class="fw-semibold user-name">
                                        {{ $user->full_name ?? $user->name }}
                                    </span>
                                    <span class="small opacity-75 text-capitalize">checker</span>
                                </div>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-person-circle me-2"></i> Hồ sơ
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider"></li>

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
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.body.classList.toggle('sidebar-collapsed');
        });
    </script>

    @stack('scripts')

    </body>
    </html>
