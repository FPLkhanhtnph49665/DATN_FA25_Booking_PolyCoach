@extends('layouts.client')

@section('content')
<div class="container my-4">

    <h4 class="fw-bold mb-3">Danh sách chuyến đi</h4>

    @forelse($trips as $trip)
        <div class="vex-trip-card mb-3">

            {{-- HEADER --}}
            <div class="vex-header d-flex justify-content-between">

                <div class="d-flex gap-4 align-items-center">

                    {{-- Ảnh xe (1 ảnh mặc định) --}}
                    <img src="{{ $trip->bus->image ?? asset('images/bus-default.jpg') }}" class="vex-bus-img" />

                    <div>

                        {{-- Giờ xuất phát – thời gian – giờ đến --}}
                        <div class="d-flex gap-5 mt-2">

                            {{-- Giờ xuất phát --}}
                            <div class="text-center">
                                <div class="fs-3 fw-bold">{{ $trip->departure_time_formatted }}</div>
                                <div class="text-muted small">{{ $trip->route->fromCity->name }}</div>
                            </div>

                            {{-- Thời gian dự kiến --}}
                            <div class="text-center">
                                <div class="text-muted small mb-1">
                                    {{ $trip->route->estimated_time ?? '4 giờ' }}
                                </div>

                                <div class="vex-timeline">
                                    <span class="dot"></span>
                                    <span class="line"></span>
                                    <span class="dot"></span>
                                </div>

                                <div class="text-muted small mt-1">(Dự kiến)</div>
                            </div>

                            {{-- Giờ đến --}}
                            <div class="text-center">
                                <div class="fs-3 fw-bold">{{ $trip->arrival_time_formatted }}</div>
                                <div class="text-muted small">{{ $trip->route->toCity->name }}</div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Giá + ghế trống --}}
                <div class="text-end">

                    <div class="text-success fw-semibold mb-1">
                        Còn {{ $trip->availableSeats() }} chỗ trống
                    </div>

                    <div class="vex-price">
                        {{ number_format($trip->ticket_price, 0, '.', '.') }}đ
                    </div>

                </div>
            </div>

            {{-- BODY --}}
            <div class="vex-body">

                <div class="fw-semibold mb-2">
                    {{ $trip->route->fromCity->name }} → {{ $trip->route->toCity->name }}
                </div>

                <div class="text-muted small mb-3">
                    Lưu ý: điểm đón và điểm trả sẽ được hiển thị ở phần chi tiết bên dưới.
                </div>

                <div class="d-flex justify-content-between align-items-center">

                    <div class="small vex-links">
                        <a href="#">Sơ đồ ghế</a>
                        <span>|</span>
                        <a href="#">Lịch trình</a>
                        <span>|</span>

                        {{-- MỞ / ĐÓNG KHUNG ĐÓN TRẢ --}}
                        <a href="javascript:void(0)"
                           class="vex-link-dontra"
                           data-trip-id="{{ $trip->id }}">
                            Đón/trả
                        </a>

                        <span>|</span>
                        <a href="#">Chính sách</a>
                    </div>

                    <a href="{{ route('client.trips.show', ['trip_id' => $trip->id]) }}"
                       class="btn vex-btn-choose fw-semibold px-4">
                        Chọn chuyến
                    </a>

                </div>

            </div>

            {{-- KHUNG CHI TIẾT ĐÓN / TRẢ (ẨN MẶC ĐỊNH) --}}
            @php
                $pickupPoints = \App\Models\PickupDropoffPoint::where('route_id', $trip->route->id)
                    ->where('type', 'pickup')
                    ->where('active', 1)
                    ->orderBy('time')
                    ->get();

                $dropoffPoints = \App\Models\PickupDropoffPoint::where('route_id', $trip->route->id)
                    ->where('type', 'dropoff')
                    ->where('active', 1)
                    ->orderBy('time')
                    ->get();
            @endphp

            <div class="vex-detail-panel" id="detail-trip-{{ $trip->id }}" style="display:none;">
                {{-- Tabs đơn giản, chỉ kích hoạt Đón/trả --}}
                <div class="vex-detail-tabs">
                    <button type="button" class="tab active">Đón/trả</button>
                    <button type="button" class="tab" disabled>Đánh giá</button>
                    <button type="button" class="tab" disabled>Chính sách</button>
                    <button type="button" class="tab" disabled>Hình ảnh</button>

                    <button type="button"
                            class="btn-close-detail"
                            data-trip-id="{{ $trip->id }}">
                        ✕
                    </button>
                </div>

                <div class="vex-detail-body">
                    <p class="vex-note">
                        <strong>Lưu ý:</strong> Các mốc thời gian đón, trả bên dưới là thời gian dự kiến.
                        Lịch trình có thể thay đổi tùy tình hình thực tế.
                    </p>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2">Điểm đón</h6>
                            @forelse($pickupPoints as $p)
                                <div class="vex-point-row">
                                    <span class="time">{{ $p->time }}</span>
                                    <span class="dot">•</span>
                                    <span class="text">
                                        {{ $p->name }}
                                        @if($p->address)
                                            – {{ $p->address }}
                                        @endif
                                    </span>
                                </div>
                            @empty
                                <p class="text-muted small mb-0">Chưa có điểm đón.</p>
                            @endforelse
                        </div>

                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2">Điểm trả</h6>
                            @forelse($dropoffPoints as $p)
                                <div class="vex-point-row">
                                    <span class="time">{{ $p->time }}</span>
                                    <span class="dot">•</span>
                                    <span class="text">
                                        {{ $p->name }}
                                        @if($p->address)
                                            – {{ $p->address }}
                                        @endif
                                    </span>
                                </div>
                            @empty
                                <p class="text-muted small mb-0">Chưa có điểm trả.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @empty
        <p class="text-muted">Hiện chưa có chuyến nào.</p>
    @endforelse

    </div>

{{-- CSS --}}
<style>
.vex-trip-card {
    border-radius: 14px;
    border: 1px solid #eaeaea;
    background: #fff;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    transition: .2s;
}
.vex-trip-card:hover {
    box-shadow: 0 5px 18px rgba(0,0,0,0.12);
}

.vex-header { padding: 20px; border-bottom: 1px solid #f2f2f2; }
.vex-body { padding: 18px 20px 20px; }

.vex-bus-img {
    width: 120px;
    height: 80px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid #ddd;
}

/* timeline */
.vex-timeline {
    display: flex;
    align-items: center;
    gap: 6px;
}
.vex-timeline .dot {
    width: 10px;
    height: 10px;
    background: #ff7a00;
    border-radius: 50%;
}
.vex-timeline .line {
    flex: 1;
    height: 2px;
    background-image: linear-gradient(to right, #ff7a00 40%, transparent 0%);
    background-size: 10px 2px;
    background-repeat: repeat-x;
}

.vex-price {
    color: #d0011b;
    font-size: 1.4rem;
    font-weight: 700;
}

/* Links */
.vex-links a {
    color: #444;
    text-decoration: none;
    margin: 0 4px;
}
.vex-links a:hover {
    color: #007bff;
}

/* Button */
.vex-btn-choose {
    background: #ffd400;
    border-radius: 8px;
}
.vex-btn-choose:hover {
    background: #ffca00;
}

/* --- PANEL CHI TIẾT ĐÓN/TRẢ --- */
.vex-detail-panel {
    margin: 0 20px 16px 20px;
    border-radius: 0 0 14px 14px;
    border-top: 1px solid #f2f2f2;
    background: #fff;
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}

.vex-detail-tabs {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-bottom: 1px solid #f2f2f2;
}

.vex-detail-tabs .tab {
    border: none;
    background: transparent;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 0.9rem;
    cursor: default;
}
.vex-detail-tabs .tab.active {
    background: #e6f0ff;
    color: #0052cc;
    cursor: default;
}
.vex-detail-tabs .tab[disabled] {
    color: #999;
}

.btn-close-detail {
    margin-left: auto;
    border: none;
    background: transparent;
    font-size: 1rem;
    cursor: pointer;
    color: #999;
}
.btn-close-detail:hover {
    color: #333;
}

.vex-detail-body {
    padding: 16px 22px 18px;
}

.vex-note {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 14px;
}

.vex-point-row {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-bottom: 6px;
    font-size: 0.9rem;
}
.vex-point-row .time {
    width: 60px;
    font-weight: 600;
}
.vex-point-row .dot {
    margin-top: 2px;
}
.vex-point-row .text {
    flex: 1;
}
</style>

{{-- JS SIMPLE TOGGLE --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const links = document.querySelectorAll('.vex-link-dontra');
    const panels = document.querySelectorAll('.vex-detail-panel');

    function closeAll() {
        panels.forEach(p => p.style.display = 'none');
    }

    links.forEach(link => {
        link.addEventListener('click', function () {
            const id = this.dataset.tripId;
            const panel = document.getElementById('detail-trip-' + id);

            if (!panel) return;

            const isVisible = panel.style.display === 'block';

            closeAll();
            panel.style.display = isVisible ? 'none' : 'block';
        });
    });

    // nút X đóng panel
    document.querySelectorAll('.btn-close-detail').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.tripId;
            const panel = document.getElementById('detail-trip-' + id);
            if (panel) panel.style.display = 'none';
        });
    });
});
</script>
@endsection
