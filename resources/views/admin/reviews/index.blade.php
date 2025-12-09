{{-- resources/views/admin/reviews/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý Đánh giá')

@section('content')
<div class="mb-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                <i class="bi bi-star-half"></i>
                Quản lý danh sách đánh giá
            </h2>
            <p class="text-white small mb-0">
                Theo dõi đánh giá của khách hàng cho từng chuyến xe: số sao, nội dung và trạng thái duyệt.
            </p>
        </div>

        <div class="d-flex gap-2">
            {{-- Nếu có màn tạo review tay thì bật route ra --}}
            <a href="#"
               class="btn btn-primary d-flex align-items-center gap-1">
                <i class="bi bi-plus-circle"></i>
                <span>Thêm đánh giá mới</span>
            </a>

            <a href="#"
               class="btn btn-outline-light d-flex align-items-center gap-1">
                <i class="bi bi-trash3"></i>
                <span>Thùng rác</span>
            </a>
        </div>
    </div>

    {{-- Thông báo lỗi nhanh --}}
    @if($errors->any())
        <div class="alert alert-danger py-2 small">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Thông báo thành công --}}
    @if(session('success'))
        <div class="alert alert-success py-2 small">
            {{ session('success') }}
        </div>
    @endif

    {{-- Bộ lọc / tìm kiếm --}}
    <div class="card border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reviews.index') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="search" class="form-label text-white small mb-1">Tìm kiếm</label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        class="form-control"
                        placeholder="Tìm theo tên khách, nội dung đánh giá, mã chuyến..."
                        value="{{ request('search') }}"
                    >
                </div>

                <div class="col-md-3">
                    <label for="status" class="form-label text-white small mb-1">Trạng thái</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Từ chối</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-search"></i>
                        <span>Lọc</span>
                    </button>
                    <a href="{{ route('admin.reviews.index') }}"
                       class="btn btn-outline-light flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-arrow-repeat"></i>
                        <span>Đặt lại</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Bảng danh sách đánh giá --}}
    <div class="card border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-muted small">#</th>
                            <th class="text-muted small">Người dùng</th>
                            <th class="text-muted small">Chuyến</th>
                            <th class="text-muted small">Đánh giá</th>
                            <th class="text-muted small">Nội dung</th>
                            <th class="text-muted small">Trạng thái</th>
                            <th class="text-muted small">Ngày đánh giá</th>
                            <th class="text-muted small text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                <td class="text-muted small">
                                    {{ $reviews->firstItem() + $loop->index }}
                                </td>

                                {{-- Người dùng --}}
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold">
                                            {{ $review->user->full_name ?? $review->user->name ?? 'N/A' }}
                                        </span>
                                        @if(!empty($review->user?->email))
                                            <span class="text-muted small">
                                                {{ $review->user->email }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Chuyến: tuyến + ngày/giờ --}}
                                <td>
                                    @php
                                        $trip = $review->trip ?? null;
                                    @endphp
                                    @if($trip)
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold">
                                                {{ $trip->route->fromCity?->name ?? '-' }}
                                                <span class="text-muted">→</span>
                                                {{ $trip->route->toCity?->name ?? '-' }}
                                            </span>
                                            <span class="text-muted small">
                                                {{ $trip->departure_date?->format('d/m/Y') ?? '-' }}
                                                {{ $trip->departure_time_formatted ?? $trip->departure_time ?? '' }}
                                            </span>
                                            <span class="text-muted small">
                                                Mã chuyến: {{ $trip->ma_chuyen ?? $trip->id }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>

                                {{-- Đánh giá (sao) --}}
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>
                                            {{-- accessor stars: ⭐⭐⭐ --}}
                                            {{ $review->stars }}
                                        </span>
                                        <span class="text-muted small">
                                            {{ number_format($review->rating, 1) }}/5
                                        </span>
                                    </div>
                                </td>

                                {{-- Nội dung --}}
                                <td class="text-muted small">
                                    {{ \Illuminate\Support\Str::limit($review->content, 70) }}
                                </td>

                                {{-- Trạng thái (status: pending | approved | rejected) --}}
                                <td>
                                    @if($review->status === 'pending')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                            <i class="bi bi-hourglass-split me-1"></i>
                                            Chờ duyệt
                                        </span>
                                    @elseif($review->status === 'approved')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Đã duyệt
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Từ chối
                                        </span>
                                    @endif
                                </td>

                                {{-- Ngày đánh giá (dùng accessor formatted_date) --}}
                                <td class="text-muted small">
                                    {{ $review->formatted_date }}
                                </td>

                                {{-- Hành động --}}
                                <td class="text-center">
                                    <a href="#"
                                       class="btn btn-sm btn-outline-info me-1">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="#"
                                       class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('admin.reviews.destroy', $review->id) }}"
                                          method="POST"
                                          class="d-inline-block delete-review-form">
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
                                    Chưa có đánh giá nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3 px-3 pb-3">
                {{ $reviews->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success_toast'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: @json(session('success_toast')),
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
        // SweetAlert2 confirm xóa đánh giá
        document.querySelectorAll('.delete-review-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc muốn xóa đánh giá này?',
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
