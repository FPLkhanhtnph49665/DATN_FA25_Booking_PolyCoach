@extends('layouts.admin')

@section('title', 'Quản lý Tin tức')

@section('content')
<div class="mb-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                <i class="bi bi-newspaper"></i>
                Quản lý tin tức
            </h2>
            <p class="text-white small mb-0">
                Quản lý bài viết tin tức, trạng thái hiển thị, nổi bật và thời gian đăng.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.news.create') }}"
               class="btn btn-primary d-flex align-items-center gap-1">
                <i class="bi bi-plus-circle"></i>
                <span>Thêm tin mới</span>
            </a>
        </div>
    </div>

    {{-- Thông báo lỗi nhanh --}}
    @if($errors->any())
        <div class="alert alert-danger py-2 small">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Bộ lọc --}}
    <div class="card border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('admin.news.index') }}" method="GET"
                  class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="form-label text-white small mb-1">Tìm kiếm</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control"
                           placeholder="Tìm theo tiêu đề...">
                </div>

                <div class="col-md-3">
                    <label class="form-label text-white small mb-1">Danh mục</label>
                    <input type="text"
                           name="category"
                           value="{{ request('category') }}"
                           class="form-control"
                           placeholder="Ví dụ: Khuyến mãi">
                </div>

                <div class="col-md-2">
                    <label class="form-label text-white small mb-1">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="1" {{ request('status')==='1'?'selected':'' }}>Hiển thị</option>
                        <option value="0" {{ request('status')==='0'?'selected':'' }}>Ẩn</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-search"></i> Lọc
                    </button>
                    <a href="{{ route('admin.news.index') }}"
                       class="btn btn-outline-light flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-arrow-repeat"></i> Đặt lại
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
                            <th class="text-muted small">Ảnh</th>
                            <th class="text-muted small">Tiêu đề</th>
                            <th class="text-muted small">Danh mục</th>
                            <th class="text-muted small">Nổi bật</th>
                            <th class="text-muted small">Trạng thái</th>
                            <th class="text-muted small">Ngày đăng</th>
                            <th class="text-muted small text-center">Hoạt động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($news as $item)
                            <tr>
                                <td class="text-muted small">
                                    {{ $news->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    @if($item->thumbnail)
                                        <img src="{{ asset('storage/'.$item->thumbnail) }}"
                                             width="70"
                                             class="rounded">
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>

                                <td class="fw-semibold">
                                    {{ $item->title }}
                                </td>

                                <td>
                                    {{ $item->category }}
                                </td>

                                <td>
                                    @if($item->is_featured)
                                        <span class="badge bg-warning-subtle text-warning border">
                                            Nổi bật
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-dark border">
                                            Thường
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if($item->status)
                                        <span class="badge bg-success-subtle text-success border">
                                            Hiển thị
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border">
                                            Ẩn
                                        </span>
                                    @endif
                                </td>

                                <td class="text-muted small">
                                    {{ $item->published_at?->format('d/m/Y H:i') ?? '—' }}
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('admin.news.edit', $item->id) }}"
                                       class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="{{ route('admin.news.show', $item->id) }}"
                                        class="btn btn-sm btn-outline-info me-1"
                                     title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <form action="{{ route('admin.news.destroy', $item->id) }}"
                                          method="POST"
                                          class="d-inline-block delete-news-form">
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
                                    Chưa có tin tức nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3 px-3 pb-3">
                {{ $news->links('pagination::bootstrap-4') }}
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
document.querySelectorAll('.delete-news-form').forEach(form => {
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Bạn có chắc muốn xóa tin này?',
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
