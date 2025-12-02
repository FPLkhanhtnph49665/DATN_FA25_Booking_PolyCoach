@extends('layouts.client')

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
        $date = \Carbon\Carbon::parse($trip->ngay_khoi_hanh);
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
                        {{ $route->diem_di ?? '—' }} - {{ $route->diem_den ?? '—' }}
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
                                            // mỗi mảng là 1 cột ghế (trên xuống)
                                            $floor1Columns = [
                                                ['A01', 'A02', 'A03', 'A04'],
                                                ['A05', 'A06', 'A07', 'A08'],
                                                ['A09', 'A10', 'A11', 'A12'],
                                                ['A13', 'A14', 'A15', 'A16'],
                                            ];
                                            $bookedSeats = []; // TODO: sau này lấy từ DB
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
                                        ĐIỂM ĐÓN <span class="text-danger">*</span>
                                    </div>
                                    <div class="mb-2 small">
                                        <span class="me-3">
                                            <input type="radio" name="pickup_type" value="ben" checked>
                                            Bến xe/VP
                                        </span>
                                    </div>

                                    <select name="pickup_point_id" id="pickup-select" class="form-select mb-2">
                                        <option value="">-- điểm đón tại {{ $route->diem_di }} --</option>

                                        @if ($route->pickupPoints && $route->pickupPoints->count() > 0)
                                            @foreach ($route->pickupPoints as $point)
                                                <option value="{{ $point->id }}">
                                                    {{ $point->ten_diem_don }} - {{ $point->dia_chi }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="0">{{ $route->diem_di }}</option>
                                        @endif
                                    </select>

                                    <div class="small text-danger">
                                        Vui lòng có mặt tại bến/VP trước giờ xuất bến để kiểm tra thông tin hoặc trung
                                        chuyển.
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="small fw-semibold mb-1">
                                        ĐIỂM TRẢ <span class="text-danger">*</span>
                                    </div>
                                    <div class="mb-2 small">
                                        <span class="me-3">
                                            <input type="radio" name="drop_type" value="ben" checked>
                                            Bến xe/VP
                                        </span>
                                    </div>

                                    <select name="dropoff_point_id" id="dropoff-select" class="form-select mb-2">
                                        <option value="">-- điểm trả tại {{ $route->diem_den }} --</option>

                                        @if ($route->dropoffPoints && $route->dropoffPoints->count() > 0)
                                            @foreach ($route->dropoffPoints as $point)
                                                <option value="{{ $point->id }}">
                                                    {{ $point->ten_diem_tra }} - {{ $point->dia_chi }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="0">{{ $route->diem_den }}</option>
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
                        <span>{{ $route->diem_di ?? '' }} - {{ $route->diem_den ?? '' }}</span>
                    </div>
                    <div class="price-row">
                        <span>biển số xe</span>
                        <span>{{ $trip->bus->bien_so ?? '' }}</span>
                    </div>
                    <div class="price-row">
                        <span>Thời gian xuất bến</span>
                        <span>{{ $trip->gio_khoi_hanh }} {{ $date->format('d/m/Y') }}</span>
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
                            {{ number_format($trip->gia_ve, 0, '.', '.') }}đ
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
            // Chuyển price thành 'let' để có thể thay đổi giá trị
            let currentPrice = {{ $trip->gia_ve }};
            const basePrice = {{ $trip->gia_ve }}; // Lưu giá gốc để fallback
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
            const sidebarPricePerSeat = document.getElementById(
                'sidebar-price-per-seat'); // Element hiển thị đơn giá

            // Select Inputs
            const pickupSelect = document.getElementById('pickup-select');
            const dropoffSelect = document.getElementById('dropoff-select');

            // --- 2. HÀM TIỆN ÍCH ---
            function formatMoney(n) {
                return Number(n).toLocaleString('vi-VN') + 'đ'; // Sửa lại cách format cho chuẩn
            }

            // --- 3. HÀM TÍNH TOÁN & CẬP NHẬT UI (SUMMARY) ---
            function updateSummary() {
                // Lấy danh sách ghế đang chọn
                const selected = Array.from(seatsEls)
                    .filter(el => el.classList.contains('selected'))
                    .map(el => el.dataset.code);

                const count = selected.length;

                // Update Input Hidden Form
                if (inputSeatCount) inputSeatCount.value = count;
                if (inputSeatCodes) inputSeatCodes.value = selected.join(',');

                // Enable/Disable Submit Button
                if (btnSubmit) btnSubmit.disabled = count === 0;

                // Update Sidebar Info
                if (sidebarSeatQty) sidebarSeatQty.textContent = count + ' ghế';
                if (sidebarSeatCodes) sidebarSeatCodes.textContent = count ? selected.join(', ') : '—';

                // TÍNH TỔNG TIỀN DỰA TRÊN GIÁ MỚI NHẤT (currentPrice)
                const total = count * currentPrice;
                const totalText = count ? formatMoney(total) : '0đ';

                if (sidebarTripTotal) sidebarTripTotal.textContent = totalText;
                if (sidebarTotalAll) sidebarTotalAll.textContent = totalText;
            }

            // --- 4. SỰ KIỆN CHỌN GHẾ ---
            seatsEls.forEach(el => {
                el.addEventListener('click', function() {
                    if (this.dataset.booked === '1') return;
                    this.classList.toggle('selected');
                    updateSummary();
                });
            });

            // --- 5. LOGIC GỌI API TÍNH GIÁ (MỚI) ---
            function fetchFare() {
                const pickupId = pickupSelect.value;
                const dropoffId = dropoffSelect.value;

                // Nếu chưa chọn cả 2, hoặc chọn về mặc định -> Về giá gốc
                if (!pickupId || !dropoffId) {
                    currentPrice = basePrice;
                    updatePriceUI();
                    updateSummary(); // Tính lại tổng tiền
                    return;
                }

                // Hiệu ứng loading nhẹ (tuỳ chọn)
                if (sidebarPricePerSeat) sidebarPricePerSeat.innerText = 'Đang tính...';

                // Gọi API (Sử dụng đường dẫn API bạn đã tạo)
                // Lưu ý: Đảm bảo route này trả về JSON: { final_price: 150000, note: "..." }
                const url = `/api/get-fare?trip_id=${tripId}&pickup_id=${pickupId}&dropoff_id=${dropoffId}`;

                fetch(url)
                    .then(response => {
                        if (!response.ok) throw new Error('Lỗi mạng');
                        return response.json();
                    })
                    .then(data => {
                        // Cập nhật giá hiện tại từ API trả về
                        currentPrice = Number(data.final_price);

                        // Cập nhật giao diện đơn giá & tổng tiền
                        updatePriceUI();
                        updateSummary();

                        // (Tuỳ chọn) Hiển thị thông báo nếu có thay đổi giá
                        if (currentPrice !== basePrice) {
                            console.log('Đã cập nhật giá mới: ' + data.note);
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi lấy giá:', error);
                        // Nếu lỗi, quay về giá gốc
                        currentPrice = basePrice;
                        updatePriceUI();
                        updateSummary();
                    });
            }

            function updatePriceUI() {
                // Cập nhật hiển thị "Giá vé lượt đi" trên sidebar
                if (sidebarPricePerSeat) {
                    sidebarPricePerSeat.innerText = formatMoney(currentPrice);

                    // Thêm màu sắc nếu giá thay đổi (tuỳ chọn)
                    if (currentPrice !== basePrice) {
                        sidebarPricePerSeat.style.color = '#dc3545'; // Màu đỏ
                        sidebarPricePerSeat.style.fontWeight = 'bold';
                    } else {
                        sidebarPricePerSeat.style.color = ''; // Reset màu
                    }
                }
            }

            // --- 6. LẮNG NGHE SỰ KIỆN THAY ĐỔI ĐIỂM ĐÓN/TRẢ ---
            if (pickupSelect) pickupSelect.addEventListener('change', fetchFare);
            if (dropoffSelect) dropoffSelect.addEventListener('change', fetchFare);

            // Chạy lần đầu
            // --- 7. XỬ LÝ CHỌN PHƯƠNG THỨC THANH TOÁN ---
            const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
            const payOnlineLabel = document.getElementById('pay-online-label');
            const payCashLabel = document.getElementById('pay-cash-label');

            function updatePaymentUI() {
                // Reset active class
                payOnlineLabel.classList.remove('active');
                payCashLabel.classList.remove('active');

                // Check cái nào đang checked
                const selected = document.querySelector('input[name="payment_method"]:checked').value;

                if (selected === 'vnpay') {
                    payOnlineLabel.classList.add('active');
                    btnSubmit.innerHTML = 'Thanh toán ngay'; // Đổi text nút
                } else {
                    payCashLabel.classList.add('active');
                    btnSubmit.innerHTML = 'Hoàn tất đặt vé'; // Đổi text nút
                }
            }

            // Lắng nghe sự kiện change
            paymentRadios.forEach(radio => {
                radio.addEventListener('change', updatePaymentUI);
            });

            // Gọi 1 lần khi load trang để set trạng thái mặc định
            updatePaymentUI();
            updateSummary();
        });
    </script>
@endsection
