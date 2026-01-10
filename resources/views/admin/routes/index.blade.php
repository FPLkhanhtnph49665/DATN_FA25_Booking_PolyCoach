{{-- resources/views/admin/routes/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý Tuyến đường')

@section('content')
<div class="mb-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                <i class="bi bi-signpost-split-fill"></i>
                Quản lý tuyến đường
            </h2>
            <p class="text-white small mb-0">
                Cấu hình các tuyến xe giữa các thành phố: quãng đường, thời gian dự kiến và trạng thái hoạt động.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.routes.create') }}"
               class="btn btn-primary d-flex align-items-center gap-1">
                <i class="bi bi-plus-circle"></i>
                <span>Thêm tuyến mới</span>
            </a>
        </div>
    </div>

    {{-- Thông báo lỗi nhanh --}}
    @if($errors->any())
        <div class="alert alert-danger py-2 small">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Bộ lọc / Tìm kiếm --}}
    <div class="card border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('admin.routes.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="search" class="form-label text-white small mb-1">Tìm kiếm</label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Tìm theo thành phố đi, đến..."
                    >
                </div>

                <div class="col-md-3">
                    <label for="status" class="form-label text-white small mb-1">Trạng thái</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Dừng hoạt động</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit"
                            class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-search"></i>
                        <span>Lọc</span>
                    </button>
                    <a href="{{ route('admin.routes.index') }}"
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
                            <th class="text-muted small">Tuyến</th>
                            <th class="text-muted small">Thành phố đi</th>
                            <th class="text-muted small">Thành phố đến</th>
                            <th class="text-muted small">Quãng đường (km)</th>
                            <th class="text-muted small">Thời gian dự kiến</th>
                            <th class="text-muted small">Trạng thái</th>
                            <th class="text-muted small text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($routes as $route)
                            <tr>
                                <td class="text-muted small">
                                    {{ $routes->firstItem() + $loop->index }}
                                </td>

                                {{-- Tuyến: Thành phố đi → Thành phố đến --}}
                                <td class="fw-semibold">
                                    {{ $route->fromCity?->name ?? '—' }}
                                    <span class="text-muted">→</span>
                                    {{ $route->toCity?->name ?? '—' }}
                                </td>

                                <td>
                                    {{ $route->fromCity?->name ?? '—' }}
                                </td>

                                <td>
                                    {{ $route->toCity?->name ?? '—' }}
                                </td>

                                <td>
                                    @if(!is_null($route->distance))
                                        <span class="badge bg-secondary-subtle text-dark border border-primary-subtle">
                                            {{ number_format($route->distance) }} km
                                        </span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>

                                <td class="text-muted small">
                                    @if($route->estimated_time)
                                        {{ \Carbon\Carbon::parse($route->estimated_time)->format('H:i') }}
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>
                                    @if($route->status == 1)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 0.55rem;"></i>
                                            Hoạt động
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                            <i class="bi bi-slash-circle me-1"></i>
                                            Dừng hoạt động
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('admin.routes.edit', $route->id) }}"
                                       class="btn btn-sm btn-outline-info me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('admin.routes.destroy', $route->id) }}"
                                          method="POST"
                                          class="d-inline-block delete-route-form">
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
                                    Chưa có tuyến nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3 px-3 pb-3">
                {{ $routes->links('pagination::bootstrap-4') }}
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
        // Confirm xóa bằng SweetAlert2
        document.querySelectorAll('.delete-route-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc muốn xóa tuyến này?',
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
