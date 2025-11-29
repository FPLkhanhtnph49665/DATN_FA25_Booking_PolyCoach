@extends('layouts.client')

@section('title', 'PolyCoach - Đặt Vé Xe Chất Lượng Cao')

@section('content')
<style>
    /* ===== HERO BANNER ===== */
.hero-banner {
   position: relative;
    max-width: 1128px;         /* giống rendered width của FUTA */
    height: 250px;             /* giống rendered height */
    margin: 32px auto 24px;    /* căn giữa + khoảng cách trên dưới */
    border-radius: 12px;       /* tương đương rounded-xl */
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08); /* card-box-shadown */
}

.hero-banner img {
    position: absolute;        /* như data-nimg="fill" */
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;         /* giống object-cover */
    display: block;
}

/* nếu sau này bạn có text overlay trên banner thì dùng mấy class này,
   hiện tại không dùng cũng không sao */
.hero-content {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: #fff;
    z-index: 2;
    padding: 0 1.5rem;
}

.hero-title {
    font-size: 3.2rem;
    font-weight: 800;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    margin-bottom: 1rem;
}

.hero-subtitle {
    font-size: 1.25rem;
    font-weight: 400;
    opacity: 0.9;
    margin-bottom: 2rem;
}

/* ===== FORM TÌM KIẾM (card bên dưới banner) ===== */
.search-form-container {
    max-width: 1100px;
    margin: -40px auto 40px;    /* kéo card đè lên chân banner, căn giữa */
    background: #ffffff;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 18px 40px rgba(0, 0, 0, 0.12);
    position: relative;
    z-index: 10;
}

/* CHỈ style input trong khối tìm kiếm để không phá toàn site */
.search-form-container .form-control,
.search-form-container .form-select {
    border-radius: 12px;
    padding: 14px 16px;
    border: 1.5px solid #e0e0e0;
    font-size: 1rem;
    transition: all 0.25s;
}

.search-form-container .form-control:focus,
.search-form-container .form-select:focus {
    border-color: #E63946;
    box-shadow: 0 0 0 0.18rem rgba(230, 57, 70, 0.25);
}

/* nút Tìm chuyến */
.search-form-container .btn-search {
    background: #E63946;
    color: #fff;
    border: none;
    border-radius: 999px;
    padding: 14px 30px;
    font-weight: 600;
    font-size: 1.05rem;
    transition: all 0.25s;
    width: 100%;
}

.search-form-container .btn-search:hover {
    background: #D00000;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(230, 57, 70, 0.3);
}

/* ===== CARD KHUYẾN MÃI / TUYẾN HOT ===== */
.promo-card {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    height: 100%;
    background: #fff;
}

.promo-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 14px 32px rgba(0, 0, 0, 0.15);
}

.route-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.25s;
    height: 100%;
    border: 1px solid #f0f0f0;
    position: relative;
}

.route-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 28px rgba(230, 57, 70, 0.18);
    border-color: #E63946;
}

.route-from,
.route-to {
    font-weight: 700;
    font-size: 1.05rem;
    color: #1a1a1a;
}

.route-price {
    font-size: 1.4rem;
    font-weight: 800;
    color: #E63946;
}

.route-time {
    font-size: 0.9rem;
    color: #666666;
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

/* ===== RESPONSIVE ===== */
@media (max-width: 992px) {
    .hero-banner {
        margin-top: 20px;
    }
    .hero-title {
        font-size: 2.4rem;
    }
    .hero-subtitle {
        font-size: 1.05rem;
    }
    .search-form-container {
        margin-top: -30px;
        padding: 1.5rem;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 2rem;
    }
    .hero-subtitle {
        font-size: 0.95rem;
    }
    .search-form-container {
        margin-top: -20px;
        padding: 1.25rem;
    }
}
</style>


<!-- SEARCH FORM -->
{{-- HERO PoLyCoach --}}
<div class="hero-wrapper mb-5">
    {{-- Banner full width, có giới hạn max-width ở giữa --}}
    <div class="hero-banner">
        <img src="{{ asset('banerPoLyCoach.png') }}" alt="PoLyCoach banner">
    </div>
    {{-- Form tìm chuyến, nằm đè xuống dưới banner --}}
    <div class="hero-search container">
        @include('client.trips._search-form')
    </div>
</div>



<!-- KHUYẾN MÃI NỔI BẬT -->
<section class="container my-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="fw-bold text-primary mb-0">Khuyến Mãi Nổi Bật</h4>
        <a href="#" class="text-danger fw-medium small">Xem tất cả →</a>
    </div>

    @isset($promos)
    <div class="row g-4">
        @foreach($promos->take(3) as $promo)
        <div class="col-md-4">
            <div class="promo-card position-relative">
                <img src="{{ $promo->image }}" class="img-fluid" alt="{{ $promo->title }}">
                <div class="p-3">
                    <h6 class="fw-bold">{{ $promo->title }}</h6>
                    <p class="small text-muted">{{ Str::limit($promo->description, 80) }}</p>
                </div>
                @if($loop->first)
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

<!-- TUYẾN PHỔ BIẾN -->
<section class="container my-5 pb-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="fw-bold text-primary mb-0">Tuyến Xe Phổ Biến</h4>
        <a href="{{ route('client.trips') }}" class="text-danger fw-medium small">Xem tất cả →</a>
    </div>

    <div class="row g-4">
        @isset($popularTrips)
            @forelse($popularTrips->take(8) as $trip)
            <div class="col-6 col-md-3">
                <div class="route-card">
                    <div class="route-from">{{ $trip->route->diem_di }}</div>
                    <i class="fas fa-arrow-right text-muted my-1"></i>
                    <div class="route-to">{{ $trip->route->diem_den }}</div>
                    <div class="route-price mt-2">{{ number_format($trip->gia_ve ?? 250000) }}đ</div>
                    <div class="route-time">{{ $trip->gio_khoi_hanh ?? '6h' }}</div>
                    <a href="{{ route('client.searchTrips', ['from' => $trip->route->diem_di, 'to' => $trip->route->diem_den]) }}"
                       class="btn btn-outline-danger btn-sm w-100 mt-3 rounded-pill">
                       Đặt ngay
                    </a>
                </div>
            </div>
            @empty
                <p class="text-muted col-12 text-center">Chưa có tuyến phổ biến.</p>
            @endforelse
        @endisset
    </div>
</section>
@endsection
