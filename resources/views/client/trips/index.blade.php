@extends('layouts.client')

@section('content')
    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div class="row g-4">
                {{-- CỘT BÊN TRÁI: BỘ LỌC --}}
                <aside class="col-lg-3">
                    <div class="sticky-top" style="top: 20px; z-index: 10;">
                        @include('client.trips._filter')
                    </div>
                </aside>

                {{-- CỘT BÊN PHẢI: DANH SÁCH CHUYẾN ĐI --}}
                <main class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0">Danh sách chuyến đi</h4>
                        <span class="text-muted">{{ count($trips) }} chuyến được tìm thấy</span>
                    </div>

                    @forelse($trips as $trip)
                        <div class="trip-card mb-4 shadow-sm border-0">
                            <div class="row g-0">
                                {{-- Thông tin chính --}}
                                <div class="col-md-9 p-4">
                                    <div class="d-flex align-items-center gap-2 mb-4">
                                        <span class="badge rounded-pill bg-primary-subtle text-primary px-3">
                                            <i class="bi bi-bus-front me-1"></i> {{ $trip->bus->type ?? 'Xe giường nằm' }}
                                        </span>
                                        <span class="text-muted small"><i class="bi bi-shield-check me-1"></i> Xác nhận tức
                                            thì</span>
                                    </div>

                                    <div class="row align-items-center">
                                        {{-- Điểm đi --}}
                                        <div class="col-auto text-center" style="min-width: 80px;">
                                            <div class="time-val h4 fw-bold mb-0">
                                                {{ \Carbon\Carbon::parse($trip->departure_time)->format('H:i') }}</div>
                                            <div class="location-val text-muted small mt-1">
                                                {{ $trip->route->fromCity->name }}</div>
                                        </div>

                                        {{-- Timeline --}}
                                        <div class="col text-center px-4">
                                            @php
                                                $estimatedTime = optional($trip->route)->estimated_time;
                                                $durationText = $estimatedTime
                                                    ? Carbon\Carbon::parse($estimatedTime)
                                                        ->diff(Carbon\Carbon::today())
                                                        ->format('%h giờ %i phút')
                                                    : '---';
                                            @endphp
                                            <div class="duration-text small text-muted mb-2">{{ $durationText }}</div>
                                            <div class="trip-timeline">
                                                <div class="dot start"></div>
                                                <div class="line"></div>
                                                <div class="dot end"></div>
                                            </div>
                                        </div>

                                        {{-- Điểm đến --}}
                                        <div class="col-auto text-center" style="min-width: 80px;">
                                            <div class="time-val h4 fw-bold mb-0">
                                                {{ \Carbon\Carbon::parse($trip->arrival_time)->format('H:i') }}</div>
                                            <div class="location-val text-muted small mt-1">{{ $trip->route->toCity->name }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Giá và Đặt vé --}}
                                <div
                                    class="col-md-3 p-4 bg-light-subtle border-start d-flex flex-column justify-content-center text-end">
                                    <div class="price-tag text-primary mb-1">
                                        {{ number_format($trip->ticket_price, 0, '.', '.') }}đ</div>
                                    <div class="text-success small fw-bold mb-3">
                                        Còn {{ $trip->availableSeats() }} chỗ trống
                                    </div>
                                    <a href="{{ route('client.trips.show', ['trip_id' => $trip->id]) }}"
                                        class="btn btn-primary fw-bold py-2">
                                        Chọn chuyến
                                    </a>
                                </div>
                            </div>

                            {{-- Footer Action --}}
                            <div class="trip-footer px-4 py-2 bg-white d-flex justify-content-end border-top">
                                <button class="btn btn-link btn-sm text-decoration-none btn-detail-toggle"
                                    data-trip-id="{{ $trip->id }}">
                                    Thông tin chi tiết <i class="bi bi-chevron-down ms-1"></i>
                                </button>
                            </div>

                            {{-- Hidden Panel --}}
                            <div class="detail-panel" id="detail-trip-{{ $trip->id }}" style="display:none;">
                                <div class="bg-white border-top">
                                    {{-- Tab Headers --}}
                                    <ul class="nav nav-tabs custom-detail-tabs px-4" role="tablist">
                                        <li class="nav-item">
                                            <button class="nav-link active" data-tab="dontra-{{ $trip->id }}">Điểm
                                                đón/trả</button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-tab="chinhsach-{{ $trip->id }}">Chính
                                                sách</button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-tab="hinhanh-{{ $trip->id }}">Hình
                                                ảnh</button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-tab="danhgia-{{ $trip->id }}">Đánh
                                                giá</button>
                                        </li>
                                    </ul>

                                    {{-- Tab Content --}}
                                    <div class="tab-content p-4">
                                        {{-- 1. Tab Đón/Trả --}}
                                        <div class="tab-pane-custom active" id="dontra-{{ $trip->id }}">
                                            <div class="row">
                                                <div class="col-md-6 border-end">
                                                    <h6 class="fw-bold mb-3 text-primary"><i
                                                            class="bi bi-geo-alt me-2"></i>Điểm đón</h6>
                                                    @forelse ($trip->route->pickupPoints as $p)
                                                        <div class="d-flex gap-3 mb-2 small">
                                                            <span
                                                                class="fw-bold">{{ \Carbon\Carbon::parse($p->time)->format('H:i') }}</span>
                                                            <span>{{ $p->name }}</span>
                                                        </div>
                                                    @empty
                                                        <p class="text-muted small">Đang cập nhật...</p>
                                                    @endforelse
                                                </div>
                                                <div class="col-md-6 ps-md-4">
                                                    <h6 class="fw-bold mb-3 text-danger"><i class="bi bi-geo me-2"></i>Điểm
                                                        trả</h6>
                                                    @forelse ($trip->route->dropoffPoints as $p)
                                                        <div class="d-flex gap-3 mb-2 small">
                                                            <span
                                                                class="fw-bold">{{ \Carbon\Carbon::parse($p->time)->format('H:i') }}</span>
                                                            <span>{{ $p->name }}</span>
                                                        </div>
                                                    @empty
                                                        <p class="text-muted small">Đang cập nhật...</p>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>

                                        {{-- 2. Tab Chính sách --}}
                                        <div class="tab-pane-custom d-none" id="chinhsach-{{ $trip->id }}">
                                            <h6 class="fw-bold mb-3 text-primary">Chính sách chuyến đi</h6>
                                            <div class="small text-muted">
                                                <p class="mb-2">
                                                    <i class="bi bi-clock-history me-2"></i>
                                                    Quý khách vui lòng có mặt tại bến xuất phát trước ít nhất <strong>30
                                                        phút</strong> so với giờ khởi hành.
                                                </p>
                                                <p class="mb-2">
                                                    <i class="bi bi-telephone-outbound me-2"></i>
                                                    Nếu có nhu cầu trung chuyển, vui lòng liên hệ tổng đài để được tư vấn
                                                    thêm.
                                                </p>
                                                <p class="mb-0">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    Trường hợp thay đổi thông tin hành trình, vui lòng gọi tổng đài để được
                                                    hỗ trợ theo chính sách giá vé tốt nhất.
                                                </p>
                                            </div>
                                        </div>

                                        {{-- 3. Tab Hình ảnh --}}
                                        <div class="tab-pane-custom d-none" id="hinhanh-{{ $trip->id }}">
                                            <h6 class="fw-bold mb-3">Hình ảnh xe & dịch vụ</h6>
                                            @if (isset($trip->bus->images) && count($trip->bus->images) > 0)
                                                <div class="row g-2">
                                                    @foreach ($trip->bus->images as $img)
                                                        <div class="col-md-3">
                                                            <img src="{{ asset($img->image_path) }}"
                                                                class="img-fluid rounded shadow-sm" alt="Bus Image">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-center py-3">
                                                    <i class="bi bi-images text-muted d-block mb-2"></i>
                                                    <span class="text-muted small">Hình ảnh đang được cập nhật...</span>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- 4. Tab Đánh giá --}}
                                        <div class="tab-pane-custom d-none" id="danhgia-{{ $trip->id }}">
                                            <h6 class="fw-bold mb-3">Đánh giá từ hành khách</h6>
                                            @if (isset($trip->reviews) && count($trip->reviews) > 0)
                                                {{-- Loop qua đánh giá ở đây --}}
                                            @else
                                                <div class="text-center py-3">
                                                    <div class="text-warning mb-2">
                                                        <i class="bi bi-star"></i><i class="bi bi-star"></i><i
                                                            class="bi bi-star"></i><i class="bi bi-star"></i><i
                                                            class="bi bi-star"></i>
                                                    </div>
                                                    <span class="text-muted small">Chưa có đánh giá nào cho chuyến đi
                                                        này.</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card border-0 shadow-sm p-5 text-center">
                            <img src="https://cdn-icons-png.flaticon.com/512/6195/6195616.png" width="80"
                                class="mx-auto mb-3 opacity-50">
                            <p class="text-muted">Rất tiếc, chúng tôi không tìm thấy chuyến đi nào phù hợp.</p>
                            <a href="{{ route('client.trips') }}" class="btn btn-outline-primary btn-sm mx-auto">Xóa bộ
                                lọc</a>
                        </div>
                    @endforelse
                </main>
            </div>
        </div>
    </div>

    <style>
        /* Tổng thể */
        body {
            background-color: #f4f7f9;
        }

        .trip-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.2s ease;
        }

        .trip-card:hover {
            transform: translateY(-4px);
        }

        /* Custom Timeline UI */
        .trip-timeline {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 10px 0;
        }

        .trip-timeline .line {
            height: 2px;
            background: #dee2e6;
            flex-grow: 1;
            position: relative;
        }

        .trip-timeline .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #fff;
            z-index: 1;
        }

        .trip-timeline .dot.start {
            border: 2px solid #0d6efd;
        }

        .trip-timeline .dot.end {
            border: 2px solid #dc3545;
        }

        .price-tag {
            font-size: 1.5rem;
            font-weight: 800;
        }

        /* Stick Sidebar cho filter */
        .sticky-top {
            transition: all 0.3s ease;
        }

        /* Tùy chỉnh Tab chi tiết */
        .custom-detail-tabs {
            border-bottom: 1px solid #edf2f7;
        }

        .custom-detail-tabs .nav-link {
            border: none;
            color: #718096;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 15px 20px;
            position: relative;
            background: none;
        }

        .custom-detail-tabs .nav-link.active {
            color: #0d6efd;
            background: none;
        }

        .custom-detail-tabs .nav-link.active::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #0d6efd;
            border-radius: 3px 3px 0 0;
        }

        /* Ẩn hiện nội dung tab */
        .tab-pane-custom.d-none {
            display: none !important;
        }

        .tab-pane-custom.active {
            display: block !important;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Toggle hiển thị nguyên cái Panel chi tiết
            document.querySelectorAll('.btn-detail-toggle').forEach(btn => {
                btn.addEventListener('click', function() {
                    const tripId = this.dataset.tripId;
                    const panel = document.getElementById(`detail-trip-${tripId}`);

                    const isVisible = panel.style.display === 'block';
                    panel.style.display = isVisible ? 'none' : 'block';

                    // Xoay icon mũi tên (nếu có)
                    const icon = this.querySelector('i');
                    if (icon) icon.style.transform = isVisible ? 'rotate(0deg)' : 'rotate(180deg)';
                });
            });

            // 2. Xử lý chuyển đổi giữa các Tab bên trong
            document.querySelectorAll('.custom-detail-tabs .nav-link').forEach(tabBtn => {
                tabBtn.addEventListener('click', function() {
                    const panel = this.closest('.detail-panel');
                    const targetId = this.dataset.tab;

                    // Xóa active ở tất cả các nút tab trong cùng 1 panel
                    panel.querySelectorAll('.nav-link').forEach(b => b.classList.remove('active'));
                    // Thêm active cho nút vừa bấm
                    this.classList.add('active');

                    // Ẩn tất cả các nội dung tab
                    panel.querySelectorAll('.tab-pane-custom').forEach(pane => {
                        pane.classList.add('d-none');
                        pane.classList.remove('active');
                    });

                    // Hiển thị nội dung tab tương ứng
                    const targetPane = document.getElementById(targetId);
                    if (targetPane) {
                        targetPane.classList.remove('d-none');
                        targetPane.classList.add('active');
                    }
                });
            });
        });
    </script>
@endsection
