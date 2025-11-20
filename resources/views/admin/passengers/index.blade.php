{{-- resources/views/admin/passengers/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý Hành khách')

@section('content')
<div class="mb-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                <i class="bi bi-people-fill"></i>
                Quản lý danh sách hành khách
            </h2>
            <p class="text-muted small mb-0">
                Theo dõi thông tin hành khách, ghế, vé, chuyến và tuyến tương ứng.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="#"
               class="btn btn-primary d-flex align-items-center gap-1">
                <i class="bi bi-plus-circle"></i>
                <span>Thêm hành khách mới</span>
            </a>

            <a href="#"
               class="btn btn-outline-light d-flex align-items-center gap-1">
                <i class="bi bi-trash3"></i>
                <span>Thùng rác</span>
            </a>
        </div>
    </div>

    {{-- Thông báo lỗi nhanh (nếu có) --}}
    @if($errors->any())
        <div class="alert alert-danger py-2 small">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Thông báo thành công --}}
    @if(session('success'))
        <div class="alert alert-success py-2 small">
            {{ session('success') }}
        </div>
    @endif

    {{-- Bộ lọc / tìm kiếm --}}
    <div class="card border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.passengers.index') }}" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label for="search" class="form-label text-muted small mb-1">Tìm kiếm</label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        class="form-control"
                        placeholder="Tên hành khách, số điện thoại, vé hoặc chuyến..."
                        value="{{ request('search') }}"
                    >
                </div>

                <div class="col-md-3">
                    <label for="ticket_status" class="form-label text-muted small mb-1">Trạng thái vé</label>
                    <select name="ticket_status" id="ticket_status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="pending" {{ request('ticket_status') === 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                        <option value="paid" {{ request('ticket_status') === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                        <option value="cancelled" {{ request('ticket_status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-search"></i>
                        <span>Lọc</span>
                    </button>
                    <a href="{{ route('admin.passengers.index') }}"
                       class="btn btn-outline-light flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-arrow-repeat"></i>
                        <span>Đặt lại</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Bảng danh sách hành khách --}}
    <div class="card border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-muted small">#</th>
                            <th class="text-muted small">Tên hành khách</th>
                            <th class="text-muted small">Số điện thoại</th>
                            <th class="text-muted small">Tuổi</th>
                            <th class="text-muted small">Ghế</th>
                            <th class="text-muted small">Vé liên quan</th>
                            <th class="text-muted small">Trạng thái vé</th>
                            <th class="text-muted small">Chuyến</th>
                            <th class="text-muted small">Tuyến</th>
                            <th class="text-muted small text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($passengers as $passenger)
                            <tr>
                                <td class="text-muted small">
                                    {{ $passengers->firstItem() + $loop->index }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $passenger->name }}
                                </td>

                                <td class="text-muted small">
                                    {{ $passenger->phone ?? '-' }}
                                </td>

                                <td class="text-muted small">
                                    {{ $passenger->age ?? '-' }}
                                </td>

                                <td>
                                    <span class="badge bg-info-subtle text-info border border-info-subtle">
                                        {{ $passenger->seat_label }}
                                    </span>
                                </td>

                                {{-- Vé liên quan: user của vé --}}
                                <td>
                                    @php
                                        $ticket = $passenger->ticket ?? null;
                                        $ticketUser = $ticket?->user;
                                    @endphp
                                    @if($ticketUser)
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold">
                                                {{ $ticketUser->full_name ?? $ticketUser->name ?? 'N/A' }}
                                            </span>
                                            @if(!empty($ticketUser->email))
                                                <span class="text-muted small">
                                                    {{ $ticketUser->email }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>

                                {{-- Trạng thái vé --}}
                                <td>
                                    @if(!empty($ticket?->trang_thai_label))
                                        {!! $ticket->trang_thai_label !!}
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>

                                {{-- Chuyến: ngày + giờ khởi hành nếu có --}}
                                <td class="text-muted small">
                                    @php
                                        $trip = $passenger->trip ?? null;
                                    @endphp

                                    @if($trip)
                                        {{ $trip->departure_date?->format('d/m/Y') ?? '-' }}
                                        {{ $trip->departure_time_formatted ?? $trip->departure_time ?? '' }}
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- Tuyến: fromCity → toCity (theo model routes mới) --}}
                                <td>
                                    @if($trip && $trip->route)
                                        <span class="fw-semibold">
                                            {{ $trip->route->fromCity?->name ?? '-' }}
                                            <span class="text-muted">→</span>
                                            {{ $trip->route->toCity?->name ?? '-' }}
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <a href="#"
                                       class="btn btn-sm btn-outline-info me-1">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="#"
                                       class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="#"
                                          method="POST"
                                          class="d-inline-block delete-passenger-form">
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
                                <td colspan="10" class="text-center py-4 text-muted">
                                    Chưa có hành khách nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3 px-3 pb-3">
                {{ $passengers->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success_toast'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: @json(session('success_toast')),
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
        // SweetAlert2 confirm xóa hành khách
        document.querySelectorAll('.delete-passenger-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc muốn xóa hành khách này?',
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
