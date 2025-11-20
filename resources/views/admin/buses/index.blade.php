{{-- resources/views/admin/buses/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý Xe')

@section('content')
<div class="mb-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                <i class="bi bi-bus-front-fill"></i>
                Quản lý danh sách Xe
            </h2>
            <p class="text-muted small mb-0">
                Theo dõi đội xe: biển số, số ghế, loại xe và trạng thái hoạt động trong hệ thống PolyCoach.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.buses.create') }}"
               class="btn btn-primary d-flex align-items-center gap-1">
                <i class="bi bi-plus-circle"></i>
                <span>Thêm xe mới</span>
            </a>
        </div>
    </div>

    {{-- Hiển thị lỗi nhanh --}}
    @if($errors->any())
        <div class="alert alert-danger py-2 small">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Bảng danh sách xe --}}
    <div class="card border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-muted small">#</th>
                            <th class="text-muted small">Biển số</th>
                            <th class="text-muted small">Số ghế</th>
                            <th class="text-muted small">Loại xe</th>
                            <th class="text-muted small">Trạng thái</th>
                            <th class="text-muted small text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($buses as $bus)
                            <tr>
                                <td class="text-muted small">
                                    {{ $buses->firstItem() + $loop->index }}
                                </td>
                                <td class="fw-semibold">
                                    {{ $bus->plate_number }}
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-dark border border-primary-subtle">
                                        {{ $bus->seat_count }} ghế
                                    </span>
                                </td>
                                <td>
                                    @if ($bus->type === 'Seat')
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                            <i class="bi bi-person-lines-fill me-1"></i> Seat
                                        </span>
                                    @elseif ($bus->type === 'Sleeper')
                                        <span class="badge bg-info-subtle text-info border border-info-subtle">
                                            <i class="bi bi-moon-stars me-1"></i> Sleeper
                                        </span>
                                    @elseif ($bus->type === 'Limousine')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                            <i class="bi bi-gem me-1"></i> Limousine
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ $bus->type }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($bus->status == 1)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 0.55rem;"></i>
                                            Hoạt động
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                            <i class="bi bi-tools me-1"></i>
                                            Bảo dưỡng
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.buses.edit', $bus->id) }}"
                                       class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('admin.buses.destroy', $bus->id) }}"
                                          method="POST"
                                          class="d-inline-block delete-bus-form">
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
                                    Chưa có xe nào trong hệ thống.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3 px-3 pb-3">
                {{ $buses->links('pagination::bootstrap-4') }}
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

    <script>
        // SweetAlert2 confirm xóa xe
        document.querySelectorAll('.delete-bus-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc muốn xóa xe này?',
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
