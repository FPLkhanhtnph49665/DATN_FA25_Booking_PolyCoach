{{-- resources/views/client/home.blade.php --}}
@extends('layouts.client')

@section('title', 'Trang chủ')

@section('content')
<div class="container my-4">

    {{-- Banner chính --}}
    @isset($banners)
        <div id="homeCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($banners as $key => $banner)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <img src="{{ $banner->image }}" class="d-block w-100 rounded" alt="{{ $banner->title ?? 'Banner' }}">
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    @endisset

    {{-- Form tìm chuyến xe --}}
    <div class="card p-4 mb-5 border-0 shadow-sm rounded">
        <form action="{{ route('client.searchTrips') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label">Điểm đi</label>
                <input type="text" name="from" class="form-control" placeholder="Chọn điểm đi" value="{{ request('from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Điểm đến</label>
                <input type="text" name="to" class="form-control" placeholder="Chọn điểm đến" value="{{ request('to') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Ngày đi</label>
                <input type="date" name="date" class="form-control" value="{{ request('date', date('Y-m-d')) }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Số vé</label>
                <select name="seats" class="form-select">
                    @for($i=1; $i<=10; $i++)
                        <option value="{{ $i }}" {{ request('seats', 1)==$i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-warning w-100">Tìm chuyến xe</button>
            </div>
        </form>
    </div>

    {{-- Khuyến mãi nổi bật --}}
    <h4 class="mb-3">Khuyến mãi nổi bật</h4>
    <div id="promoCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
        <div class="carousel-inner">
            @isset($promos)
                @foreach($promos as $key => $promo)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <img src="{{ $promo->image }}" class="d-block w-100 rounded" alt="{{ $promo->title ?? 'Promo' }}">
                    </div>
                @endforeach
            @endisset
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    {{-- Tuyến phổ biến --}}
    <h4 class="mb-3">Tuyến phổ biến</h4>
    <div class="row g-3">
        @isset($routes)
            @forelse($routes as $route)
                <div class="col-6 col-md-3">
                    <div class="border rounded p-3 text-center shadow-sm">
                        {{ $route->from }} → {{ $route->to }}
                    </div>
                </div>
            @empty
                <p>Chưa có tuyến phổ biến.</p>
            @endforelse
        @endisset
    </div>

</div>
@endsection
