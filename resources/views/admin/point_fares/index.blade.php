{{-- resources/views/admin/point_fares/index.blade.php --}}
@extends('layouts.admin')

<<<<<<< HEAD
@section('title', 'Quản lý giá vé theo điểm')

@section('content')
<div class="mb-4">

=======
@section('title', 'Giá vé chặng')

@section('content')
<div class="mb-4">
>>>>>>> f2d58eb95d774cf948ca94767c758deb6261785d
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
<<<<<<< HEAD
                <i class="bi bi-cash-coin"></i>
                Danh sách giá vé theo điểm
            </h2>
            <p class="text-muted small mb-0">
                Quản lý giá vé dựa trên tuyến – điểm đón – điểm trả.
            </p>
        </div>

        <a href="{{ route('admin.point_fares.create') }}"
           class="btn btn-primary d-flex align-items-center gap-1 shadow-sm">
            <i class="bi bi-plus-circle"></i>
            <span>Thêm giá vé mới</span>
        </a>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-dark text-light">
                        <tr>
                            <th class="small">#</th>
                            <th class="small">Tuyến xe</th>
                            <th class="small">Điểm đón</th>
                            <th class="small">Điểm trả</th>
                            <th class="small">Giá vé</th>
                            <th class="small">Ngày tạo</th>
                            <th class="small text-end">Hành động</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse ($pointFares as $pointFare)
                        <tr>
                            <td class="text-muted small">
                                {{ $pointFares->firstItem() + $loop->index }}
                            </td>

                            <td class="fw-semibold">
                                {{ $pointFare->route->fromCity->name ?? '---' }}
                                →
                                {{ $pointFare->route->toCity->name ?? '---' }}
                            </td>

                            <td>
                                <span class="badge bg-secondary-subtle text-dark border border-secondary-subtle">
                                    {{ $pointFare->pickupPoint->name ?? '---' }}
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-info-subtle text-info border border-info-subtle">
                                    {{ $pointFare->dropoffPoint->name ?? '---' }}
                                </span>
                            </td>

                            <td class="fw-semibold">
                                {{ number_format($pointFare->price, 0, ',', '.') }} VNĐ
                            </td>

                            <td class="text-muted small">
                                {{ $pointFare->created_at?->format('d/m/Y H:i') ?? '—' }}
                            </td>

                            <td class="text-end">

                                <a href="{{ route('admin.point_fares.edit', $pointFare->id) }}"
                                   class="btn btn-sm btn-outline-info me-1">
                                   <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('admin.point_fares.destroy', $pointFare->id) }}"
                                      method="POST"
                                      class="d-inline-block delete-pointfare-form">
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
                                Không có dữ liệu giá vé.
                            </td>
                        </tr>
                    @endforelse
=======
                <i class="bi bi-currency-dollar"></i>
                Giá vé chặng
            </h2>
            <p class="text-muted small mb-0">
                Quản lý hệ thống giá vé giữa các điểm đón và điểm trả trên các tuyến xe.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.point_fares.create') }}"
               class="btn btn-primary d-flex align-items-center gap-1">
                <i class="bi bi-plus-circle"></i>
                <span>Thêm giá vé mới</span>
            </a>
        </div>
    </div>

    {{-- Bộ lọc --}}
    <div class="card border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.point_fares.index') }}" class="row g-3 align-items-end">

                {{-- Tuyến xe --}}
                <div class="col-md-3">
                    <label for="route_id" class="form-label text-muted small mb-1">Tuyến xe</label>
                    <select name="route_id" id="route_id" class="form-select">
                        <option value="">-- Tất cả --</option>
                        @foreach ($routes as $route)
                            <option value="{{ $route->id }}" {{ request('route_id') == $route->id ? 'selected' : '' }}>
                                {{ $route->name ?? ($route->fromCity?->name . ' → ' . $route->toCity?->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Điểm đón --}}
                <div class="col-md-3">
                    <label for="pickup_point_id" class="form-label text-muted small mb-1">Điểm đón</label>
                    <select name="pickup_point_id" id="pickup_point_id" class="form-select">
                        <option value="">-- Tất cả --</option>
                        @foreach ($pickupPoints as $point)
                            <option value="{{ $point->id }}" {{ request('pickup_point_id') == $point->id ? 'selected' : '' }}>
                                {{ $point->name }} ({{ $point->city?->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Điểm trả --}}
                <div class="col-md-3">
                    <label for="dropoff_point_id" class="form-label text-muted small mb-1">Điểm trả</label>
                    <select name="dropoff_point_id" id="dropoff_point_id" class="form-select">
                        <option value="">-- Tất cả --</option>
                        @foreach ($dropoffPoints as $point)
                            <option value="{{ $point->id }}" {{ request('dropoff_point_id') == $point->id ? 'selected' : '' }}>
                                {{ $point->name }} ({{ $point->city?->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nút --}}
                <div class="col-md-3 d-flex gap-2">
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
            @if(session('success'))
                <div class="alert alert-success mx-3 mt-3">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger mx-3 mt-3">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-muted small">#</th>
                            <th class="text-muted small">Tuyến xe</th>
                            <th class="text-muted small">Điểm đón</th>
                            <th class="text-muted small">Điểm trả</th>
                            <th class="text-muted small">Giá vé</th>
                            <th class="text-muted small">Ngày tạo</th>
                            <th class="text-muted small text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pointFares as $pointFare)
                            <tr>
                                <td class="text-muted small">
                                    {{ $pointFares->firstItem() + $loop->index }}
                                </td>
                                <td>
                                    <span class="fw-semibold">
                                        {{ $pointFare->route->fromCity->name ?? '---' }}
                                        <i class="bi bi-arrow-right mx-1 text-muted"></i>
                                        {{ $pointFare->route->toCity->name ?? '---' }}
                                    </span>
                                    <span class="d-block small text-muted">({{ $pointFare->route->name ?? 'Tuyến #' . $pointFare->route_id }})</span>
                                </td>
                                <td>
                                    <span class="d-block fw-semibold">{{ $pointFare->pickupPoint->name ?? '---' }}</span>
                                    <span class="d-block small text-muted">{{ $pointFare->pickupPoint->city?->name ?? '---' }}</span>
                                </td>
                                <td>
                                    <span class="d-block fw-semibold">{{ $pointFare->dropoffPoint->name ?? '---' }}</span>
                                    <span class="d-block small text-muted">{{ $pointFare->dropoffPoint->city?->name ?? '---' }}</span>
                                </td>
                                <td class="fw-semibold text-success">
                                    {{ number_format($pointFare->price, 0, ',', '.') }} VNĐ
                                </td>
                                <td class="text-muted small">
                                    {{ $pointFare->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.point_fares.edit', $pointFare->id) }}"
                                       class="btn btn-sm btn-outline-info me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('admin.point_fares.destroy', $pointFare->id) }}"
                                          method="POST"
                                          class="d-inline-block delete-point-fare-form">
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
                                    Chưa có giá vé chặng nào được tìm thấy.
                                </td>
                            </tr>
                        @endforelse
>>>>>>> f2d58eb95d774cf948ca94767c758deb6261785d
                    </tbody>

                </table>
            </div>

<<<<<<< HEAD
=======
            {{-- Pagination --}}
>>>>>>> f2d58eb95d774cf948ca94767c758deb6261785d
            <div class="d-flex justify-content-end mt-3 px-3 pb-3">
                {{ $pointFares->links('pagination::bootstrap-5') }}
            </div>
<<<<<<< HEAD

        </div>
    </div>

=======
        </div>
    </div>
>>>>>>> f2d58eb95d774cf948ca94767c758deb6261785d
</div>
@endsection

@push('scripts')
<<<<<<< HEAD
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Success --}}
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Thành công!',
        text: @json(session('success')),
        showConfirmButton: false,
        timer: 1800
    });
</script>
@endif

{{-- Error --}}
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

{{-- Confirm delete --}}
<script>
document.querySelectorAll('.delete-pointfare-form').forEach(form => {
    form.addEventListener('submit', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: "Hành động này không thể phục hồi!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if(result.isConfirmed){
                form.submit();
            }
        });
    });
});
</script>
@endpush
=======
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Hiển thị thông báo flash (giữ lại từ bản gốc) --}}
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
        document.querySelectorAll('.delete-point-fare-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc muốn xóa giá vé này?',
                    text: "Hành động này không thể hoàn tác!",
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
>>>>>>> f2d58eb95d774cf948ca94767c758deb6261785d
