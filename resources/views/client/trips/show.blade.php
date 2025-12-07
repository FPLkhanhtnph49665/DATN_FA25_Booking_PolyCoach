@extends('layouts.client')
<div class="container mt-3">
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Đã có lỗi xảy ra:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
</div>
@section('content')
    <style>
        body {
            background-color: #f5f5f5;
        }

        /* ===== HEADER CAM ===== */
        .booking-hero {
            background: linear-gradient(135deg, #ff6a00 0%, #ff9933 50%, #ff6a00 100%);
            color: #fff;
            padding: 24px 0 70px;
            margin-bottom: -40px;
        }

        .booking-hero-title {
            font-size: 22px;
            font-weight: 700;
        }

        .booking-hero-sub {
            font-size: 14px;
            opacity: 0.9;
        }

        /* ===== CARD TRẮNG CHÍNH ===== */
        .booking-main-card {
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border: 1px solid #eee;
            padding: 20px 24px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        /* ===== CARD SIDEBAR ===== */
        .booking-side-card {
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border: 1px solid #eee;
            padding: 16px 18px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .booking-side-card h6 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .price-row span:last-child {
            font-weight: 500;
        }

        .price-total {
            font-weight: 700;
            color: #ff0000;
        }

        /* ===== GHẾ: layout tầng dưới / tầng trên + legend ===== */
        .seat-select-wrap {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 32px;
        }

        .seat-floors {
            display: flex;
            gap: 40px;
        }

        .seat-floor-title {
            text-align: center;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .seat-floor-body {
            display: flex;
            gap: 10px;
        }

        .seat-column {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        /* Icon ghế */
        .seat {
            position: relative;
            width: 32px;
            height: 40px;
            border-radius: 8px;
            border: 1px solid #d4d4d4;
            background: #f3f4f6;
            font-size: 11px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            color: #666;
            cursor: pointer;
            user-select: none;
        }

        .seat::before {
            content: '';
            position: absolute;
            top: 3px;
            left: 5px;
            right: 5px;
            height: 9px;
            border-radius: 6px 6px 3px 3px;
            background: #e5e7eb;
        }

        /* TRẠNG THÁI GHẾ */
        .seat.available {
            background: #e6f4ff;
            border-color: #4b9cff;
            color: #2563eb;
        }

        .seat.selected {
            background: #ffe6d8;
            border-color: #ff7a00;
            color: #c2410c;
        }

        .seat.booked {
            background: #e5e7eb;
            border-color: #cbd5e1;
            color: #9ca3af;
            cursor: not-allowed;
        }

        .seat.booked::before {
            background: #d4d4d4;
        }

        /* Legend bên phải */
        .seat-legend-right {
            min-width: 120px;
            font-size: 13px;
        }

        .seat-legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 6px;
        }

        .seat-legend-box {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border: 1px solid #d4d4d4;
        }

        .legend-booked {
            background: #e5e7eb;
        }

        .legend-available {
            background: #e6f4ff;
            border-color: #4b9cff;
        }

        .legend-selected {
            background: #ffe6d8;
            border-color: #ff7a00;
        }

        /* ===== Nút chính ===== */
        .btn-main {
            background-color: #ff7a00;
            border-color: #ff7a00;
            color: #fff;
            border-radius: 999px;
            padding: 8px 24px;
            font-weight: 600;
        }

        .btn-main:hover {
            background-color: #ff8f26;
            border-color: #ff8f26;
            color: #fff;
        }

        /* ===== PHƯƠNG THỨC THANH TOÁN ===== */
        .payment-option {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 12px 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            display: flex;
            align-items: center;
        }

        .payment-option:hover {
            border-color: #ff7a00;
            background-color: #fff8f5;
        }

        /* Khi radio được check, label cha sẽ đổi màu (xử lý bằng JS bên dưới hoặc :has nếu trình duyệt hỗ trợ) */
        .payment-option.active {
            border-color: #ff7a00;
            background-color: #fff8f5;
            box-shadow: 0 0 0 1px #ff7a00;
        }

        .payment-option .form-check-input {
            margin-right: 12px;
            cursor: pointer;
        }

        .payment-icon {
            width: 32px;
            height: 32px;
            object-fit: contain;
            margin-right: 12px;
        }
    </style>
    @php
        $route = $trip->route;
        $date = \Carbon\Carbon::parse($trip->departure_date);
        $weekdayMap = [
            1 => 'Thứ 2',
            2 => 'Thứ 3',
            3 => 'Thứ 4',
            4 => 'Thứ 5',
            5 => 'Thứ 6',
            6 => 'Thứ 7',
            0 => 'Chủ nhật',
        ];
        $weekday = $weekdayMap[$date->dayOfWeek] ?? '';
    @endphp

    {{-- HEADER CAM --}}
    <div class="booking-hero">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="booking-hero-title">
                        {{ $route->fromCity->name ?? '—' }} - {{ $route->toCity->name ?? '—' }}
                    </div>
                    <div class="booking-hero-sub">
                        {{ $weekday }}, {{ $date->format('d/m') }}
                    </div>
                </div>
                <div class="text-end small">
                    <a href="{{ route('client.trips') }}" class="text-white text-decoration-none">
                        ← Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
    {{-- NỘI DUNG CHÍNH --}}
    <div class="container mb-5" style="margin-top: 50px;">
        <div class="row">
            {{-- LEFT: card lớn chọn ghế + form --}}
            <div class="col-lg-8 mb-4">
                <form action="{{ route('client.bookings.store') }}" method="POST" id="booking-form">
                    @csrf
                    <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                    <input type="hidden" name="seats" id="input-seat-count">
                    <input type="hidden" name="seat_codes" id="input-seat-codes">

                    <div class="booking-main-card">

                        {{-- CHỌN GHẾ --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Chọn ghế</h5>
                            <a href="javascript:void(0)" class="small text-primary">Thông tin xe</a>
                        </div>

                        <div class="seat-select-wrap mb-3">
                            {{-- Bên trái: Tầng dưới / Tầng trên --}}
                            <div class="seat-floors">

                                {{-- TẦNG DƯỚI --}}
                                <div class="seat-floor">
                                    <div class="seat-floor-title">Tầng dưới</div>
                                    <div class="seat-floor-body">
                                        @php
                                            // 1. Lấy danh sách các ghế ĐÃ ĐẶT từ bảng tickets
                                            // Quan hệ tickets phải được định nghĩa trong model Trip (hoặc query trực tiếp ở đây nếu lười)
                                            // Logic: Lấy các ticket thuộc trip này, status != cancelled
                                            $bookedSeats = \App\Models\Ticket::where('trip_id', $trip->id)
                                                ->where('status', '!=', 'cancelled')
                                                ->pluck('seat_code')
                                                ->toArray();

                                            // 2. Định nghĩa sơ đồ ghế (Giữ nguyên hoặc lấy từ DB bus_seats nếu muốn dynamic)
                                            $floor1Columns = [
                                                ['A01', 'A02', 'A03', 'A04'],
                                                ['A05', 'A06', 'A07', 'A08'],
                                                ['A09', 'A10', 'A11', 'A12'],
                                                ['A13', 'A14', 'A15', 'A16'],
                                            ];
                                        @endphp

                                        @foreach ($floor1Columns as $column)
                                            <div class="seat-column">
                                                @foreach ($column as $code)
                                                    @php
                                                        $isBooked = in_array($code, $bookedSeats);
                                                    @endphp
                                                    <div class="seat {{ $isBooked ? 'booked' : 'available' }}"
                                                        data-code="{{ $code }}"
                                                        data-booked="{{ $isBooked ? '1' : '0' }}">
                                                        {{ $code }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                {{-- TẦNG TRÊN --}}
                                <div class="seat-floor">
                                    <div class="seat-floor-title">Tầng trên</div>
                                    <div class="seat-floor-body">
                                        @php
                                            $floor2Columns = [
                                                ['B01', 'B02', 'B03', 'B04'],
                                                ['B05', 'B06', 'B07', 'B08'],
                                                ['B09', 'B10', 'B11', 'B12'],
                                                ['B13', 'B14', 'B15', 'B16'],
                                            ];
                                        @endphp

                                        @foreach ($floor2Columns as $column)
                                            <div class="seat-column">
                                                @foreach ($column as $code)
                                                    @php
                                                        $isBooked = in_array($code, $bookedSeats);
                                                    @endphp
                                                    <div class="seat {{ $isBooked ? 'booked' : 'available' }}"
                                                        data-code="{{ $code }}"
                                                        data-booked="{{ $isBooked ? '1' : '0' }}">
                                                        {{ $code }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Bên phải: legend --}}
                            <div class="seat-legend-right">
                                <div class="seat-legend-item">
                                    <span class="seat-legend-box legend-booked"></span>
                                    <span>Đã bán</span>
                                </div>
                                <div class="seat-legend-item">
                                    <span class="seat-legend-box legend-available"></span>
                                    <span>Còn trống</span>
                                </div>
                                <div class="seat-legend-item">
                                    <span class="seat-legend-box legend-selected"></span>
                                    <span>Đang chọn</span>
                                </div>
                            </div>
                        </div>
                        {{-- THÔNG TIN KHÁCH HÀNG --}}
                        @php
                            $user = Auth::user();
                        @endphp

                        <div class="border-top pt-3 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="section-title mb-0">Thông tin khách hàng</div>
                                <span class="text-danger small">ĐIỀU KHOẢN &amp; LƯU Ý</span>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                        <input type="text" name="customer_name" value="{{ $user->full_name ?? '' }}"
                                            class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                        <input type="text" name="customer_phone" value="{{ $user->phone ?? '' }}"
                                            class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="customer_email" value="{{ $user->email ?? '' }}"
                                            class="form-control">
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="agreeTerm" required>
                                        <label class="form-check-label small" for="agreeTerm">
                                            Chấp nhận điều khoản đặt vé &amp; chính sách bảo mật thông tin.
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 small text-danger">
                                    Quý khách vui lòng có mặt tại bến xuất phát trước ít nhất 30 phút
                                    so với giờ khởi hành. <br><br>
                                    Nếu có nhu cầu trung chuyển, vui lòng liên hệ tổng đài để được
                                    tư vấn thêm. <br><br>
                                    Trường hợp thay đổi thông tin hành trình, vui lòng gọi tổng đài
                                    để được hỗ trợ theo chính sách giá vé tốt nhất.
                                </div>
                            </div>
                        </div>

                        {{-- THÔNG TIN ĐÓN TRẢ --}}
                        <div class="border-top pt-3">
                            <div class="section-title">Thông tin đón trả</div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="small fw-semibold mb-1">
                                        ĐIỂM ĐÓN </div>
                                    <div class="mb-2 small">
                                        <span class="me-3">
                                            <input type="radio" name="pickup_type" value="ben" checked> Bến xe/VP
                                        </span>
                                    </div>

                                    <select name="pickup_point_id" id="pickup-select" class="form-select mb-2">
                                        <option value="">-- Mặc định (Tại bến xe) --</option>

                                        @if ($route->pickupPoints && $route->pickupPoints->count() > 0)
                                            @foreach ($route->pickupPoints as $point)
                                                <option value="{{ $point->id }}">
                                                    {{ $point->name }} - {{ $point->address }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="small fw-semibold mb-1">
                                        ĐIỂM TRẢ </div>
                                    <div class="mb-2 small">
                                        <span class="me-3">
                                            <input type="radio" name="drop_type" value="ben" checked> Bến xe/VP
                                        </span>
                                    </div>

                                    <select name="dropoff_point_id" id="dropoff-select" class="form-select mb-2">
                                        <option value="">-- Mặc định (Tại bến xe) --</option>

                                        @if ($route->dropoffPoints && $route->dropoffPoints->count() > 0)
                                            @foreach ($route->dropoffPoints as $point)
                                                <option value="{{ $point->id }}">
                                                    {{ $point->name }} - {{ $point->address }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            {{-- PHƯƠNG THỨC THANH TOÁN --}}
                            <div class="border-top pt-3 mb-4 mt-3">
                                <div class="section-title">Phương thức thanh toán</div>

                                <div class="row g-3">
                                    {{-- Option 1: Thanh toán Online --}}
                                    <div class="col-md-6">
                                        <label class="payment-option active" id="pay-online-label">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                value="vnpay" checked>
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold text-dark">Thanh toán Online (VNPAY)</span>
                                                <span class="small text-muted">Thẻ ATM, Internet Banking, QR Code</span>
                                            </div>
                                        </label>
                                    </div>

                                    {{-- Option 2: Thanh toán Tiền mặt --}}
                                    <div class="col-md-6">
                                        <label class="payment-option" id="pay-cash-label">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                value="cash">
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold text-dark">Thanh toán Tiền mặt</span>
                                                <span class="small text-muted">Thanh toán tại quầy hoặc khi lên xe</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <a href="{{ route('client.trips') }}" class="btn btn-outline-secondary me-2">
                                    Hủy
                                </a>
                                <button type="submit" class="btn btn-main" id="btn-submit" disabled>
                                    Thanh toán
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            {{-- RIGHT: thông tin lượt đi + chi tiết giá --}}
            <div class="col-lg-4">
                <div class="booking-side-card">
                    <h6>Thông tin lượt đi</h6>
                    <div class="price-row">
                        <span>Tuyến xe</span>
                        <span>{{ $route->fromCity->name ?? '' }} - {{ $route->toCity->name ?? '' }}</span>
                    </div>
                    <div class="price-row">
                        <span>biển số xe</span>
                        <span>{{ $trip->bus->plate_number ?? '' }}</span>
                    </div>
                    <div class="price-row">
                        <span>Thời gian xuất bến</span>
                        <span>{{ $trip->departure_time }} {{ $date->format('d/m/Y') }}</span>
                    </div>
                    <div class="price-row">
                        <span>Số lượng ghế</span>
                        <span id="sidebar-seat-qty">0 ghế</span>
                    </div>
                    <div class="price-row">
                        <span>Số ghế</span>
                        <span id="sidebar-seat-codes">—</span>
                    </div>
                    <div class="price-row">
                        <span>Tổng tiền lượt đi</span>
                        <span class="price-total" id="sidebar-trip-total">0đ</span>
                    </div>
                </div>

                <div class="booking-side-card">
                    <h6>Chi tiết giá</h6>
                    <div class="price-row">
                        <span>Giá vé lượt đi</span>
                        <span id="sidebar-price-per-seat">
                            {{ number_format($trip->ticket_price, 0, '.', '.') }}đ
                        </span>
                    </div>
                    <div class="price-row">
                        <span>Phí thanh toán</span>
                        <span>0đ</span>
                    </div>
                    <hr>
                    <div class="price-row">
                        <span class="fw-semibold">Tổng tiền</span>
                        <span class="price-total" id="sidebar-total-all">0đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- 1. KHỞI TẠO BIẾN ---
            let currentPrice = {{ $trip->ticket_price ?? ($trip->price ?? 0) }};
            const basePrice = {{ $trip->ticket_price ?? ($trip->price ?? 0) }};
            const tripId = {{ $trip->id }};

            // Các element DOM
            const seatsEls = document.querySelectorAll('.seat');
            const inputSeatCount = document.getElementById('input-seat-count');
            const inputSeatCodes = document.getElementById('input-seat-codes');
            const btnSubmit = document.getElementById('btn-submit');

            // Sidebar Elements
            const sidebarSeatQty = document.getElementById('sidebar-seat-qty');
            const sidebarSeatCodes = document.getElementById('sidebar-seat-codes');
            const sidebarTripTotal = document.getElementById('sidebar-trip-total');
            const sidebarTotalAll = document.getElementById('sidebar-total-all');
            const sidebarPricePerSeat = document.getElementById('sidebar-price-per-seat');

            // Select Inputs
            const pickupSelect = document.getElementById('pickup-select');
            const dropoffSelect = document.getElementById('dropoff-select');

            // --- 2. HÀM TIỆN ÍCH ---
            function formatMoney(n) {
                return Number(n).toLocaleString('vi-VN') + 'đ';
            }

            // --- 3. HÀM TÍNH TOÁN & CẬP NHẬT UI ---
            function updateSummary() {
                // Lấy danh sách ghế đang chọn (class .selected)
                const selected = Array.from(seatsEls)
                    .filter(el => el.classList.contains('selected'))
                    .map(el => el.dataset.code);

                const count = selected.length;

                // Update Input Hidden Form gửi lên Server
                if (inputSeatCount) inputSeatCount.value = count;
                if (inputSeatCodes) inputSeatCodes.value = selected.join(','); // Gửi chuỗi "A01,A02"

                // Enable/Disable Submit Button
                if (btnSubmit) btnSubmit.disabled = count === 0;

                // Update Sidebar Info
                if (sidebarSeatQty) sidebarSeatQty.textContent = count + ' ghế';
                if (sidebarSeatCodes) sidebarSeatCodes.textContent = count ? selected.join(', ') : '—';

                // Tính tổng tiền
                const total = count * currentPrice;
                const totalText = count ? formatMoney(total) : '0đ';

                if (sidebarTripTotal) sidebarTripTotal.textContent = totalText;
                if (sidebarTotalAll) sidebarTotalAll.textContent = totalText;
            }

            // --- 4. SỰ KIỆN CLICK GHẾ ---
            seatsEls.forEach(el => {
                el.addEventListener('click', function() {
                    // Nếu ghế đã có người đặt (check từ PHP render ra) thì chặn
                    if (this.dataset.booked === '1' || this.classList.contains('booked')) {
                        alert('Ghế này đã có người đặt, vui lòng chọn ghế khác.');
                        return;
                    }
                    this.classList.toggle('selected');
                    updateSummary();
                });
            });

            // --- 5. AJAX LẤY GIÁ VÉ ---
            function fetchFare() {
                const pickupId = pickupSelect.value;
                const dropoffId = dropoffSelect.value;

                // LOGIC MỚI:
                // Nếu 1 trong 2 điểm chưa được chọn (giá trị rỗng) -> Về giá gốc ngay lập tức
                if (!pickupId || !dropoffId) {
                    currentPrice = basePrice; // Về giá gốc

                    updatePriceUI(); // Cập nhật text giá
                    updateSummary(); // Cập nhật tổng tiền

                    // Không cần gọi API nữa vì Controller cũng sẽ trả về giá gốc thôi
                    if (sidebarPricePerSeat) {
                        sidebarPricePerSeat.style.color = ''; // Xóa màu đỏ (nếu có)
                        sidebarPricePerSeat.style.fontWeight = '';
                    }
                    return;
                }

                // Nếu đã chọn cả 2 thì mới gọi API tính giá đặc biệt
                if (sidebarPricePerSeat) sidebarPricePerSeat.innerText = 'Đang tính...';

                const url = `/api/get-fare?trip_id=${tripId}&pickup_id=${pickupId}&dropoff_id=${dropoffId}`;

                // ... (Phần fetch giữ nguyên)

                fetch(url)
                    .then(response => {
                        if (!response.ok) throw new Error('Lỗi kết nối Server');
                        return response.json();
                    })
                    .then(data => {
                        // Cập nhật giá từ API (Lưu ý: Controller trả về key 'price')
                        currentPrice = Number(data.price);
                        updatePriceUI();
                        updateSummary(); // Tính lại tổng tiền với giá mới
                    })
                    .catch(error => {
                        console.error('Lỗi:', error);
                        currentPrice = basePrice; // Fallback về giá gốc nếu lỗi
                        updatePriceUI();
                        updateSummary();
                    });
            }

            function updatePriceUI() {
                if (sidebarPricePerSeat) {
                    sidebarPricePerSeat.innerText = formatMoney(currentPrice);

                    // Đổi màu nếu giá khác giá gốc
                    if (currentPrice !== basePrice) {
                        sidebarPricePerSeat.style.color = '#dc3545';
                        sidebarPricePerSeat.style.fontWeight = 'bold';
                    } else {
                        sidebarPricePerSeat.style.color = '';
                        sidebarPricePerSeat.style.fontWeight = '';
                    }
                }
            }

            // --- 6. GẮN SỰ KIỆN ---
            if (pickupSelect) pickupSelect.addEventListener('change', fetchFare);
            if (dropoffSelect) dropoffSelect.addEventListener('change', fetchFare);

            // Xử lý nút chọn phương thức thanh toán
            const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
            const payOnlineLabel = document.getElementById('pay-online-label');
            const payCashLabel = document.getElementById('pay-cash-label');

            function updatePaymentUI() {
                if (payOnlineLabel) payOnlineLabel.classList.remove('active');
                if (payCashLabel) payCashLabel.classList.remove('active');

                const checkedRadio = document.querySelector('input[name="payment_method"]:checked');
                if (!checkedRadio) return;

                const selected = checkedRadio.value;

                if (selected === 'vnpay') {
                    if (payOnlineLabel) payOnlineLabel.classList.add('active');
                    if (btnSubmit) btnSubmit.innerHTML = 'Thanh toán ngay';
                } else {
                    if (payCashLabel) payCashLabel.classList.add('active');
                    if (btnSubmit) btnSubmit.innerHTML = 'Hoàn tất đặt vé';
                }
            }

            paymentRadios.forEach(radio => {
                radio.addEventListener('change', updatePaymentUI);
            });

            // Khởi chạy lần đầu
            updatePaymentUI();
            updateSummary();
        });
    </script>
@endsection
