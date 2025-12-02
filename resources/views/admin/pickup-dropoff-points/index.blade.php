{{-- resources/views/admin/pickup-dropoff-points/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Điểm đón / trả khách')

@section('content')
<div class="mb-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                <i class="bi bi-pin-map-fill"></i>
                Điểm đón / trả khách
            </h2>
            <p class="text-muted small mb-0">
                Quản lý hệ thống điểm đón và trả khách cho các tuyến xe trong PolyCoach: theo thành phố, tuyến, loại điểm & trạng thái.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.pickup-dropoff-points.create') }}"
               class="btn btn-primary d-flex align-items-center gap-1">
                <i class="bi bi-plus-circle"></i>
                <span>Thêm điểm đón/trả</span>
            </a>
        </div>
    </div>

    {{-- Bộ lọc --}}
    <div class="card border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pickup-dropoff-points.index') }}" class="row g-3 align-items-end">

                {{-- Tìm kiếm --}}
                <div class="col-md-3">
                    <label for="search" class="form-label text-muted small mb-1">Tìm kiếm</label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Tên điểm, địa chỉ..."
                    >
                </div>

                {{-- Thành phố --}}
                <div class="col-md-2">
                    <label for="city_id" class="form-label text-muted small mb-1">Thành phố</label>
                    <select name="city_id" id="city_id" class="form-select">
                        <option value="">-- Tất cả --</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tuyến xe --}}
                <div class="col-md-2">
                    <label for="route_id" class="form-label text-muted small mb-1">Tuyến xe</label>
                    <select name="route_id" id="route_id" class="form-select">
                        <option value="">-- Tất cả --</option>
                        @foreach ($routes as $route)
                            <option value="{{ $route->id }}" {{ request('route_id') == $route->id ? 'selected' : '' }}>
                                {{ $route->name ?? ('Tuyến #' . $route->id) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Loại điểm --}}
                <div class="col-md-2">
                    <label for="type" class="form-label text-muted small mb-1">Loại điểm</label>
                    <select name="type" id="type" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="pickup" {{ request('type') === 'pickup' ? 'selected' : '' }}>Điểm đón</option>
                        <option value="dropoff" {{ request('type') === 'dropoff' ? 'selected' : '' }}>Điểm trả</option>
                    </select>
                </div>

                {{-- Trạng thái --}}
                <div class="col-md-2">
                    <label for="active" class="form-label text-muted small mb-1">Trạng thái</label>
                    <select name="active" id="active" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Ngưng</option>
                    </select>
                </div>

                {{-- Nút --}}
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit"
                            class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-search"></i>
                        <span>Lọc</span>
                    </button>
                    <a href="{{ route('admin.pickup-dropoff-points.index') }}"
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
                            <th class="text-muted small">Tên điểm</th>
                            <th class="text-muted small">Thành phố</th>
                            <th class="text-muted small">Tuyến</th>
                            <th class="text-muted small">Địa chỉ</th>
                            <th class="text-muted small">Thời gian</th>
                            <th class="text-muted small">Loại</th>
                            <th class="text-muted small">Trạng thái</th>
                            <th class="text-muted small text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($points as $point)
                            <tr>
                                <td class="text-muted small">
                                    {{ $points->firstItem() + $loop->index }}
                                </td>
                                <td class="fw-semibold">
                                    {{ $point->name }}
                                </td>
                                <td>
                                    {{ $point->city?->name ?? '—' }}
                                </td>
                                <td>
                                    {{ $point->route?->name ?? ('Tuyến #' . $point->route_id) }}
                                </td>
                                <td>
                                    {{ $point->address ?? '—' }}
                                </td>
                                <td class="text-muted small">
                                    {{ $point->time ?? '—' }}
                                </td>
                                <td>
                                    @if ($point->type === 'pickup')
                                        <span class="badge bg-info-subtle text-info border border-info-subtle">
                                            <i class="bi bi-arrow-up-circle me-1"></i>
                                            Điểm đón
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                            <i class="bi bi-arrow-down-circle me-1"></i>
                                            Điểm trả
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($point->active)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 0.55rem;"></i>
                                            Hoạt động
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                            <i class="bi bi-slash-circle me-1"></i>
                                            Ngưng
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.pickup-dropoff-points.edit', $point->id) }}"
                                       class="btn btn-sm btn-outline-info me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('admin.pickup-dropoff-points.destroy', $point->id) }}"
                                          method="POST"
                                          class="d-inline-block delete-point-form">
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
                                <td colspan="9" class="text-center py-4 text-muted">
                                    Chưa có điểm đón/trả nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3 px-3 pb-3">
                {{ $points->links('pagination::bootstrap-4') }}
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
        document.querySelectorAll('.delete-point-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc muốn xóa điểm này?',
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
