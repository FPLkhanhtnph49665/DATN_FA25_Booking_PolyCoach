@extends('layouts.client')

@section('content')
    <style>
        body {
            background-color: #f5f5f5;
        }

        .account-hero {
            background: linear-gradient(135deg, #ff6a00 0%, #ff9933 50%, #ff6a00 100%);
            color: #fff;
            padding: 24px 0 70px;
            margin-bottom: -40px;
        }

        .account-hero-title {
            font-size: 22px;
            font-weight: 700;
        }

        .account-wrapper {
            margin-top: 40px;
            margin-bottom: 40px;
        }

        .account-sidebar {
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border: 1px solid #eee;
            padding: 10px 0;
        }

        .account-menu-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            font-size: 14px;
            color: #444;
            text-decoration: none;
        }

        .account-menu-item:hover {
            background: #fff7f0;
        }

        .account-menu-item.active {
            background: #e8f3ff;
            font-weight: 600;
            border-left: 3px solid #1e88e5;
        }

        .account-menu-icon {
            width: 28px;
            height: 28px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #fff;
        }

        .icon-green {
            background: #00b14f;
        }

        .icon-orange {
            background: #ff7a00;
        }

        .icon-blue {
            background: #1e88e5;
        }

        .icon-red {
            background: #f44336;
        }

        .icon-gray {
            background: #9e9e9e;
        }

        .account-main-card {
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border: 1px solid #eee;
            padding: 24px 28px;
        }

        .ticket-filter-row input,
        .ticket-filter-row select {
            font-size: 13px;
            height: 36px;
        }

        .btn-main {
            background-color: #ff7a00;
            border-color: #;
            color: #fff;
            border-radius: 999px;
            padding: 6px 20px;
            font-weight: 600;
        }

        .btn-main:hover {
            background-color: #ff8f26;
            border-color: #ff8f26;
            color: #fff;
        }

        .table-ticket th {
            font-size: 13px;
            background: #fafafa;
        }

        .table-ticket td {
            font-size: 13px;
            vertical-align: middle;
        }

        .no-data {
            text-align: center;
            padding: 40px 0;
            color: #999;
            font-size: 13px;
        }
    </style>

    @php
        /** @var \App\Models\User $user */
        $user = $user ?? auth()->user();
    @endphp
    <div class="container account-wrapper">
        <div class="row">
            {{-- SIDEBAR --}}
            <div class="col-lg-3 mb-3">
                <div class="account-sidebar">
                    <a href="javascript:void(0)" class="account-menu-item">
                        <span class="account-menu-icon icon-green">F</span>
                        <span>PoLyCoachPay</span>
                    </a>
                    <a href="{{ route('client.account.show') }}" class="account-menu-item">
                        <span class="account-menu-icon icon-orange">
                            <i class="bi bi-person"></i>
                        </span>
                        <span>Thông tin tài khoản</span>
                    </a>
                    <a href="{{ route('client.account.tickets') }}" class="account-menu-item active">
                        <span class="account-menu-icon icon-blue">
                            <i class="bi bi-ticket-perforated"></i>
                        </span>
                        <span>Lịch sử mua vé</span>
                    </a>
                    <a href="#" class="account-menu-item">
                        <span class="account-menu-icon icon-blue">
                            <i class="bi bi-geo-alt"></i>
                        </span>
                        <span>Địa chỉ của bạn</span>
                    </a>
                    <a href="{{ route('password.request') }}" class="account-menu-item">
                        <span class="account-menu-icon icon-red">
                            <i class="bi bi-shield-lock"></i>
                        </span>
                        <span>Đặt lại mật khẩu</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="account-menu-item w-100 text-start border-0 bg-transparent">
                            <span class="account-menu-icon icon-gray">
                                <i class="bi bi-box-arrow-right"></i>
                            </span>
                            <span>Đăng xuất</span>
                        </button>
                    </form>
                </div>
            </div>
            {{-- MODAL THÔNG BÁO THÀNH CÔNG --}}
            <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center p-5">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>

                            <h3 class="mt-3 text-success">Đặt vé thành công!</h3>
                            <p class="text-muted">Vé của bạn đã được đặt. Vui lòng kiểm tra email hoặc lịch sử đặt vé.</p>
                            <button type="button" class="btn btn-success mt-3" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- MAIN CONTENT --}}
            <div class="col-lg-9">
                <div class="account-main-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Lịch sử mua vé</h5>
                        <a href="{{ route('client.trips') }}" class="btn btn-main">
                            Đặt vé
                        </a>
                    </div>

                    <p class="small text-muted mb-3">
                        Theo dõi và quản lý quá trình lịch sử mua vé của bạn
                    </p>

                    {{-- FILTER --}}
                    <form method="GET" class="ticket-filter-row mb-3">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-3">
                                <input type="text" name="code" class="form-control" placeholder="Mã vé"
                                    value="{{ request('code') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="route" class="form-control" placeholder="Tuyến đường"
                                    value="{{ request('route') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">Trạng thái</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ thanh
                                        toán</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh
                                        toán</option>
                                    <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Đã hủy
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2 text-end">
                                <button class="btn btn-outline-secondary btn-sm me-1" type="submit">
                                    Tìm
                                </button>
                                <a href="{{ route('client.account.tickets') }}" class="btn btn-outline-secondary btn-sm">
                                    Xóa lọc
                                </a>
                            </div>
                        </div>
                    </form>

                    {{-- TABLE --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-ticket mb-0">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 80px;">Mã Booking</th> {{-- THAY ĐỔI TÊN CỘT --}}
                                    <th style="width: 80px;">Số lượng vé</th> {{-- THAY ĐỔI TÊN CỘT --}}
                                    <th>Tuyến đường</th>
                                    <th style="width: 120px;">Ngày đi</th>
                                    <th style="width: 120px;">Tổng tiền</th> {{-- THAY ĐỔI TÊN CỘT --}}
                                    <th style="width: 120px;">Trạng thái</th>
                                    <th style="width: 100px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                    {{-- THAY ĐỔI VÒNG LẶP SANG $bookings --}}
                                    @php
                                        $trip = $booking->trip;
                                        $route = $trip?->route;
                                        $firstTicket = $booking->tickets->first();

                                        // 1. Lấy điểm đón
                                        // Nếu có giá chặng -> lấy tên điểm đón từ chặng. Nếu không -> lấy tên thành phố đi.
                                        $pickupName =
                                            $firstTicket?->pointFare?->pickupPoint?->address ??
                                            ($route->fromCity->name ?? '—');

                                        // 2. Lấy điểm trả
                                        // Nếu có giá chặng -> lấy tên điểm trả từ chặng. Nếu không -> lấy tên thành phố đến.
                                        $dropoffName =
                                            $firstTicket?->pointFare?->dropoffPoint?->address ??
                                            ($route->toCity->name ?? '—');

                                        $total = $booking->total_amount;
                                        $seatCodes = $booking->tickets->pluck('seat_code')->implode(', ');
                                        $paymentMethod = $booking->payment_method ?? 'Tiền mặt';
                                    @endphp
                                    <tr>
                                        {{-- MÃ BOOKING --}}
                                        <td class="text-center fw-bold">
                                            #{{ $booking->id }}
                                        </td>

                                        {{-- SỐ LƯỢNG VÉ --}}
                                        <td class="text-center">
                                            {{ $booking->tickets->count() }}
                                        </td>

                                        {{-- TUYẾN ĐƯỜNG --}}
                                        <td>
                                            @if ($route)
                                                {{ $route->fromCity->name }} → {{ $route->toCity->name }}
                                            @else
                                                —
                                            @endif
                                        </td>

                                        {{-- NGÀY ĐI --}}
                                        <td class="text-center">
                                            @if ($trip)
                                                {{ \Carbon\Carbon::parse($trip->departure_date)->format('d/m/Y') }}
                                                <br>
                                                <span class="text-muted small">
                                                    {{ \Carbon\Carbon::parse($trip->departure_time)->format('H:i') }}
                                                </span>
                                            @else
                                                —
                                            @endif
                                        </td>


                                        {{-- TỔNG TIỀN --}}
                                        <td class="text-end fw-bold text-danger">
                                            {{ number_format($total, 0, ',', '.') }}đ
                                        </td>

                                        {{-- TRẠNG THÁI (Lấy từ Booking) --}}
                                        <td class="text-center">
                                            @switch($trip->trip_status)
                                                @case(1)
                                                    <span class="badge bg-info-subtle text-info border border-info-subtle">
                                                        <i class="bi bi-clock-history me-1"></i> Chưa xuất phát
                                                    </span>
                                                @break

                                                @case(2)
                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                                        <i class="bi bi-pause-circle me-1"></i> Đã tạm hoãn
                                                    </span>
                                                @break

                                                @case(3)
                                                    <span
                                                        class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                                        <i class="bi bi-bus-front me-1"></i> Đã xuất phát
                                                    </span>
                                                @break

                                                @case(4)
                                                    <span
                                                        class="badge bg-success-subtle text-success border border-success-subtle">
                                                        <i class="bi bi-check-circle-fill me-1"></i> Đã hoàn thành
                                                    </span>
                                                @break

                                                @default
                                                    <span
                                                        class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                                        - Không xác định -
                                                    </span>
                                            @endswitch
                                        </td>

                                        {{-- CỘT HÀNH ĐỘNG MỚI --}}
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-view-detail"
                                                data-bs-toggle="modal" data-bs-target="#bookingDetailModal"
                                                data-id="#{{ $booking->id }}"
                                                data-route="{{ $route->fromCity->name }} → {{ $route->toCity->name }}"
                                                data-time="{{ \Carbon\Carbon::parse($trip->departure_date)->format('d/m/Y') }} - {{ $trip->departure_time }}"
                                                data-bus="{{ $trip->bus->plate_number ?? 'Đang cập nhật' }}"
                                                data-seats="{{ $seatCodes }}" {{-- CẬP NHẬT Ở ĐÂY --}}
                                                data-pickup="{{ $pickupName }}" data-dropoff="{{ $dropoffName }}"
                                                data-total="{{ number_format($total, 0, ',', '.') }}đ"
                                                data-payment="{{ $paymentMethod }}" data-status="{{ $booking->status }}"
                                                data-tickets="{{ json_encode(
                                                    $booking->tickets->map(
                                                        fn($t) => [
                                                            'id' => $t->id,
                                                            'seat_code' => $t->seat_code,
                                                            'price' => number_format($t->price, 0, ',', '.') . 'đ',
                                                        ],
                                                    ),
                                                ) }}">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            {{-- Thay thế đoạn code nút Đánh giá cũ --}}
                                            @if ($booking->trip->trip_status == '4')
                                                @if ($booking->reviews->isNotEmpty())
                                                    {{-- Giả sử bạn đã load relationship review --}}
                                                    <button class="btn btn-sm btn-outline-secondary mt-2" disabled>
                                                        <i class="bi bi-check-all"></i>
                                                    </button>
                                                @else
                                                    <button type="button"
                                                        class="btn btn-sm btn-warning text-white btn-review mt-2"
                                                        data-bs-toggle="modal" data-bs-target="#reviewModal"
                                                        data-trip-id="{{ $booking->trip_id }}"
                                                        data-route-id="{{ $booking->trip->route_id }}"
                                                        data-booking-id="{{ $booking->id }}">
                                                        <i class="bi bi-star-fill"></i>
                                                    </button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">
                                                <div class="no-data">
                                                    <div class="mb-2">🪑</div>
                                                    <div>Hiện chưa có lịch sử mua vé.</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $bookings->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- MODAL CHI TIẾT VÉ --}}
        <div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title">Chi tiết đặt chỗ <span id="modal-booking-id"
                                class="text-primary fw-bold"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <h5 class="fw-bold mb-1" id="modal-route"></h5>
                            <div class="text-muted small" id="modal-time"></div>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <small class="text-muted">Biển số xe</small>
                                <div class="fw-semibold" id="modal-bus"></div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Số ghế / Mã ghế</small>
                                <div class="fw-semibold text-success" id="modal-seats"></div>
                            </div>

                            <div class="col-12 border-top pt-2"></div>

                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <div class="me-2 text-success"><i class="bi bi-geo-alt-fill"></i></div>
                                    <div>
                                        <small class="text-muted">Điểm đón cụ thể</small>
                                        <div class="fw-semibold" id="modal-pickup"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <div class="me-2 text-danger"><i class="bi bi-geo-alt-fill"></i></div>
                                    <div>
                                        <small class="text-muted">Điểm trả cụ thể</small>
                                        <div class="fw-semibold" id="modal-dropoff"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 border-top pt-2"></div>

                            <div class="col-4">
                                <small class="text-muted">Thanh toán</small>
                                <div id="modal-payment"></div>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">Trạng thái thanh toán</small>
                                <div id="modal-payment-status"></div>
                            </div>
                            <div class="col-4 text-end">
                                <small class="text-muted">Tổng tiền</small>
                                <div class="fw-bold text-danger fs-5" id="modal-total"></div>
                            </div>
                        </div>
                        <h6 class="fw-bold mt-4 mb-2">Danh sách vé đã đặt</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th style="width: 50px;">ID Vé</th>
                                        <th style="width: 100px;">Mã ghế</th>
                                        <th style="width: 100px;">Giá/Vé</th>
                                    </tr>
                                </thead>
                                <tbody id="modal-tickets-list">
                                    {{-- Nội dung sẽ được nạp bằng JavaScript --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- MODAL ĐÁNH GIÁ --}}
        <div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('client.reviews.store') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Gửi đánh giá chuyến đi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="trip_id" id="review_trip_id">
                            <input type="hidden" name="route_id" id="review_route_id">
                            <input type="hidden" name="booking_id" id="review_booking_id">

                            <div class="mb-3">
                                <label class="form-label">Số sao (1-5)</label>
                                <select name="rating" class="form-select" required>
                                    <option value="5">5 sao - Rất tốt</option>
                                    <option value="4">4 sao - Tốt</option>
                                    <option value="3">3 sao - Bình thường</option>
                                    <option value="2">2 sao - Tệ</option>
                                    <option value="1">1 sao - Rất tệ</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nội dung đánh giá</label>
                                <textarea name="content" class="form-control" rows="3" placeholder="Chia sẻ cảm nhận của bạn..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-warning text-white">Gửi đánh giá</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1060">
            <div id="reviewToast" class="toast align-items-center text-white bg-success border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <span id="toast-message">Cảm ơn bạn đã đánh giá chuyến đi!</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // ... (logic success modal - giữ nguyên) ...

                var bookingModal = document.getElementById('bookingDetailModal'); // Đã đổi ID
                bookingModal.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
                    var seats = button.getAttribute('data-seats');

                    // Lấy dữ liệu chung
                    var id = button.getAttribute('data-id');
                    var route = button.getAttribute('data-route');
                    var time = button.getAttribute('data-time');
                    var bus = button.getAttribute('data-bus');
                    var pickup = button.getAttribute('data-pickup');
                    var dropoff = button.getAttribute('data-dropoff');
                    var total = button.getAttribute('data-total');
                    var payment = button.getAttribute('data-payment');

                    // Lấy danh sách tickets (JSON string)
                    var ticketsJson = button.getAttribute('data-tickets');
                    var tickets = ticketsJson ? JSON.parse(ticketsJson) : [];

                    // Nạp dữ liệu chung vào Modal
                    document.getElementById('modal-booking-id').textContent = id; // Đã đổi ID
                    document.getElementById('modal-route').textContent = route;
                    document.getElementById('modal-time').textContent = time;
                    document.getElementById('modal-bus').textContent = bus;
                    document.getElementById('modal-pickup').textContent = pickup;
                    document.getElementById('modal-dropoff').textContent = dropoff;
                    document.getElementById('modal-total').textContent = total;
                    document.getElementById('modal-payment').textContent = payment;
                    document.getElementById('modal-payment-status').textContent = button.getAttribute('data-status');
                    document.getElementById('modal-seats').textContent = seats;

                    // Nạp danh sách vé con
                    const ticketsListBody = document.getElementById('modal-tickets-list');
                    ticketsListBody.innerHTML = ''; // Xóa nội dung cũ

                    if (tickets.length > 0) {
                        tickets.forEach(ticket => {
                            const row = ticketsListBody.insertRow();
                            row.className = 'text-center';
                            row.insertCell().textContent = `#${ticket.id}`;
                            row.insertCell().textContent = ticket.seat_code;
                            row.insertCell().textContent = ticket.price;
                        });
                    } else {
                        const row = ticketsListBody.insertRow();
                        const cell = row.insertCell();
                        cell.colSpan = 3;
                        cell.textContent = 'Không tìm thấy thông tin vé chi tiết.';
                        cell.className = 'text-center text-muted';
                    }
                });


                // =======================================================
                // PHẦN SỬA LỖI HIỂN THỊ MODAL THÔNG BÁO THÀNH CÔNG
                // =======================================================

                // 1. Lấy giá trị session, bọc trong '|| false' để tránh lỗi cú pháp JS khi session rỗng
                const successMessage = "{{ session('success') }}" || false;

                if (successMessage && successMessage.trim() === 'Đặt vé thành công!') {
                    const successModalElement = document.getElementById('successModal');

                    // 2. Kiểm tra xem Bootstrap đã được tải chưa và element có tồn tại không
                    if (typeof bootstrap !== 'undefined' && successModalElement) {
                        // Khởi tạo và hiển thị Modal
                        const successModal = new bootstrap.Modal(successModalElement);
                        successModal.show();
                    } else {
                        // Fallback nếu Bootstrap không hoạt động (chỉ để debug)
                        console.error('Bootstrap Modal object not found or successModal element missing.');
                        // alert(successMessage); // Có thể dùng alert tạm thời để kiểm tra session
                    }
                }
            });
            document.addEventListener('DOMContentLoaded', function() {
                const reviewModal = document.getElementById('reviewModal');
                if (reviewModal) {
                    reviewModal.addEventListener('show.bs.modal', function(event) {
                        const button = event.relatedTarget; // Nút vừa được nhấn

                        // Lấy ID từ data-attributes
                        const tripId = button.getAttribute('data-trip-id');
                        const routeId = button.getAttribute('data-route-id');
                        const bookingId = button.getAttribute('data-booking-id');

                        // Gán giá trị vào input ẩn trong modal
                        document.getElementById('review_trip_id').value = tripId;
                        document.getElementById('review_route_id').value = routeId;
                        document.getElementById('review_booking_id').value = bookingId;
                    });
                }
            });
            //đánh giá thành công
            document.addEventListener('DOMContentLoaded', function() {
                // Kiểm tra thông báo từ Laravel Session
                const successMessage = "{{ session('success') }}";

                if (successMessage) {
                    const toastElement = document.getElementById('reviewToast');
                    const toastMessage = document.getElementById('toast-message');

                    if (toastElement) {
                        // Cập nhật nội dung thông báo từ session (nếu có)
                        toastMessage.textContent = successMessage;

                        // Khởi tạo và hiển thị Toast bằng Bootstrap
                        const toast = new bootstrap.Toast(toastElement, {
                            delay: 5000 // Tự động ẩn sau 5 giây
                        });
                        toast.show();
                    }
                }
            });
        </script>
    @endsection
