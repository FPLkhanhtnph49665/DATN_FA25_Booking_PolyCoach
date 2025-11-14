@extends('layouts.client')

@section('content')
<div class="container my-5">

    <h4>Danh sách chuyến</h4>
@if($trips->count())
    @foreach($trips as $trip)
        <div class="futa-trip-card mb-3">
            {{-- HEADER: giờ – thời lượng – giờ đến + loại xe, ghế trống, giá --}}
            <div class="futa-trip-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-4">

                    {{-- Giờ đi --}}
                    <div class="text-center">
                        <div class="fw-bold fs-4">{{ $trip->gio_khoi_hanh }}</div>
                        <div class="small text-muted">
                            {{ optional($trip->route)->diem_di }}
                        </div>
                    </div>

                    {{-- Đường kẻ thời lượng --}}
                    <div class="text-center futa-trip-duration">
                        <div class="small text-muted">
                            {{ $trip->thoi_gian_du_kien ?? '4 giờ' }}
                        </div>
                        <div class="futa-dot-line my-1">
                            <span class="dot"></span>
                            <span class="line"></span>
                            <span class="dot"></span>
                        </div>
                        <div class="small text-muted">
                            ({{ optional($trip->route)->ten_tinh ?? 'Dự kiến' }})
                        </div>
                    </div>

                    {{-- Giờ đến --}}
                    <div class="text-center">
                        <div class="fw-bold fs-4">
                            {{ $trip->gio_den ?? '21:30' }}
                        </div>
                        <div class="small text-muted">
                            {{ optional($trip->route)->diem_den }}
                        </div>
                    </div>

                </div>

                {{-- Loại xe – ghế trống – giá --}}
                <div class="text-end">
                    <div class="small text-muted mb-1">
                        {{ $trip->loai_xe ?? 'Limousine' }}
                    </div>
                    <div class="small text-success mb-1">
                        {{ $trip->so_ghe_trong }} chỗ trống
                    </div>
                    <div class="futa-price">
                        {{ number_format($trip->gia_ve, 0, '.', '.') }}đ
                    </div>
                </div>
            </div>

            {{-- BODY: tên tuyến, lưu ý, link phụ + nút Chọn chuyến --}}
            <div class="futa-trip-body">
                <div class="mb-2">
                    <strong>
                        {{ optional($trip->route)->diem_di }}
                        – {{ optional($trip->route)->diem_den }}
                    </strong>
                </div>

                <div class="small text-muted mb-3">
                    Lưu ý: Lý do xe không nhận đón trả tại khu vực cụ thể sẽ hiển thị tại đây.
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="small futa-links">
                        <a href="javascript:void(0)">Ghế ngồi</a>
                        <span> | </span>
                        <a href="javascript:void(0)">Lịch trình</a>
                        <span> | </span>
                        <a href="javascript:void(0)">Trung chuyển</a>
                        <span> | </span>
                        <a href="javascript:void(0)">Chính sách</a>
                    </div>

                    <a href="{{ route('client.trips.show', $trip->id) }}"
                       class="btn btn-warning rounded-pill px-4 fw-semibold futa-btn-choose">
                        Chọn chuyến
                    </a>
                </div>
            </div>
        </div>
    @endforeach
    @else
        <p>Không có chuyến nào.</p>
@endif
</div>
@endsection
<style>
    .futa-trip-card {
        border-radius: 12px;
        background: #fff;
    border: 1px solid #eee;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    overflow: hidden;
}

.futa-trip-header {
    padding: 16px 20px;
    border-bottom: 1px solid #f5f5f5;
}

.futa-trip-body {
    padding: 16px 20px 12px;
}

.futa-trip-duration .futa-dot-line {
    display: flex;
    align-items: center;
    gap: 4px;
}

.futa-trip-duration .dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ff7a00; /* màu cam giống FUTA */
}

.futa-trip-duration .line {
    flex: 1;
    height: 2px;
    background-image: linear-gradient(to right, #ff7a00 33%, rgba(255, 122, 0, 0) 0%);
    background-position: bottom;
    background-size: 8px 2px;
    background-repeat: repeat-x;
}

.futa-price {
    color: #ff0000;
    font-weight: 700;
    font-size: 1.1rem;
}

.futa-links a {
    color: #444;
    text-decoration: none;
}

.futa-links a:hover {
    text-decoration: underline;
}

.futa-btn-choose {
    background-color: #ff7a00;
    border-color: #ff7a00;
    color: #fff;
}

.futa-btn-choose:hover {
    background-color: #ff8f26;
    border-color: #ff8f26;
    color: #fff;
}
</style>

