{{-- resources/views/admin/point_fares/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý giá vé chặng')

@section('content')
<div class="mb-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                <i class="bi bi-currency-dollar"></i>
                Danh sách Giá vé chặng
            </h2>
            <p class="text-muted small mb-0">
                Quản lý giá vé áp dụng cho từng chặng đường cụ thể (Điểm đón &rarr; Điểm trả) trong hệ thống PolyCoach.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.point_fares.create') }}" class="btn btn-primary d-flex align-items-center gap-1">
                <i class="bi bi-plus-circle"></i>
                <span>Thêm giá vé</span>
            </a>
        </div>
    </div>

    {{-- Bộ lọc / Tìm kiếm --}}
    <div class="card border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.point_fares.index') }}" class="row g-3 align-items-end">
                {{-- Ô tìm kiếm theo Điểm đi/đến (Giả định có trường 'name' hoặc 'code' trong Points) --}}
                <div class="col-md-5">
                    <label for="search" class="form-label text-light small mb-1">Tìm kiếm điểm</label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Nhập tên điểm đón hoặc điểm trả..."
                    >
                </div>

                {{-- Khoảng giá (Để trống hoặc thêm bộ lọc phức tạp hơn nếu cần) --}}
                <div class="col-md-3">
                    <label for="min_price" class="form-label text-muted small mb-1">Giá tối thiểu (VND)</label>
                    <input
                        type="number"
                        name="min_price"
                        id="min_price"
                        value="{{ request('min_price') }}"
                        class="form-control"
                        placeholder="VD: 50000"
                    >
                </div>

                {{-- Nút --}}
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit"
                            class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-search"></i>
                        <span>Lọc</span>
                    </button>
                    <a href="{{ route('admin.point_fares.index') }}"
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
                            <th class="text-muted small">#</th>
                            <th class="text-muted small">Điểm đón (Pickup)</th>
                            <th class="text-muted small">Điểm trả (Dropoff)</th>
                            <th class="text-muted small">Giá vé</th>
                            <th class="text-muted small">Ngày tạo</th>
                            <th class="text-muted small text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pointFares as $fare)
                            <tr>
                                <td class="text-muted small">
                                    {{ $pointFares->firstItem() + $loop->index }}
                                </td>
                                {{-- Thông tin Điểm đón --}}
                                <td class="fw-semibold">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle me-2">
                                        <i class="bi bi-arrow-up-right-circle-fill"></i>
                                    </span>
                                    {{ $fare->pickupPoint->dia_chi ?? '—' }}
                                </td>
                                {{-- Thông tin Điểm trả --}}
                                <td class="fw-semibold">
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle me-2">
                                        <i class="bi bi-arrow-down-left-circle-fill"></i>
                                    </span>
                                    {{ $fare->dropoffPoint->dia_chi ?? '—' }}
                                </td>
                                {{-- Giá vé --}}
                                <td>
                                    <span class="fw-bold text-success">
                                        {{ number_format($fare->price, 0, ',', '.') }} VND
                                    </span>
                                </td>
                                {{-- Ngày tạo --}}
                                <td class="text-muted small">
                                    {{ $fare->created_at?->format('d/m/Y H:i') ?? '—' }}
                                </td>
                                {{-- Hành động --}}
                                <td class="text-end">
                                    <a href="{{ route('admin.point_fares.edit', $fare->id) }}"
                                       class="btn btn-sm btn-outline-info me-1" title="Chỉnh sửa">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('admin.point_fares.destroy', $fare->id) }}"
                                          method="POST"
                                          class="d-inline-block delete-fare-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger" title="Xóa">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Không có giá vé chặng nào được thiết lập.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Phân trang --}}
            <div class="d-flex justify-content-end mt-3 px-3 pb-3">
                {{ $pointFares->links('pagination::bootstrap-4') }}
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
        // SweetAlert2 confirm xóa
        document.querySelectorAll('.delete-fare-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc muốn xóa giá vé chặng này?',
                    text: "Giá vé sẽ bị loại bỏ khỏi hệ thống.",
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