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
                <p class="text-white small mb-0">
                    Theo dõi các giao dịch thanh toán: vé, người dùng, số tiền, phương thức và trạng thái xử lý.
                </p>
            </div>
        </div>

        {{-- Error message --}}
        @if ($errors->any())
            <div class="alert alert-danger py-2 small">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Filters --}}
        <div class="card border-0 mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.payments.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="search" class="form-label text-white small mb-1">Tìm kiếm</label>
                        <input type="text" name="search" id="search" class="form-control"
                            placeholder="Tìm theo tên người dùng, email hoặc mã vé..." value="{{ request('search') }}">
                    </div>

                    <div class="col-md-3">
                        <label for="status" class="form-label text-white small mb-1">Trạng thái</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">-- Tất cả --</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xử lý
                            </option>
                            <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Thành công
                            </option>
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

        {{-- Payments table --}}
        <div class="card border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="text-muted small">#</th>
                                <th class="text-muted small">Người dùng</th>
                                <th class="text-muted small">số vé</th>
                                <th class="text-muted small">giá vé</th>
                                <th class="text-muted small">tổng tiền</th>
                                <th class="text-muted small">Phương thức</th>
                                <th class="text-muted small">Trạng thái</th>
                                <th class="text-muted small">Ngày thanh toán</th>
                                <th class="text-muted small text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <td class="text-muted small">
                                        {{ $payments->firstItem() + $loop->index }}
                                    </td>

                                    {{-- User --}}
                                    <td>
                                        @if ($payment->user)
                                            <div class="d-flex flex-column">
                                                <span
                                                    class="fw-semibold">{{ $payment->user->full_name ?? $payment->user->name }}</span>
                                                @if (!empty($payment->user->email))
                                                    <span class="text-muted small">{{ $payment->user->email }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted small">N/A</span>
                                        @endif
                                    </td>

                                    {{-- Ticket --}}
                                    <td>
                                        {{ $payment->booking->tickets->count() }} vé
                                    </td>

                                    {{-- Ticket price --}}
                                    <td>
                                        @if ($payment->booking && $payment->booking->tickets->isNotEmpty())
                                            <span class="fw-semibold text-success">
                                                {{-- Lấy giá từ vé đầu tiên trong booking --}}
                                                {{ number_format($payment->booking->tickets->first()->price, 0, ',', '.') }}
                                                VND
                                            </span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>

                                    {{-- Amount --}}
                                    <td>
                                        <span class="fw-semibold text-danger">
                                            {{ number_format($payment->amount, 0, ',', '.') }} VND
                                        </span>
                                    </td>

                                    {{-- Payment method --}}
                                    <td>
                                        @php
                                            $method = $payment->payment_method ?? '-';
                                        @endphp
                                        @if ($method !== '-')
                                            <span class="badge bg-info-subtle text-info border border-info-subtle">
                                                {{ strtoupper($method) }}
                                            </span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td>
                                        @php $status = $payment->status; @endphp
                                        @if ($status === 'pending')
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                                <i class="bi bi-hourglass-split me-1"></i> Chờ xử lý
                                            </span>
                                        @elseif($status === 'success')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                <i class="bi bi-check-circle me-1"></i> Thành công
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                                <i class="bi bi-x-circle me-1"></i> Thất bại
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Paid at --}}
                                    <td class="text-muted small">
                                        {{ $payment->created_at?->format('d/m/Y H:i') ?? '-' }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-center">
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
        document.querySelectorAll('.delete-payment-form').forEach(form => {
            form.addEventListener('submit', function(e) {
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
