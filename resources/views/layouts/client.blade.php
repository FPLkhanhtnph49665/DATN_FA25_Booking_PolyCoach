<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PolyCoach - Đặt vé xe nhanh chóng')</title>

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/client.css') }}">
    <style>
        /* Header nav link hover */

    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    {{-- Header --}}
    <header class="client-header shadow-sm bg-primary">
        <div class="container d-flex justify-content-between align-items-center py-2">
            <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none">
                <img src="{{ asset('PolyCoach.gif') }}" alt="PolyCoach Logo" class="logo-gif me-2" style="height:48px;">
                <h4 class="m-0 fw-bold text-white">PolyCoach</h4>
            </a>

            {{-- Desktop Nav --}}
            <nav class="d-none d-md-flex gap-4">
                <a href="{{ url('/') }}" class="nav-link">Trang chủ</a>
                <a href="{{ url('/tuyen-xe') }}" class="nav-link">Tuyến xe</a>
                <a href="{{ url('/nha-xe') }}" class="nav-link">Nhà xe</a>
                <a href="{{ url('/lien-he') }}" class="nav-link">Liên hệ</a>
                <a href="{{ url('/ve-chung-toi') }}" class="nav-link">Về chúng tôi</a>
            </nav>

            {{-- Login Button --}}
            <div>
                <a href="{{ route('login') }}" class="btn btn-light btn-sm">Đăng nhập</a>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-grow-1 py-4">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="pt-5 pb-3 mt-auto">
        <div class="container">
            <div class="row gy-4">
                <!-- Thông tin công ty -->
                <div class="col-sm-6 col-md-3">
                    <h5 class="fw-bold text-orange mb-3">TRUNG TÂM TỔNG ĐÀI & CSKH</h5>
                    <p class="fs-5 fw-semibold text-orange mb-2">1900 6067</p>
                    <h6 class="fw-bold mb-2">CÔNG TY CỔ PHẦN XE KHÁCH POLYCOACH</h6>
                    <p class="mb-1">Địa chỉ: Trịnh Văn Bô, Hà Nội, Việt Nam.</p>
                    <p class="mb-1">Email: <a href="mailto:hotro@polycoach.vn">hotro@polycoach.vn</a></p>
                    <p>Điện thoại: 02838386852 | Fax: 02838386853</p>
                </div>

                <!-- PolyCoach Links -->
                <div class="col-sm-6 col-md-3">
                    <h5 class="fw-bold mb-3">POLYCOACH</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Về chúng tôi</a></li>
                        <li><a href="#">Lịch trình</a></li>
                        <li><a href="#">Tuyển dụng</a></li>
                        <li><a href="#">Tin tức & Sự kiện</a></li>
                        <li><a href="#">Mạng lưới văn phòng</a></li>
                    </ul>
                </div>

                <!-- Hỗ trợ -->
                <div class="col-sm-6 col-md-3">
                    <h5 class="fw-bold mb-3">Hỗ trợ</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Tra cứu thông tin đặt vé</a></li>
                        <li><a href="#">Điều khoản sử dụng</a></li>
                        <li><a href="#">Câu hỏi thường gặp</a></li>
                        <li><a href="#">Hướng dẫn đặt vé trên Web</a></li>
                        <li><a href="#">Hướng dẫn nạp tiền trên App</a></li>
                    </ul>
                </div>

                <!-- App + Mạng xã hội -->
                <div class="col-sm-6 col-md-3">
                    <h5 class="fw-bold mb-3">TẢI APP POLYCOACH</h5>
                    <div class="d-flex gap-2 mb-3">
                        <a href="#"><img src="https://img.icons8.com/color/48/android-play.png" alt="CH Play"
                                class="img-fluid" style="height:48px;"></a>
                        <a href="#"><img src="https://img.icons8.com/ios-filled/48/apple-logo.png" alt="App Store"
                                class="img-fluid" style="height:48px;"></a>
                    </div>
                    <h5 class="fw-bold mb-2">KẾT NỐI CHÚNG TÔI</h5>
                    <div class="d-flex gap-3">
                        <a href="#"><img src="https://img.icons8.com/color/48/facebook.png" alt="Facebook"
                                class="img-fluid" style="height:32px;"></a>
                        <a href="#"><img src="https://img.icons8.com/color/48/youtube-play.png" alt="YouTube"
                                class="img-fluid" style="height:32px;"></a>
                    </div>
                </div>
            </div>

            <!-- Logo thương hiệu phụ -->
            <div class="d-flex flex-wrap justify-content-center align-items-center gap-4 mt-4">
                <img src="/images/futa-bus-lines.png" alt="PolyCoach Bus Lines" class="img-fluid" style="height:48px;">
                <img src="/images/futa-express.png" alt="PolyCoach Express" class="img-fluid" style="height:48px;">
                <img src="/images/futa-advertising.png" alt="PolyCoach Advertising" class="img-fluid"
                    style="height:48px;">
                <img src="/images/phuc-loc-rest-stop.png" alt="Phúc Lộc Rest Stop" class="img-fluid" style="height:48px;">
            </div>

            <!-- Bản quyền -->
            <div class="text-center text-muted small mt-3">
                © 2025 | Bản quyền thuộc về Công ty Cổ Phần Xe Khách PolyCoach
            </div>
        </div>
    </footer>

    {{-- Script --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
