{{-- resources/views/admin/trips/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý Chuyến')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Danh sách chuyến</h2>
    <a href="{{ route('admin.trips.create') }}" class="btn btn-primary">Thêm chuyến mới</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Tuyến</th>
            <th>Xe</th>
            <th>Ngày khởi hành</th>
            <th>Giờ khởi hành</th>
            <th>Ngày đến dự kiến</th>
            <th>Giờ đến dự kiến</th>
            <th>Giá vé</th>
            <th>Ghế còn trống</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($trips as $trip)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ optional($trip->route)->diem_di ?? '-' }} - {{ optional($trip->route)->diem_den ?? '-' }}</td>
            <td>{{ optional($trip->bus)->bien_so ?? '-' }}</td>
            <td>{{ $trip->ngay_khoi_hanh?->format('d/m/Y') ?? '-' }}</td>
            <td>{{ $trip->gio_khoi_hanh_formatted }}</td>
            <td>{{ $trip->ngay_den?->format('d/m/Y') ?? '-' }}</td>
            <td>{{ $trip->gio_den_formatted ?? '-' }}</td>
            <td>{{ number_format($trip->gia_ve, 0, ',', '.') }}₫</td>
            <td>{{ count($trip->available_seats) }} / {{ $trip->bus?->so_ghe ?? 0 }}</td>
            <td>
                @if($trip->trang_thai == 1)
                    <span class="badge bg-success">Hoạt động</span>
                @else
                    <span class="badge bg-secondary">Ngưng</span>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.trips.edit', $trip->id) }}" class="btn btn-sm btn-warning">Sửa</a>

                <form action="{{ route('admin.trips.destroy', $trip->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa chuyến này?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Xóa</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="11" class="text-center">Chưa có chuyến nào</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- Pagination --}}
<div class="d-flex justify-content-end mt-3">
    {{ $trips->links('pagination::bootstrap-4') }}
</div>
@endsection
