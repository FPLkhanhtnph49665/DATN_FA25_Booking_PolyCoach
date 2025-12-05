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
                                        // Lấy danh sách mã ghế/số ghế từ collection tickets
                                        $seatCodes = $booking->tickets->pluck('seat_code')->implode(', ');
                                        $total = $booking->total_amount; // Lấy từ booking
                                        $paymentMethod = $booking->payment_method ?? 'Tiền mặt';
                                        // Lấy một vé đại diện để có thông tin đón/trả
                                        $firstTicket = $booking->tickets->first();

                                        // --- CHỈNH SỬA LOGIC DỮ LIỆU ---
                                        // Cần đảm bảo Model Booking có quan hệ với Trip, và Booking có cột total_price, status

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
                                                {{ \Carbon\Carbon::parse($trip->depature_date)->format('d/m/Y') }}
                                                <br>
                                                <span class="text-muted small">
                                                    {{ $trip->depature_time }}
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
                                            {!! $booking->trang_thai_label ?? ucfirst($booking->status) !!}
                                        </td>

                                        {{-- CỘT HÀNH ĐỘNG MỚI --}}
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-view-detail"
                                                data-bs-toggle="modal" data-bs-target="#bookingDetailModal"
                                                {{-- Truyền DỮ LIỆU BOOKING VÀ TICKETs VÀO data attribute --}} data-id="#{{ $booking->id }}"
                                                data-route="{{ $route->fromCity->name }} → {{ $route->toCity->name }}"
                                                data-time="{{ \Carbon\Carbon::parse($trip->depature_date)->format('d/m/Y') }} - {{ $trip->depature_time }}"
                                                data-bus="{{ $trip->bus->plate_number ?? 'Đang cập nhật' }}"
                                                data-seats="{{ $seatCodes }}" {{-- Lấy điểm đón/trả từ vé đầu tiên hoặc từ trip/route --}}
                                                data-pickup="{{ $firstTicket->pickup_point->dia_chi ?? $route->fromCity->name }}"
                                                data-dropoff="{{ $firstTicket->dropoff_point->dia_chi ?? $route->toCity->name }}"
                                                data-total="{{ number_format($total, 0, ',', '.') }}đ"
                                                data-payment="{{ $paymentMethod }}"
                                                data-status="{{ $booking->trang_thai_label ?? ucfirst($booking->status) }}"
                                                data-tickets="{{ json_encode($booking->tickets->map(fn($t) => ['id' => $t->id, 'seat_code' => $t->seat_code, 'price' => number_format($t->price, 0, ',', '.') . 'đ'])) }}">
                                                <i class="bi bi-eye"></i> Chi tiết
                                            </button>
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
                                    <small class="text-muted">Điểm đón</small>
                                    <div class="fw-semibold" id="modal-pickup"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start">
                                <div class="me-2 text-danger"><i class="bi bi-geo-alt-fill"></i></div>
                                <div>
                                    <small class="text-muted">Điểm trả</small>
                                    <div class="fw-semibold" id="modal-dropoff"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 border-top pt-2"></div>

                        <div class="col-6">
                            <small class="text-muted">Thanh toán</small>
                            <div id="modal-payment"></div>
                        </div>
                        <div class="col-6 text-end">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ... (logic success modal - giữ nguyên) ...

            var bookingModal = document.getElementById('bookingDetailModal'); // Đã đổi ID
            bookingModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;

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
    </script>
@endsection
