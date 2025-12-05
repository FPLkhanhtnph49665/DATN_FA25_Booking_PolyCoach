@extends('layouts.client')

@section('content')
    <div class="container my-5">
        <div class="mb-3">
            @include('client.trips._filter')
        </div>

        <h4 class="fw-bold mb-3">Danh sách chuyến đi</h4>

        @forelse($trips as $trip)
            <div class="vex-trip-card mb-3">

                {{-- HEADER (Thông tin cốt lõi: Xe, Giờ, Giá) --}}
                <div class="vex-header d-flex justify-content-between align-items-start">

                    <div class="d-flex gap-4 align-items-center">

                        {{-- Ảnh xe --}}
                        {{-- <img src="{{ $trip->bus->image ?? asset('images/bus-default.jpg') }}" class="vex-bus-img" alt="Hình ảnh xe" /> --}}

                        <div>
                            {{-- Tên tuyến và Loại xe --}}
                            <div class="fw-bold mb-1">
                                {{ $trip->route->fromCity->name ?? 'Điểm đi' }} – {{ $trip->route->toCity->name ?? 'Điểm đến' }}
                            </div>
                            <div class="small text-muted mb-2">
                                {{ $trip->bus->type ?? 'Xe giường nằm' }}
                            </div>

                            {{-- Giờ xuất phát – thời gian – giờ đến (GIỮ NGUYÊN LOGIC CỦA BẠN) --}}
                            <div class="d-flex gap-4 align-items-center">

                                {{-- Giờ xuất phát --}}
                                <div class="text-center">
                                    <div class="fw-bold fs-4">
                                        {{ \Carbon\Carbon::parse($trip->departure_time)->format('H') }} giờ
                                        {{ \Carbon\Carbon::parse($trip->departure_time)->format('i') }} phút
                                    </div>
                                    <div class="small text-muted">
                                        {{ $trip->route->fromCity->name ?? 'Điểm đi' }}
                                    </div>
                                </div>

                                {{-- Đường kẻ thời lượng (GIỮ NGUYÊN LOGIC CỦA BẠN) --}}
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
                                        {{ $durationText }}
                                    </div>

                                    <div class="vex-timeline">
                                        <span class="dot"></span>
                                        <span class="line"></span>
                                        <span class="dot"></span>
                                    </div>
                                    <div class="small text-muted">
                                        ({{ $trip->route->toCity->name ?? 'Dự kiến' }})
                                    </div>
                                </div>

                                {{-- Giờ đến --}}
                                <div class="text-center">
                                    <div class="fw-bold fs-4">
                                        {{ \Carbon\Carbon::parse($trip->arrival_time)->format('H') }} giờ
                                        {{ \Carbon\Carbon::parse($trip->arrival_time)->format('i') }} phút
                                    </div>
                                    <div class="small text-muted">
                                        {{ $trip->route->toCity->name ?? 'Điểm đến' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Giá + Ghế trống + Nút Chọn chuyến --}}
                    <div class="text-end d-flex flex-column align-items-end">
                        <div class="vex-price mb-1">
                            {{ number_format($trip->ticket_price, 0, '.', '.') }}đ
                        </div>
                        <div class="text-success fw-semibold small mb-3">
                            Còn {{ $trip->availableSeats() }} chỗ trống
                        </div>

                        {{-- Nút Chọn chuyến (Chỉ còn một nút duy nhất) --}}
                        <a href="{{ route('client.trips.show', ['trip_id' => $trip->id]) }}"
                            class="btn vex-btn-choose fw-semibold px-4">
                            Chọn chuyến
                        </a>
                    </div>
                </div>

                {{-- BODY (Chứa các link phụ trợ) --}}
                <div class="vex-body">
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
                    </div>
                </div>

                {{-- KHUNG CHI TIẾT ĐÓN / TRẢ (ẨN MẶC ĐỊNH - GIỮ NGUYÊN LOGIC CỦA BẠN) --}}
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
                                        <span class="time">{{ \Carbon\Carbon::parse($p->time)->format('H:i') }}</span>
                                        <span class="dot">•</span>
                                        <span class="text">
                                            {{ $p->name }}
                                            @if ($p->address)
                                                – <span class="text-muted">{{ $p->address }}</span>
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
                                        <span class="time">{{ \Carbon\Carbon::parse($p->time)->format('H:i') }}</span>
                                        <span class="dot">•</span>
                                        <span class="text">
                                            {{ $p->name }}
                                            @if ($p->address)
                                                – <span class="text-muted">{{ $p->address }}</span>
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

    {{-- CSS BỔ SUNG CHO KHUNG CHI TIẾT --}}
    <style>
        /* Các CSS đã cung cấp */
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
            border: none;
            color: #000;
        }

        .vex-btn-choose:hover {
            background: #ffca00;
        }

        /* --- PANEL CHI TIẾT ĐÓN/TRẢ --- */
        .vex-detail-panel {
            margin: 0 20px 16px 20px;
            border-radius: 0 0 14px 14px;
            border: 1px solid #f2f2f2; /* Thêm border để không bị lỗi layout khi mở */
            border-top: none;
            background: #fff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .vex-detail-tabs {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-bottom: 1px solid #f2f2f2;
            position: relative; /* Thêm để nút đóng căn đúng */
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
        
        /* Bổ sung CSS cho khung chi tiết */
        .vex-detail-body {
            padding: 20px 16px;
        }
        .vex-note {
            padding: 10px;
            background: #fff8e1;
            border: 1px solid #ffe082;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        .vex-point-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 8px;
            gap: 8px;
        }
        .vex-point-row .time {
            font-weight: 600;
            color: #000;
            width: 50px; /* Cố định chiều rộng cho giờ */
        }
        .vex-point-row .dot {
            color: #ff7a00;
            font-size: 1.2rem;
            line-height: 1;
        }
        .vex-point-row .text {
            flex: 1;
            font-size: 0.9rem;
        }
        .btn-close-detail {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            font-size: 1.2rem;
            color: #666;
            cursor: pointer;
        }
    </style>
@endsection

{{-- THÊM SCRIPT ĐỂ XỬ LÝ SỰ KIỆN MỞ/ĐÓNG DETAIL PANEL --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleDetail = (tripId, isClosing = false) => {
            const panel = document.getElementById(`detail-trip-${tripId}`);
            const link = document.querySelector(`.vex-link-dontra[data-trip-id="${tripId}"]`);
            
            if (panel && link) {
                if (panel.style.display === 'block' || isClosing) {
                    panel.style.display = 'none';
                    link.textContent = 'Đón/trả';
                } else {
                    // Đóng tất cả panel khác trước khi mở panel mới
                    document.querySelectorAll('.vex-detail-panel').forEach(p => p.style.display = 'none');
                    document.querySelectorAll('.vex-link-dontra').forEach(l => l.textContent = 'Đón/trả');

                    panel.style.display = 'block';
                    link.textContent = 'Đóng chi tiết';
                }
            }
        };

        // Bắt sự kiện click vào link Đón/trả
        document.querySelectorAll('.vex-link-dontra').forEach(link => {
            link.addEventListener('click', function() {
                const tripId = this.getAttribute('data-trip-id');
                toggleDetail(tripId);
            });
        });

        // Bắt sự kiện click vào nút Đóng (✕)
        document.querySelectorAll('.btn-close-detail').forEach(button => {
            button.addEventListener('click', function() {
                const tripId = this.getAttribute('data-trip-id');
                toggleDetail(tripId, true);
            });
        });
    });
</script>
@endpush