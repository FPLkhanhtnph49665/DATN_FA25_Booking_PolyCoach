{{-- resources/views/admin/reviews/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý Đánh giá')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Quản lý danh sách đánh giá</h2>
    <a href="#" class="btn btn-success">Thêm đánh giá mới</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<a href="#" class="btn btn-secondary mb-3">Thùng rác</a>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Người dùng</th>
            <th>Chuyến</th>
            <th>Đánh giá (sao)</th>
            <th>Nội dung</th>
            <th>Ngày đánh giá</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($reviews as $review)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $review->user->name ?? 'N/A' }}</td>
            <td>
                #{{ $review->trip->id ?? 'N/A' }}
                {{-- Bạn có thể hiển thị chi tiết: $review->trip->route->diem_di . ' - ' . $review->trip->route->diem_den --}}
            </td>
            <td>
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $review->rating)
                        <span class="text-warning">★</span>
                    @else
                        <span class="text-secondary">★</span>
                    @endif
                @endfor
            </td>
            <td>{{ Str::limit($review->noi_dung, 50) }}</td>
            <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
            <td>
                <a href="#" class="btn btn-sm btn-info">Xem</a>

                <a href="#" class="btn btn-sm btn-warning">Sửa</a>

                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Xóa</button>
                </form>

            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">Chưa có đánh giá nào</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- Pagination --}}
<div class="d-flex justify-content-end mt-3">
        {{ $reviews->links('pagination::bootstrap-4') }}
    </div>
@endsection
