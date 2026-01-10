{{-- resources/views/admin/bookings/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý Booking')

@section('content')
    <div class="mb-4">
        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                    <i class="bi bi-ticket-detailed-fill"></i>
                    Danh sách Booking
                </h2>
                <p class="text-white small mb-0">
                    Quản lý các đơn đặt vé: khách hàng, chuyến, ghế, trạng thái và phương thức thanh toán.
                </p>
            </div>
        </div>

        {{-- Thông báo lỗi nhanh --}}
        @if ($errors->any())
            <div class="alert alert-danger py-2 small">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Bộ lọc / tìm kiếm --}}
        <div class="card border-0 mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.bookings.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label for="search" class="form-label text-white small mb-1">Tìm kiếm</label>
                        <input type="text" name="search" id="search" class="form-control"
                            placeholder="Tìm theo tên khách hàng, email hoặc mã chuyến..." value="{{ request('search') }}">
                    </div>

                    {{-- Nếu controller chưa filter theo status / payment_method thì sau này ông có thể bổ sung --}}
                    <div class="col-md-3">
                        <label for="status" class="form-label text-white small mb-1">Trạng thái Booking</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">-- Tất cả --</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xử lý
                            </option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Đã thanh toán
                            </option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                            <i class="bi bi-search"></i>
                            <span>Tìm kiếm</span>
                        </button>
                        <a href="{{ route('admin.bookings.index') }}"
                            class="btn btn-outline-light flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Đặt lại</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Bảng danh sách --}}
        <div class="card border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="text-muted small">ID</th>
                                <th class="text-muted small">Khách hàng</th>
                                <th class="text-muted small">Chuyến</th>
                                <th class="text-muted small">Số ghế</th>
                                <th class="text-muted small">Trạng thái</th>
                                <th class="text-muted small">tổng tiền</th>
                                <th class="text-muted small">Thanh toán</th>
                                <th class="text-muted small">Ngày đặt</th>
                                <th class="text-muted small text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr>
                                    <td class="text-muted small">
                                        #{{ $booking->id }}
                                    </td>

                                    {{-- Khách hàng --}}
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold">
                                                {{ $booking->user->full_name ?? '-' }}
                                            </span>
                                            @if (!empty($booking->user?->email))
                                                <span class="text-muted small">
                                                    {{ $booking->user->email }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Chuyến: ưu tiên hiển thị tuyến + ngày/giờ nếu có quan hệ trip->route --}}
                                    <td>
                                        @php
                                            $trip = $booking->trip ?? null;
                                        @endphp

                                        @if ($trip)
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold">
                                                    {{ $trip->route?->fromCity?->name ?? '-' }}
                                                    <span class="text-muted">→</span>
                                                    {{ $trip->route?->toCity?->name ?? '-' }}
                                                </span>
                                                <span class="text-muted small">
                                                    {{ $trip->departure_date?->format('d/m/Y') ?? '-' }}
                                                    {{ $trip->departure_time_formatted ?? ($trip->departure_time ?? '') }}
                                                </span>
                                                @if (!empty($trip->ma_chuyen))
                                                    <span
                                                        class="badge bg-secondary-subtle text-light border border-primary-subtle mt-1">
                                                        Mã chuyến: {{ $trip->ma_chuyen }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    {{-- Số ghế khách đặt --}}
                                    <td>
                                        {{ $booking->tickets_count ?? 0 }} ghế
                                    </td>

                                    {{-- Trạng thái (status_label accessor đã trả về HTML) --}}
                                    <td>
                                        @if ($booking->status === 'pending')
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                                <i class="bi bi-hourglass-split me-1"></i>
                                                Chờ thanh toán
                                            </span>
                                        @elseif($booking->status === 'paid')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Đã thanh toán
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                                <i class="bi bi-x-circle me-1"></i>
                                                Hủy
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Tổng tiền --}}
                                    <td class="fw-semibold text-success">
                                        {{ number_format($booking->total_amount, 0, ',', '.') }} VND
                                    </td>
                                    {{-- Phương thức thanh toán --}}
                                    <td>
                                        @php
                                            $method = $booking->payment_method ?? '-';
                                            $methodLabel = ucfirst($method);
                                        @endphp

                                        @if ($method !== '-')
                                            <span class="badge bg-info-subtle text-info border border-info-subtle">
                                                {{ $methodLabel }}
                                            </span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>

                                    {{-- Ngày đặt --}}
                                    <td class="text-muted small">
                                        {{ $booking->created_at?->format('d/m/Y H:i') ?? '-' }}
                                    </td>

                                    {{-- Hành động --}}
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                            data-bs-target="#modalBooking{{ $booking->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        Chưa có booking nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Phân trang --}}
                <div class="d-flex justify-content-end mt-3 px-3 pb-3">
                    {{ $bookings->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
    {{-- modal chi tiết booking --}}
    @forelse($bookings as $booking)
        <tr>
        </tr>

        <div class="modal fade" id="modalBooking{{ $booking->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content bg-dark text-white border-secondary shadow-lg">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title fw-bold">Chi tiết đơn đặt vé #{{ $booking->id }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="text-primary fw-bold mb-3">Thông tin người đặt</h6>
                                <div class="ps-2">
                                    <p class="mb-2"><strong>Họ tên:</strong> {{ $booking->user->full_name ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Email:</strong> {{ $booking->user->email ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Điện thoại:</strong> {{ $booking->user->phone ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-primary fw-bold mb-3">Thông tin chuyến</h6>
                                <div class="ps-2">
                                    <p class="mb-2"><strong>Hành trình:</strong>
                                        <span class="text-info">
                                            {{ $booking->trip->route->fromCity->name }} →
                                            {{ $booking->trip->route->toCity->name }}
                                        </span>
                                    </p>
                                    <p class="mb-2"><strong>Khởi hành:</strong>
                                        {{ $booking->trip->departure_date->format('d/m/Y') }} |
                                        {{ $booking->trip->departure_time }}</p>
                                    <p class="mb-2"><strong>Trạng thái:</strong> {!! $booking->status_label !!}</p>
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <h6 class="text-primary fw-bold mb-3">Danh sách ghế đã đặt</h6>

                                <div class="table-responsive">
                                    <table class="table table-sm table-dark table-borderless">
                                        <thead class="border-bottom border-secondary">
                                            <tr class="text-muted small">
                                                <th class="pb-2">MÃ VÉ</th>
                                                <th class="pb-2 text-center">MÃ GHẾ</th>
                                                <th class="pb-2 text-end">GIÁ VÉ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($booking->tickets as $ticket)
                                                <tr class="align-middle">
                                                    <td class="py-2">#{{ $ticket->id }}</td>
                                                    <td class="py-2 text-center">
                                                        <span
                                                            class="badge bg-primary-subtle text-primary border border-primary-subtle px-3">
                                                            {{ $ticket->seat_code }}
                                                        </span>
                                                    </td>
                                                    <td class="py-2 text-end">
                                                        {{ number_format($ticket->price, 0, ',', '.') }} VND</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="border-top border-secondary">
                                            <tr>
                                                <th colspan="2" class="text-end pt-3">Tổng số tiền:</th>
                                                <th class="text-end pt-3 text-warning fs-5">
                                                    {{ number_format($booking->total_amount, 0, ',', '.') }} VND
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0"> <button type="button" class="btn btn-outline-light"
                            data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    @empty
    @endforelse
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: @json(session('success')),
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: @json(session('error')),
                showConfirmButton: true
            });
        </script>
    @endif
@endpush
