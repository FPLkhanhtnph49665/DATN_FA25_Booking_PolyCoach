{{-- resources/views/admin/tickets/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý Vé')

@section('content')
<div class="mb-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                <i class="bi bi-receipt-cutoff"></i>
                Danh sách vé
            </h2>
            <p class="text-white small mb-0">
                Quản lý vé theo chuyến, khách hàng, số ghế, trạng thái và phương thức thanh toán.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.tickets.create') }}"
               class="btn btn-primary d-flex align-items-center gap-1">
                <i class="bi bi-plus-circle"></i>
                <span>Thêm vé mới</span>
            </a>
        </div>
    </div>

    {{-- Thông báo lỗi nhanh (nếu có) --}}
    @if($errors->any())
        <div class="alert alert-danger py-2 small">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Bộ lọc / tìm kiếm --}}
    <div class="card border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.tickets.index') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="search" class="form-label text-white small mb-1">Tìm kiếm</label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        class="form-control"
                        placeholder="Tìm theo tên khách, email hoặc mã chuyến..."
                        value="{{ request('search') }}"
                    >
                </div>

                <div class="col-md-3">
                    <label for="status" class="form-label text-white small mb-1">Trạng thái vé</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-search"></i>
                        <span>Lọc</span>
                    </button>
                    <a href="{{ route('admin.tickets.index') }}"
                       class="btn btn-outline-light flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-arrow-repeat"></i>
                        <span>Đặt lại</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Bảng danh sách vé --}}
    <div class="card border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-muted small">#</th>
                            <th class="text-muted small">Chuyến</th>
                            <th class="text-muted small">Người dùng</th>
                            <th class="text-muted small">Số ghế</th>
                            <th class="text-muted small">Trạng thái</th>
                            <th class="text-muted small">Phương thức thanh toán</th>
                            <th class="text-muted small text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td class="text-muted small">
                                    {{ $tickets->firstItem() + $loop->index }}
                                </td>

                                {{-- Chuyến: ưu tiên hiển thị tuyến nếu có quan hệ route --}}
                                <td>
                                    @php
                                        $trip = $ticket->trip ?? null;
                                    @endphp

                                    @if($trip)
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold">
                                                {{ $trip->route?->fromCity?->name ?? 'N/A' }}
                                                <span class="text-muted">→</span>
                                                {{ $trip->route?->toCity?->name ?? 'N/A' }}
                                            </span>
                                            <span class="text-muted small">
                                                @if($trip->departure_date)
                                                    {{ $trip->departure_date->format('d/m/Y') }}
                                                @endif
                                                {{ $trip->departure_time_formatted ?? $trip->departure_time ?? '' }}
                                            </span>
                                            <span class="text-muted small">
                                                Mã chuyến: {{ $trip->ma_chuyen ?? $trip->id }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>

                                {{-- Người dùng --}}
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold">
                                            {{ $ticket->user->full_name ?? $ticket->user->name ?? 'N/A' }}
                                        </span>
                                        @if(!empty($ticket->user?->email))
                                            <span class="text-muted small">
                                                {{ $ticket->user->email }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Số ghế --}}
                                <td>
                                    @php
                                        $seatInfo = $ticket->seat_code ?? $ticket->seat_code ?? null;
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

                                {{-- Trạng thái --}}
                                <td>
                                    @if($ticket->status === 'pending')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                            <i class="bi bi-hourglass-split me-1"></i>
                                            Chờ thanh toán
                                        </span>
                                    @elseif($ticket->status === 'paid')
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

                                {{-- Phương thức thanh toán --}}
                                <td>
                                    @php
                                        $method = $ticket->payment_method ?? '-';
                                    @endphp

                                    @if($method !== '-')
                                        <span class="badge bg-secondary-subtle text-light border border-secondary-subtle">
                                            {{ ucfirst($method) }}
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>

                                {{-- Hành động --}}
                                <td class="text-center">
                                    <a href="{{ route('admin.tickets.show', $ticket->id) }}"
                                       class="btn btn-sm btn-outline-info me-1">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.tickets.edit', $ticket->id) }}"
                                       class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('admin.tickets.destroy', $ticket->id) }}"
                                          method="POST"
                                          class="d-inline-block delete-ticket-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Chưa có vé nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3 px-3 pb-3">
                {{ $tickets->withQueryString()->links('pagination::bootstrap-4') }}
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

    <script>
        // SweetAlert2 confirm xóa vé
        document.querySelectorAll('.delete-ticket-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc muốn xóa vé này?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
