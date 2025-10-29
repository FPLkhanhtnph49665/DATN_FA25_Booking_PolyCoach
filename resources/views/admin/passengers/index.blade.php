{{-- resources/views/admin/passengers/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý Hành khách')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Quản lý danh sách hành khách</h2>
    <a href="#" class="btn btn-primary">Thêm hành khách mới</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<a href="#" class="btn btn-secondary mb-3">Thùng rác</a>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Tên hành khách</th>
            <th>Số điện thoại</th>
            <th>Tuổi</th>
            <th>Ghế</th>
            <th>Vé liên quan</th>
            <th>Trạng thái vé</th>
            <th>Chuyến</th>
            <th>Tuyến</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($passengers as $passenger)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $passenger->name }}</td>
            <td>{{ $passenger->phone ?? '-' }}</td>
            <td>{{ $passenger->age ?? '-' }}</td>
            <td>{{ $passenger->seat_label }}</td>
            <td>{{ optional($passenger->ticket->user)->full_name ?? 'N/A' }}</td>
            <td>{!! $passenger->ticket->trang_thai_label ?? '-' !!}</td>
            <td>{{ optional($passenger->trip)->ngay_khoi_hanh?->format('d/m/Y') ?? '-' }}</td>
            <td>
                {{ optional($passenger->trip->route)->diem_di ?? '-' }} -
                {{ optional($passenger->trip->route)->diem_den ?? '-' }}
            </td>
            <td>
                <a href="#" class="btn btn-sm btn-info">Xem</a>
                <a href="#" class="btn btn-sm btn-warning">Sửa</a>
                <form action="#" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa hành khách này?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Xóa</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="10" class="text-center">Chưa có hành khách nào</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- Pagination --}}
<div class="d-flex justify-content-end mt-3">
    {{ $passengers->links('pagination::bootstrap-4') }}
</div>
@endsection
