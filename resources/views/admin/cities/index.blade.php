{{-- resources/views/admin/cities/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý thành phố')

@section('content')
<div class="mb-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                <i class="bi bi-geo-alt-fill"></i>
                Danh sách thành phố
            </h2>
            <p class="text-muted small mb-0">
                Quản lý danh sách thành phố phục vụ cấu hình tuyến xe và điểm đón/trả.
            </p>
        </div>

        <a href="{{ route('admin.cities.create') }}"
           class="btn btn-primary d-flex align-items-center gap-1 shadow-sm">
            <i class="bi bi-plus-circle"></i>
            <span>Thêm thành phố</span>
        </a>
    </div>

    {{-- Filter Box --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.cities.index') }}" class="row g-3 align-items-end">

                {{-- Search --}}
                <div class="col-md-5">
                    <label for="search" class="form-label small text-light">Tìm kiếm</label>
                    <input type="text" name="search" id="search"
                           value="{{ request('search') }}"
                           class="form-control"
                           placeholder="Nhập tên hoặc mã thành phố...">
                </div>

                {{-- Status --}}
                <div class="col-md-3">
                    <label for="status" class="form-label small text-light">Trạng thái</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ngưng hoạt động</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit"
                            class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-search"></i> Lọc
                    </button>

                    <a href="{{ route('admin.cities.index') }}"
                       class="btn btn-outline-light flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-arrow-repeat"></i> Đặt lại
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-dark text-light">
                        <tr>
                            <th class="small">#</th>
                            <th class="small">Tên thành phố</th>
                            <th class="small">Mã</th>
                            <th class="small">Trạng thái</th>
                            <th class="small">Ngày tạo</th>
                            <th class="small text-end">Hành động</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse ($cities as $city)
                        <tr>
                            <td class="text-muted small">
                                {{ $cities->firstItem() + $loop->index }}
                            </td>

                            <td class="fw-semibold">{{ $city->name }}</td>

                            <td>
                                @if($city->code)
                                    <span class="badge bg-secondary-subtle text-dark border border-primary-subtle">
                                        {{ $city->code }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            <td>
                                @if ($city->status == 1)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        <i class="bi bi-circle-fill me-1" style="font-size: 0.55rem"></i>
                                        Hoạt động
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                        <i class="bi bi-slash-circle me-1"></i>
                                        Ngưng hoạt động
                                    </span>
                                @endif
                            </td>

                            <td class="text-muted small">
                                {{ $city->created_at?->format('d/m/Y H:i') ?? '—' }}
                            </td>

                            <td class="text-end">

                                <a href="{{ route('admin.cities.edit', $city->id) }}"
                                   class="btn btn-sm btn-outline-info me-1">
                                   <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('admin.cities.destroy', $city->id) }}"
                                      method="POST"
                                      class="d-inline-block delete-city-form">
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
                            <td colspan="6" class="text-center py-4 text-muted">
                                Không có thành phố nào.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>
            </div>

            <div class="d-flex justify-content-end mt-3 px-3 pb-3">
                {{ $cities->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
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
document.querySelectorAll('.delete-city-form').forEach(form => {
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
