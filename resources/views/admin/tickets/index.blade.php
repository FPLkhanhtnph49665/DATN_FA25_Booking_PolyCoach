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
        </div>

        {{-- Thông báo lỗi nhanh (nếu có) --}}
        @if ($errors->any())
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
                        <input type="text" name="search" id="search" class="form-control"
                            placeholder="Tìm theo tên khách, email, số điện thoại hoặc tên thành phố..."
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-md-3">
                        <label for="status" class="form-label text-white small mb-1">Trạng thái vé</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">-- Tất cả --</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ thanh toán
                            </option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Đã thanh toán
                            </option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy
                            </option>
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
                                <th class="text-muted small">Khách hàng</th>
                                <th class="text-muted small">Số ghế</th>
                                <th class="text-muted small">giá vé</th>
                                <th class="text-muted small">thanh toán</th>
                                <th class="text-muted small">Trạng thái</th>
                                <th class="text-muted small">kiểm bởi</th>
                                <th class="text-muted small text-center"></th>
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

                                        @if ($trip)
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold">
                                                    {{ $trip->route?->fromCity?->name ?? 'N/A' }}
                                                    <span class="text-muted">→</span>
                                                    {{ $trip->route?->toCity?->name ?? 'N/A' }}
                                                </span>
                                                <span class="text-muted small">
                                                    @if ($trip->departure_date)
                                                        {{ $trip->departure_date->format('d/m/Y') }}
                                                    @endif
                                                    {{ $trip->departure_time_formatted ?? ($trip->departure_time ?? '') }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-muted small">N/A</span>
                                        @endif
                                    </td>

                                    {{-- Người dùng --}}
                                    <td>
                                        <div class="d-flex flex-column">
                                            {{-- Kiểm tra user tồn tại trước khi truy cập thuộc tính --}}
                                            <span class="fw-semibold">
                                                {{ $ticket->user->full_name ?? ($ticket->user->name ?? 'Khách vãng lai') }}
                                            </span>

                                            @if ($ticket->user)
                                                {{-- Hiển thị Số điện thoại nếu có (không dùng elseif để hiện cả hai) --}}
                                                @if ($ticket->user->phone)
                                                    <span class="text-muted small">
                                                        <i class="bi bi-telephone"></i> {{ $ticket->user->phone }}
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Số ghế --}}
                                    <td>
                                        @php
                                            $seatInfo = $ticket->seat_code ?? ($ticket->seat_code ?? null);
                                        @endphp

                                        @if (is_array($seatInfo))
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

                                    {{-- Giá vé --}}

                                    <td class="fw-semibold text-success">
                                        @if ($ticket->price !== null)
                                            {{ number_format($ticket->price, 0, ',', '.') }} VND
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    {{-- Phương thức thanh toán --}}
                                    <td>
                                        @php
                                            $method = $ticket->payment_method ?? '-';
                                        @endphp

                                        @if ($method !== '-')
                                            <span
                                                class="badge bg-info-subtle text-info border border-info-subtle">
                                                {{ ucfirst($method) }}
                                            </span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    {{-- Trạng thái --}}
                                    <td>
                                        @if ($ticket->status === 'pending')
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
                                    {{-- Kiểm bởi --}}
                                    <td>
                                        @if ($ticket->checked_by)
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold">
                                                    {{ $ticket->checker->full_name ?? ($ticket->checker->name ?? 'N/A') }}
                                                </span>
                                                @if ($ticket->checked_at)
                                                    <span class="text-muted small">
                                                        {{ \Carbon\Carbon::parse($ticket->checked_at)->format('d/m/Y H:i') }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted small">Chưa kiểm</span>
                                        @endif
                                    </td>
                                    {{-- Hành động --}}
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                            data-bs-target="#modalTicket{{ $ticket->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
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
    {{-- model chi tiết vé --}}
    @forelse($tickets as $ticket)
        <tr>
        </tr>

        <div class="modal fade" id="modalTicket{{ $ticket->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold">Chi tiết vé #{{ $ticket->id }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-primary fw-bold mb-3">Thông tin người đặt</h6>
                        <div class="ps-2">
                            <p class="mb-2"><span class="text-white-50">Họ tên:</span> {{ $ticket->user->full_name ?? 'Khách lẻ' }}</p>
                            <p class="mb-2"><span class="text-white-50">Email:</span> {{ $ticket->user->email ?? '-' }}</p>
                            <p class="mb-2"><span class="text-white-50">Điện thoại:</span> {{ $ticket->user->phone ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-primary fw-bold mb-3">Thông tin xe</h6>
                        <div class="ps-2">
                            @if($ticket->trip && $ticket->trip->bus)
                                <p class="mb-2"><span class="text-white-50">số ghế:</span> <span class="text-info">{{ $ticket->trip->bus->seat_count }}</span></p>
                                <p class="mb-2"><span class="text-white-50">Biển số:</span> <span class="badge bg-light text-dark">{{ $ticket->trip->bus->plate_number }}</span></p>
                                <p class="mb-2"><span class="text-white-50">Loại xe:</span> {{ $ticket->trip->bus->type ?? 'Ghế ngồi/Giường nằm' }}</p>
                            @else
                                <p class="text-white-50">Chưa cập nhật thông tin xe</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <h6 class="text-primary fw-bold mb-3">Thông tin hành trình</h6>
                        
                        <div class="bg-secondary bg-opacity-10 p-4 rounded border border-secondary border-opacity-50 shadow-sm">
                            <div class="row align-items-center">
                                <div class="col-md-7">
                                    @if($ticket->pointFare)
                                        <p class="mb-1 text-info fw-semibold">Hành trình theo chặng:</p>
                                        <div class="d-flex align-items-center gap-2 mb-2 fs-5 fw-bold">
                                            <span>{{ $ticket->pointFare->pickupPoint->name }}</span>
                                            <i class="bi bi-arrow-right text-white-50"></i>
                                            <span>{{ $ticket->pointFare->dropoffPoint->name }}</span>
                                        </div>
                                        <small class="text-white-50 fw-light italic">
                                            (Tuyến chính: {{ $ticket->trip->route->fromCity->name }} → {{ $ticket->trip->route->toCity->name }})
                                        </small>
                                    @else
                                        <p class="mb-1 text-info fw-semibold">Hành trình chính:</p>
                                        <div class="fs-5 fw-bold">
                                            {{ $ticket->trip->route->fromCity->name }} 
                                            <i class="bi bi-arrow-right mx-2 text-white-50"></i> 
                                            {{ $ticket->trip->route->toCity->name }}
                                        </div>
                                    @endif
                                    
                                    <div class="mt-3 small d-flex gap-3">
                                        <span><i class="bi bi-calendar3 text-white-50 me-1"></i> {{ $ticket->trip->departure_date->format('d/m/Y') }}</span>
                                        <span><i class="bi bi-clock text-white-50 me-1"></i> {{ $ticket->trip->departure_time }}</span>
                                    </div>
                                </div>

                                <div class="col-md-5 text-md-end border-start border-secondary border-opacity-50">
                                    <div class="mb-2">
                                        <span class="text-white-50">Mã ghế:</span> 
                                        <span class="text-white fw-bold fs-4 ms-1">{{ $ticket->seat_code }}</span>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-white-50 small">Giá vé niêm yết:</p>
                                        <h3 class="text-warning fw-bold mb-0">{{ number_format($ticket->price, 0, ',', '.') }} <small class="fs-6">VND</small></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-light px-4" data-bs-dismiss="modal">Đóng</button>
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

    <script>
        // SweetAlert2 confirm xóa vé
        document.querySelectorAll('.delete-ticket-form').forEach(form => {
            form.addEventListener('submit', function(e) {
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
