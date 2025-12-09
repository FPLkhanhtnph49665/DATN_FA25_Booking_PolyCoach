@extends('layouts.admin')

@section('title', 'Quản lý giá vé theo điểm')

@section('content')
<div class="mb-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
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
                    </tbody>

                </table>
            </div>

            <div class="d-flex justify-content-end mt-3 px-3 pb-3">
                {{ $pointFares->links('pagination::bootstrap-5') }}
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
