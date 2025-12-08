<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Đặt vé xe PolyCoach nhanh chóng, an toàn, giá tốt nhất. Hệ thống xe chất lượng cao, lộ trình phủ khắp Việt Nam.">
    <title>@yield('title', 'PolyCoach - Đặt Vé Xe Chất Lượng Cao')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts: Inter (modern, clean) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/client.css') }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('iconPoLyCoach.png') }}" type="image/png">

    <style>
        :root {
            --primary: #E63946;
            --primary-dark: #D00000;
            --secondary: #1D3557;
            --light: #F1FAEE;
            --gray: #6c757d;
            --success: #06D6A0;
        }

        /* ===== Layout tổng: để footer luôn ở đáy, không dính vào content ===== */
        html,
        body {
            height: 100%;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            color: #2d3748;

            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1 0 auto;
            padding-bottom: 24px;
            /* khoảng cách với footer */
        }

        .main-nav-bar {
            background: #ffffff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .05);
            border-top: 1px solid #f2f2f2;
        }

        .main-nav {
            display: flex;
            align-items: center;
            justify-content: center;
            /* nếu muốn dồn sang phải: flex-end; */
            gap: 40px;
            /* tăng khoảng cách giữa các mục */
            padding: 12px 0;
            /* nav cao hơn một chút */
            margin: 0;
            list-style: none;
        }

        .main-nav-link {
            font-weight: 600;
            /* đậm hơn */
            font-size: 15px;
            /* to hơn xíu, có thể tăng 16 nếu muốn */
            text-transform: uppercase;
            /* CHỮ IN HOA */
            letter-spacing: .06em;
            /* giãn chữ nhẹ cho đẹp */
            color: #444;
            text-decoration: none;
            padding: 6px 0;
            position: relative;
        }

        .main-nav-link:hover {
            color: #ff595e;
        }

        .main-nav-link.active {
            color: #ff595e;
        }

        .main-nav-link.active::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: -4px;
            height: 3px;
            border-radius: 999px;
            background: #ff595e;
        }


        footer.footer {
            flex-shrink: 0;
            margin-top: auto;
        }

        /* ===== Hết phần layout flex ===== */

        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 12px;
            padding: 12px 28px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(230, 57, 70, 0.3);
        }

        .btn-auth-header {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 16px;
            border-radius: 999px;
            background: #ffffff;
            border: 1px solid #dcdcdc;
            font-size: 13px;
            font-weight: 500;
            color: #333;
            text-decoration: none;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.02);
            transition: all .2s ease;
        }

        .btn-auth-icon {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #555;
            font-size: 13px;
        }

        .btn-auth-header:hover {
            border-color: #ff595e;
            box-shadow: 0 0 0 1px rgba(255, 122, 0, 0.35);
            color: #ff595e;
        }

        .btn-auth-header:hover .btn-auth-icon {
            background: #ffe8d1;
            color: #ff595e;
        }


        .hero-section {
            background: linear-gradient(rgba(29, 53, 87, 0.85), rgba(29, 53, 87, 0.9)),
                url('{{ asset('images/hero-bus.jpg') }}') center/cover no-repeat;
            color: white;
            padding: 120px 0 80px;
            position: relative;
        }

        .search-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin-top: -60px;
            position: relative;
            z-index: 10;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 14px 16px;
            border: 1.5px solid #ddd;
            font-size: 1rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(230, 57, 70, 0.2);
        }

        .route-card {
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .route-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: var(--light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .navbar-brand img {
            height: 50px;
            transition: transform 0.3s;
        }

        .navbar-brand:hover img {
            transform: scale(1.1);
        }

        .footer {
            background: var(--secondary);
            color: #ddd;
            padding-top: 4rem;
        }

        .footer a {
            color: #aaa;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer a:hover {
            color: var(--primary);
        }

        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
            transition: all 0.3s;
        }

        .social-btn:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 100px 0 60px;
            }

            .search-card {
                margin-top: -40px;
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- ====== HEADER ====== -->
    <header>
        {{-- TOP BAR --}}
        <div class="bg-white border-bottom">
            <div class="container py-2">
                <div class="row align-items-center">
                    {{-- Trái: ngôn ngữ + tải app --}}
                    <div class="col-4 d-flex align-items-center gap-3">
                        <span class="text-muted small">
                            <i class="fas fa-globe"></i> VI
                        </span>
                        <a href="#" class="text-muted small text-decoration-none">
                            <i class="fas fa-mobile-alt"></i> Tải app
                        </a>
                    </div>

                    {{-- Giữa: logo --}}
                    <div class="col-4 text-center">
                        <a class="navbar-brand m-0" href="{{ route('client.home') }}">
                            <img src="{{ asset('logoPoLyCoach1.png') }}" alt="logoPoLyCoach" style="height:40px;">
                        </a>
                    </div>

                    {{-- Phải: đăng nhập / tài khoản --}}
                    <div class="col-4 text-end">
                        @guest
                            <a href="{{ route('login') }}" class="btn-auth-header">
                                <span class="btn-auth-icon">
                                    <i class="fas fa-user"></i>
                                </span>
                                <span class="btn-auth-text">
                                    Đăng nhập/Đăng ký
                                </span>
                            </a>
                        @endguest

                        @auth
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-outline-danger btn-sm rounded-pill px-3 dropdown-toggle"
                                    type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i>
                                    {{ auth()->user()->full_name ?? auth()->user()->name ?? 'Tài khoản' }}
                                </button>

                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                    {{-- Link quản trị nếu là admin --}}
                                    @if(auth()->user()->role === 'admin' || (auth()->user()->is_admin ?? false))
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                                <i class="fas fa-gauge-high me-1"></i> Quản trị
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                    @endif

                                    {{-- Nếu là nhân viên checker --}}
                                    @if(auth()->user()->role === 'checker')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('checker.dashboard') }}">
                                                <i class="fas fa-qrcode me-1"></i> Checker
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                    @endif

                                    <li>
                                        <a class="dropdown-item" href="{{ route('client.account.show') }}">
                                            <i class="fas fa-user-cog me-1"></i> Thông tin tài khoản
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('client.account.tickets') }}">
                                            <i class="fas fa-clock-rotate-left me-1"></i> Lịch sử đặt vé
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item" href="">
                                            <i class="fas fa-location-dot me-1"></i> Địa chỉ của bạn
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="">
                                            <i class="fas fa-key me-1"></i> Đặt lại mật khẩu
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button class="dropdown-item text-danger" type="submit">
                                                <i class="fas fa-sign-out-alt me-1"></i> Đăng xuất
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>


        <nav class="main-nav-bar">
            <div class="container">
                <ul class="main-nav">
                    <li>
                        <a class="main-nav-link {{ request()->routeIs('client.home') ? 'active' : '' }}"
                            href="{{ route('client.home') }}">
                            Trang chủ
                        </a>
                    </li>
                    <li>
                        <a class="main-nav-link {{ request()->routeIs('client.trips*') ? 'active' : '' }}"
                            href="{{ route('client.trips') }}">
                            Lịch trình
                        </a>
                    </li>
                    <li>
                        <a class="main-nav-link" href="#">
                            Tra cứu vé
                        </a>
                    </li>
                    <li>
                        <a class="main-nav-link" href="#">
                            Tin tức
                        </a>
                    </li>
                    <li>
                        <a class="main-nav-link" href="#">
                            Hóa đơn
                        </a>
                    </li>
                    <li>
                        <a class="main-nav-link {{ request()->routeIs('client.contact.*') ? 'active' : '' }}"
                            href="{{ route('client.contact.show') }}">
                            Liên hệ
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

    </header>

    <!-- Mobile Offcanvas Menu -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu">
        <div class="offcanvas-header">
            {{-- <img src="{{ asset('PolyCoach.gif') }}" alt="Logo" style="height: 40px;"> --}}
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav">
                <li><a class="nav-link" href="{{ route('client.home') }}">Trang chủ</a></li>
                <li><a class="nav-link" href="{{ route('client.trips') }}">Lịch trình</a></li>
                <li><a class="nav-link" href="#">Tra cứu vé</a></li>
                <li><a class="nav-link" href="#">Tin tức</a></li>
                <li><a class="nav-link" href="#">Hóa đơn</a></li>
                <li><a class="nav-link" href="#contact">Liên hệ</a></li>
            </ul>
        </div>
    </div>

    <!-- ====== MAIN CONTENT ====== -->
    <main>
        @yield('content')
    </main>

    <!-- ====== FOOTER ====== -->
    <footer class="footer text-white">
        <div class="container">
            <div class="row gy-4">
                <!-- Contact Info -->
                <div class="col-lg-4">
                    <h5 class="text-warning fw-bold mb-3">TRUNG TÂM HỖ TRỢ</h5>
                    <p class="display-6 fw-bold text-danger mb-2">1900 6067</p>
                    <p class="small mb-1"><strong>CÔNG TY CP XE KHÁCH POLYCOACH</strong></p>
                    <p class="small text-white mb-1">
                        Tòa nhà FPT Polytechnic, Phố Trịnh Văn Bô, Nam Từ Liêm, Hà Nội.
                    </p>
                    <p class="small mb-1">
                        Email:
                        <a href="mailto:support@polycoach.vn" class="text-warning">support@polycoach.vn</a>
                    </p>
                    <div class="mt-3">
                        <a href="#" class="badge bg-light text-dark me-2 p-2 rounded">
                            <i class="fab fa-google-play"></i> CH Play
                        </a>
                        <a href="#" class="badge bg-light text-dark p-2 rounded">
                            <i class="fab fa-app-store-ios"></i> App Store
                        </a>
                    </div>
                </div>

                <!-- Links -->
                <div class="col-lg-2 col-6">
                    <h6 class="text-warning fw-bold mb-3">PolyCoach</h6>
                    <ul class="list-unstyled small">
                        <li><a href="#">Về chúng tôi</a></li>
                        <li><a href="#">Lịch trình</a></li>
                        <li><a href="#">Tuyển dụng</a></li>
                        <li><a href="#">Tin tức</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-6">
                    <h6 class="text-warning fw-bold mb-3">Hỗ trợ</h6>
                    <ul class="list-unstyled small">
                        <li><a href="#">Tra cứu vé</a></li>
                        <li><a href="#">Điều khoản</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Hướng dẫn</a></li>
                    </ul>
                </div>

                <!-- Social & Logo -->
                <div class="col-lg-4 text-lg-end">
                    <h6 class="text-warning fw-bold mb-3">KẾT NỐI</h6>
                    <div class="d-flex gap-2 justify-content-lg-end mb-3">
                        <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-tiktok"></i></a>
                    </div>
                    <img src="{{ asset('logoPoLyCoach.png') }}" alt="Logo" class="img-fluid" style="max-height: 60px;">
                </div>
            </div>

            <hr class="border-secondary my-4">

            <div class="row align-items-center small">
                <div class="col-md-6 text-center text-md-start">
                    © {{ date('Y') }} Tập đoàn PolyCoach. Mọi quyền được bảo lưu.
                </div>
                <div class="col-md-6 text-center text-md-end">
                    Phát triển bởi <strong class="text-warning">DATN_FA25</strong>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Optional: Add smooth scroll -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (!target) return;
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>
