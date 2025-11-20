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
            <p class="text-muted small mb-0">
                Quản lý các đơn đặt vé: khách hàng, chuyến, ghế, trạng thái và phương thức thanh toán.
            </p>
        </div>

        {{-- Nếu sau này có nút tạo booking tay thì mở ra --}}
        {{-- <div class="d-flex gap-2">
            <a href="{{ route('admin.bookings.create') }}"
               class="btn btn-primary d-flex align-items-center gap-1">
                <i class="bi bi-plus-circle"></i>
                <span>Thêm Booking mới</span>
            </a>
        </div> --}}
    </div>

    {{-- Thông báo lỗi nhanh --}}
    @if($errors->any())
        <div class="alert alert-danger py-2 small">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Bộ lọc / tìm kiếm --}}
    <div class="card border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.bookings.index') }}" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label for="search" class="form-label text-muted small mb-1">Tìm kiếm</label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        class="form-control"
                        placeholder="Tìm theo tên khách hàng, email hoặc mã chuyến..."
                        value="{{ request('search') }}"
                    >
                </div>

                {{-- Nếu controller chưa filter theo status / payment_method thì sau này ông có thể bổ sung --}}
                <div class="col-md-3">
                    <label for="status" class="form-label text-muted small mb-1">Trạng thái Booking</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
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
                            <th class="text-muted small">Thanh toán</th>
                            <th class="text-muted small">Ngày đặt</th>
                            <th class="text-muted small text-center">Hành động</th>
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
                                        @if(!empty($booking->user?->email))
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

                                    @if($trip)
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold">
                                                {{ $trip->route?->fromCity?->name ?? '-' }}
                                                <span class="text-muted">→</span>
                                                {{ $trip->route?->toCity?->name ?? '-' }}
                                            </span>
                                            <span class="text-muted small">
                                                {{ $trip->departure_date?->format('d/m/Y') ?? '-' }}
                                                {{ $trip->departure_time_formatted ?? $trip->departure_time ?? '' }}
                                            </span>
                                            @if(!empty($trip->ma_chuyen))
                                                <span class="badge bg-secondary-subtle text-light border border-primary-subtle mt-1">
                                                    Mã chuyến: {{ $trip->ma_chuyen }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Số ghế --}}
                                <td>
                                    @php
                                        // ưu tiên dùng field mới nếu ông có, fallback về so_ghe
                                        $seatInfo = $booking->seat_numbers ?? $booking->so_ghe ?? null;
                                    @endphp

                                    @if(is_array($seatInfo))
                                        <span class="badge bg-info-subtle text-info border border-info-subtle">
                                            {{ implode(', ', $seatInfo) }}
                                        </span>
                                    @elseif($seatInfo)
                                        <span class="badge bg-info-subtle text-info border border-info-subtle">
                                            {{ $seatInfo }}
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>

                                {{-- Trạng thái (status_label accessor đã trả về HTML) --}}
                                <td>
                                    {!! $booking->status_label !!}
                                </td>

                                {{-- Phương thức thanh toán --}}
                                <td>
                                    @php
                                        $method = $booking->payment_method ?? '-';
                                        $methodLabel = ucfirst($method);
                                    @endphp

                                    @if($method !== '-')
                                        <span class="badge bg-secondary-subtle text-light border border-secondary-subtle">
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
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                       class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    {{-- Nếu sau này ông muốn thêm hủy/confirm booking thì gắn thêm nút ở đây --}}
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
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
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

    @if(session('error'))
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
