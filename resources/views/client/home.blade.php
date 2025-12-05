@extends('layouts.client')

@section('title', 'PolyCoach - Đặt Vé Xe Chất Lượng Cao')

@section('content')
    <style>
        /* ================== HERO BACKGROUND ================== */
        .home-hero {
            background: linear-gradient(180deg, #31a9ff 0%, #7bd5ff 45%, #f5f7fb 100%);
            padding: 32px 0 80px;
            position: relative;
            overflow: hidden;
        }

        /* nếu muốn thêm banner hình vào nền thì dùng background-image */
        .home-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url('{{ asset('banerPoLyCoach.png') }}');
            background-position: center top;
            background-repeat: no-repeat;
            background-size: cover;
            opacity: 0.18;
            /* mờ để vẫn thấy gradient */
            pointer-events: none;
        }

        .home-hero-inner {
            position: relative;
            z-index: 1;
            max-width: 1128px;
            /* giống khung Vexere */
            margin: 0 auto;
        }

        /* ================== HEADLINE ================== */
        .hero-headline {
            text-align: center;
            color: #ffffff;
            margin-bottom: 24px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .hero-title {
            font-size: 2.4rem;
            font-weight: 800;
            margin-bottom: 6px;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .hero-title span {
            font-size: 2.6rem;
            color: #ffd43b;
        }

        .hero-subtitle {
            font-size: 1.05rem;
            font-weight: 500;
            opacity: 0.95;
        }

        /* ================== SEARCH CARD ================== */
        .search-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.16);
            padding: 20px 22px 18px;
        }

        .search-tabs {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
        }

        .search-tab-btn {
            border: none;
            background: transparent;
            border-radius: 999px;
            padding: 8px 16px;
            font-size: 0.95rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #0f3a75;
            cursor: pointer;
            transition: all .2s;
            white-space: nowrap;
        }

        .search-tab-btn i {
            font-size: 1rem;
        }

        .search-tab-btn.active {
            background: #0f3a75;
            color: #ffffff;
        }

        .search-tab-btn:hover:not(.active) {
            background: rgba(15, 58, 117, 0.06);
        }

        /* ép form trong partial nằm ngang giống Vexere */
        .search-card form {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }

        .search-card .search-field {
            flex: 1 1 0;
            min-width: 190px;
            position: relative;
            background: #f8fafc;
            border-radius: 12px;
            padding: 6px 12px;
            border: 1px solid #e2e8f0;
        }

        .search-card .search-field-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 2px;
            display: block;
        }

        .search-card .search-field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(4px);
            font-size: 1rem;
            color: #4f46e5;
        }

        .search-card .search-field input,
        .search-card .search-field select {
            border: none;
            background: transparent;
            padding-left: 26px;
            /* chừa chỗ icon */
            padding-right: 4px;
            width: 100%;
            font-size: 0.95rem;
            outline: none;
        }

        .search-card .btn-search-main {
            border-radius: 999px;
            padding: 14px 28px;
            background: #ffc107;
            border: none;
            font-weight: 700;
            font-size: 1.05rem;
            color: #212529;
            white-space: nowrap;
            flex: 0 0 auto;
            min-width: 140px;
            transition: all .2s;
        }

        .search-card .btn-search-main:hover {
            background: #ffb300;
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(255, 179, 0, 0.4);
        }

        /* ================== BENEFIT BAR ================== */
        .hero-benefits {
            margin-top: 22px;
            background: #0f3a75;
            border-radius: 999px;
            padding: 10px 22px;
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            justify-content: center;
            color: #ffffff;
            font-size: 0.9rem;
            box-shadow: 0 12px 26px rgba(15, 58, 117, 0.35);
        }

        .hero-benefit-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .hero-benefit-item i {
            font-size: 1rem;
        }

        /* ================== PROMO & ROUTE CARD ================== */
        .promo-card {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
            background: #fff;
        }

        .promo-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 32px rgba(0, 0, 0, 0.14);
        }

        .badge-hot {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #ff4757;
            color: #ffffff;
            font-size: 0.8rem;
            padding: 5px 12px;
            border-radius: 50px;
            font-weight: 600;
        }

        .route-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 1.3rem 1rem;
            text-align: center;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.06);
            transition: all 0.25s;
            border: 1px solid #f1f5f9;
            position: relative;
        }

        .route-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 26px rgba(15, 58, 117, 0.18);
            border-color: #0f3a75;
        }

        .route-from,
        .route-to {
            font-weight: 700;
            font-size: 1rem;
            color: #111827;
        }

        .route-price {
            font-size: 1.3rem;
            font-weight: 800;
            color: #e11d48;
        }

        .route-time {
            font-size: 0.85rem;
            color: #6b7280;
        }

        /* ================== RESPONSIVE ================== */
        @media (max-width: 992px) {
            .home-hero {
                padding-bottom: 60px;
            }

            .hero-title {
                font-size: 2.1rem;
            }

            .hero-title span {
                font-size: 2.2rem;
            }

            .search-card form {
                gap: 10px;
            }
        }

        @media (max-width: 768px) {
            .hero-benefits {
                border-radius: 16px;
            }

            .search-card form {
                flex-direction: column;
                align-items: stretch;
            }

            .search-card .btn-search-main {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 1.8rem;
            }

            .hero-title span {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 0.95rem;
            }
        }
    </style>

    {{-- =============== HERO + SEARCH (kiểu Vexere) =============== --}}
    <div class="home-hero">
        <div class="home-hero-inner">
            <div class="hero-headline">
                <div class="hero-badge">
                    <i class="fas fa-bolt"></i>
                    Thứ 3 - Vi vu thả ga
                </div>
                <h1 class="hero-title">
                    Flash Sale giảm đến <span>50%</span>
                </h1>
                <p class="hero-subtitle">
                    PolyCoach – Đặt vé xe khách chất lượng cao, thanh toán tiện lợi, hỗ trợ 24/7
                </p>
            </div>

            {{-- CARD tìm kiếm --}}
            <div class="search-card mb-3">
                <div class="search-tabs">
                    <button type="button" class="search-tab-btn active">
                        <i class="fas fa-bus"></i> Xe khách
                    </button>
                    <button type="button" class="search-tab-btn">
                        <i class="fas fa-van-shuttle"></i> Limousine
                    </button>
                    <button type="button" class="search-tab-btn">
                        <i class="fas fa-car"></i> Thuê xe
                    </button>
                </div>

                {{-- Form thật của bạn --}}
                @include('client.trips._search-form')
                {{-- Gợi ý: trong _search-form bạn bọc từng input vào div.search-field,
                label dùng class search-field-label,
                icon dùng <span class="search-field-icon"><i class="..."></i></span>
                để style ăn theo. --}}
            </div>

            {{-- Thanh benefit giống hàng dưới banner của Vexere --}}
            <div class="hero-benefits">
                <div class="hero-benefit-item">
                    <i class="fas fa-check-circle"></i> Chắc chắn có chỗ
                </div>
                <div class="hero-benefit-item">
                    <i class="fas fa-headset"></i> Hỗ trợ 24/7
                </div>
                <div class="hero-benefit-item">
                    <i class="fas fa-tags"></i> Nhiều ưu đãi
                </div>
                <div class="hero-benefit-item">
                    <i class="fas fa-credit-card"></i> Thanh toán đa dạng
                </div>
            </div>
        </div>
    </div>

    {{-- =============== KHUYẾN MÃI NỔI BẬT =============== --}}
    <section class="container my-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="fw-bold text-primary mb-0">Khuyến mãi nổi bật</h4>
            <a href="#" class="text-danger fw-medium small">Xem tất cả →</a>
        </div>

        @isset($promos)
            <div class="row g-4">
                @foreach ($promos->take(3) as $promo)
                    <div class="col-md-4">
                        <div class="promo-card position-relative">
                            <img src="{{ $promo->image }}" class="img-fluid" alt="{{ $promo->title }}">
                            <div class="p-3">
                                <h6 class="fw-bold mb-1">{{ $promo->title }}</h6>
                                <p class="small text-muted mb-0">{{ Str::limit($promo->description, 80) }}</p>
                            </div>
                            @if ($loop->first)
                                <span class="badge-hot">HOT</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted text-center">Chưa có khuyến mãi nào.</p>
        @endisset
    </section>

    {{-- =============== TUYẾN XE PHỔ BIẾN =============== --}}
    <section class="container my-5 pb-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="fw-bold text-primary mb-0">Tuyến xe phổ biến</h4>
            <a href="{{ route('client.trips') }}" class="text-danger fw-medium small">Xem tất cả →</a>
        </div>

        <div class="row g-4">
            @isset($popularTrips)
                {{-- Sửa: Đảm bảo chỉ lặp qua 8 chuyến (nếu cần, mặc dù query trong Controller đã take(8)) --}}
                @forelse($popularTrips as $trip)
                    <div class="col-6 col-md-3">
                        <div class="route-card">
                            {{-- 1. Điểm đi: Truy cập tên thành phố qua quan hệ Route -> fromCity -> name --}}
                            <div class="route-from">{{ $trip->route->fromCity->name ?? 'Không rõ' }}</div>

                            <i class="fas fa-arrow-right text-muted my-1"></i>

                            {{-- 2. Điểm đến: Truy cập tên thành phố qua quan hệ Route -> toCity -> name --}}
                            <div class="route-to">{{ $trip->route->toCity->name ?? 'Không rõ' }}</div>

                            {{-- 3. Giá vé: Sử dụng trường ticket_price mới và cung cấp giá mặc định nếu cần --}}
                            <div class="route-price mt-2">{{ number_format($trip->ticket_price ?? 250000) }}đ</div>

                            {{-- 4. Giờ khởi hành: Sử dụng trường departure_time mới --}}
                            <div class="route-time">{{ Carbon\Carbon::parse($trip->departure_time)->format('H:i') ?? '6:00' }}
                            </div>

                            {{-- 5. Link Đặt ngay: Truyền ID thành phố và ID thành phố đến qua URL --}}
                            <a href="{{ route('client.searchTrips', [
                                'from_city_id' => $trip->route->from_city_id,
                                'to_city_id' => $trip->route->to_city_id,
                                'departure_date' => \Carbon\Carbon::now()->format('Y-m-d'), // Đặt ngày đi là ngày hiện tại hoặc ngày gần nhất
                            ]) }}"
                                class="btn btn-outline-danger btn-sm w-100 mt-3 rounded-pill">
                                Đặt ngay
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted col-12 text-center">Chưa có chuyến đi phổ biến nào.</p>
                @endforelse
            @endisset
        </div>

    </section>
@endsection
