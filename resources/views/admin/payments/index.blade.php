{{-- resources/views/admin/payments/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý Thanh toán')

@section('content')
<div class="mb-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                <i class="bi bi-credit-card-2-front-fill"></i>
                Quản lý danh sách thanh toán
            </h2>
            <p class="text-muted small mb-0">
                Theo dõi các giao dịch thanh toán: vé, người dùng, số tiền, phương thức và trạng thái xử lý.
            </p>
        </div>

        <div class="d-flex gap-2">
            {{-- Nếu sau này có màn tạo thanh toán tay thì mở route ra --}}
            <a href="#"
               class="btn btn-primary d-flex align-items-center gap-1">
                <i class="bi bi-plus-circle"></i>
                <span>Thêm thanh toán mới</span>
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

    {{-- Bộ lọc / tìm kiếm --}}
    <div class="card border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.payments.index') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="search" class="form-label text-muted small mb-1">Tìm kiếm</label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        class="form-control"
                        placeholder="Tìm theo tên người dùng, email hoặc mã vé..."
                        value="{{ request('search') }}"
                    >
                </div>

                <div class="col-md-3">
                    <label for="status" class="form-label text-muted small mb-1">Trạng thái</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Thành công</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Thất bại</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-search"></i>
                        <span>Lọc</span>
                    </button>
                    <a href="{{ route('admin.payments.index') }}"
                       class="btn btn-outline-light flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-arrow-repeat"></i>
                        <span>Đặt lại</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Bảng danh sách thanh toán --}}
    <div class="card border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-muted small">#</th>
                            <th class="text-muted small">Vé</th>
                            <th class="text-muted small">Người dùng</th>
                            <th class="text-muted small">Số tiền</th>
                            <th class="text-muted small">Phương thức</th>
                            <th class="text-muted small">Trạng thái</th>
                            <th class="text-muted small">Ngày thanh toán</th>
                            <th class="text-muted small text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td class="text-muted small">
                                    {{ $payments->firstItem() + $loop->index }}
                                </td>

                                {{-- Vé --}}
                                <td>
                                    @php
                                        $ticket = $payment->ticket ?? null;
                                    @endphp

                                    @if($ticket)
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold">
                                                Vé #{{ $ticket->id }}
                                            </span>
                                            @if(!empty($ticket->trip?->ma_chuyen))
                                                <span class="text-muted small">
                                                    Mã chuyến: {{ $ticket->trip->ma_chuyen }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>

                                {{-- Người dùng --}}
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold">
                                            {{ $payment->user->full_name ?? $payment->user->name ?? 'N/A' }}
                                        </span>
                                        @if(!empty($payment->user?->email))
                                            <span class="text-muted small">
                                                {{ $payment->user->email }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Số tiền --}}
                                <td>
                                    <span class="badge bg-secondary-subtle text-light border border-primary-subtle">
                                        {{ number_format($payment->so_tien, 0, ',', '.') }}₫
                                    </span>
                                </td>

                                {{-- Phương thức --}}
                                <td>
                                    @php
                                        $method = $payment->phuong_thuc ?? '-';
                                    @endphp

                                    @if($method !== '-')
                                        <span class="badge bg-info-subtle text-info border border-info-subtle">
                                            {{ strtoupper($method) }}
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>

                                {{-- Trạng thái --}}
                                <td>
                                    @if($payment->trang_thai == 'pending')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                            <i class="bi bi-hourglass-split me-1"></i>
                                            Chờ xử lý
                                        </span>
                                    @elseif($payment->trang_thai == 'success')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Thành công
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Thất bại
                                        </span>
                                    @endif
                                </td>

                                {{-- Ngày thanh toán --}}
                                <td class="text-muted small">
                                    {{ $payment->created_at?->format('d/m/Y H:i') ?? '-' }}
                                </td>

                                {{-- Hành động --}}
                                <td class="text-center">
                                    {{-- Hiện tại chưa có route show/edit cụ thể, ông thay # bằng route khi có --}}
                                    <a href="#"
                                       class="btn btn-sm btn-outline-info me-1">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="#"
                                       class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('admin.payments.destroy', $payment->id) }}"
                                          method="POST"
                                          class="d-inline-block delete-payment-form">
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
                                <td colspan="8" class="text-center py-4 text-muted">
                                    Chưa có thanh toán nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3 px-3 pb-3">
                {{ $payments->withQueryString()->links('pagination::bootstrap-4') }}
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
        // SweetAlert2 confirm xóa thanh toán
        document.querySelectorAll('.delete-payment-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc muốn xóa thanh toán này?',
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