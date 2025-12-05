@extends('layouts.client')

@section('content')
    <div class="container my-5">
        <div class="mb-3">
            {{-- Giả định _filter.blade.php đã được cập nhật để sử dụng ID thành phố --}}
            @include('client.trips._filter')
        </div>

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
                                        $durationText = $duration
                                            ->diff(Carbon\Carbon::today())
                                            ->format('%h giờ %i phút');
                                    } else {
                                        $durationText = 'Không rõ';
                                    }
                                @endphp
                                <div class="text-center futa-trip-duration">
                                    <div class="small text-muted">
                                        {{-- SỬA: Sử dụng trường estimated_time của Route --}}
                                        {{ $durationText }}
                                    </div>

                                    <div class="vex-timeline">
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
                            <a href="javascript:void(0)" class="vex-link-dontra" data-trip-id="{{ $trip->id }}">
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

                        <button type="button" class="btn-close-detail" data-trip-id="{{ $trip->id }}">
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
                                            @if ($p->address)
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
                                            @if ($p->address)
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

    <style>
        .vex-trip-card {
            border-radius: 14px;
            border: 1px solid #eaeaea;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            transition: .2s;
        }

        .vex-trip-card:hover {
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.12);
        }

        .vex-header {
            padding: 20px;
            border-bottom: 1px solid #f2f2f2;
        }

        .vex-body {
            padding: 18px 20px 20px;
        }

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
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
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

        .futa-btn-choose:hover {
            background-color: #ff8f26;
            border-color: #ff8f26;
            color: #fff;
        }
    </style>
