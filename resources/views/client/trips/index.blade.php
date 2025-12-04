@extends('layouts.client')

@section('content')
    <div class="container my-5">
        <div class="mb-3">
            {{-- Giả định _filter.blade.php đã được cập nhật để sử dụng ID thành phố --}}
            @include('client.trips._filter')
        </div>

        <h4>Danh sách chuyến</h4>
        @if ($trips->count())
            @foreach ($trips as $trip)
                <div class="futa-trip-card mb-3">
                    {{-- HEADER: giờ – thời lượng – giờ đến + loại xe, ghế trống, giá --}}
                    <div class="futa-trip-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-4">

                            {{-- Giờ đi --}}
                            <div class="text-center">
                                <div class="fw-bold fs-4">
                                    {{-- SỬA: Dùng trường departure_time --}}
                                    {{ \Carbon\Carbon::parse($trip->departure_time)->format('H') }} giờ
                                    {{ \Carbon\Carbon::parse($trip->departure_time)->format('i') }} phút
                                </div>
                                <div class="small text-muted">
                                    {{-- SỬA: Lấy tên thành phố từ quan hệ route->fromCity->name --}}
                                    {{ $trip->route->fromCity->name ?? 'Điểm đi' }}
                                </div>
                            </div>

                            {{-- Đường kẻ thời lượng --}}
                            @php
                                // Tính thời gian di chuyển (giả định route->estimated_time là số phút hoặc string 'HH:MM:SS')
                                $estimatedTime = optional($trip->route)->estimated_time;
                                if ($estimatedTime) {
                                    $duration = Carbon\Carbon::parse($estimatedTime);
                                    $durationText = $duration->diff(Carbon\Carbon::today())->format('%h giờ %i phút');
                                } else {
                                    $durationText = 'Không rõ';
                                }
                            @endphp
                            <div class="text-center futa-trip-duration">
                                <div class="small text-muted">
                                    {{-- SỬA: Sử dụng trường estimated_time của Route --}}
                                    {{ $durationText }}
                                </div>
                                <div class="futa-dot-line my-1">
                                    <span class="dot"></span>
                                    <span class="line"></span>
                                    <span class="dot"></span>
                                </div>
                                <div class="small text-muted">
                                    {{-- SỬA: Lấy tên thành phố từ quan hệ route->toCity->name (chỉ hiển thị dự kiến) --}}
                                    ({{ $trip->route->toCity->name ?? 'Dự kiến' }})
                                </div>
                            </div>

                            {{-- Giờ đến --}}
                            <div class="text-center">
                                <div class="fw-bold fs-4">
                                    {{-- SỬA: Dùng trường arrival_time --}}
                                    {{ \Carbon\Carbon::parse($trip->arrival_time)->format('H') }} giờ
                                    {{ \Carbon\Carbon::parse($trip->arrival_time)->format('i') }} phút
                                </div>
                                <div class="small text-muted">
                                    {{-- SỬA: Lấy tên thành phố từ quan hệ route->toCity->name --}}
                                    {{ $trip->route->toCity->name ?? 'Điểm đến' }}
                                </div>
                            </div>

                        </div>

                        {{-- Loại xe – ghế trống – giá --}}
                        <div class="text-end">
                            <div class="small text-muted mb-1">
                                {{-- SỬA: Lấy loại xe từ quan hệ bus->type --}}
                                {{ $trip->bus->type ?? 'Xe giường nằm' }}
                            </div>
                            <div class="small text-success mb-1">
                                {{-- SỬA: Gọi hàm availableSeats() trong model Trip --}}
                                {{ $trip->availableSeats() }} chỗ trống
                            </div>
                            <div class="futa-price">
                                {{-- SỬA: Dùng trường ticket_price --}}
                                {{ number_format($trip->ticket_price, 0, '.', '.') }}đ
                            </div>
                        </div>
                    </div>

                    {{-- BODY: tên tuyến, lưu ý, link phụ + nút Chọn chuyến --}}
                    <div class="futa-trip-body">
                        <div class="mb-2">
                            <strong>
                                {{-- SỬA: Lấy tên thành phố từ quan hệ route->fromCity->name --}}
                                {{ $trip->route->fromCity->name ?? 'Điểm đi' }}
                                – 
                                {{-- SỬA: Lấy tên thành phố từ quan hệ route->toCity->name --}}
                                {{ $trip->route->toCity->name ?? 'Điểm đến' }}
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

                            {{-- Link Chọn chuyến --}}
                            <a href="{{ route('client.trips.show', ['trip_id' => $trip->id]) }}"
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

{{-- ================== STYLE (GIỮ NGUYÊN) ================== --}}
<style>
    .futa-trip-card {
        border-radius: 12px;
        background: #fff;
        border: 1px solid #eee;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
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
        background: #ff7a00;
        /* màu cam giống FUTA */
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