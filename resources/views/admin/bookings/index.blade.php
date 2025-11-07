{{-- resources/views/admin/bookings/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý Booking')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Danh sách Booking</h2>
    {{-- <a href="{{ route('admin.bookings.create') }}" class="btn btn-success">Thêm Booking mới</a> --}}
</div>

<form method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Tìm theo tên khách hàng hoặc Trip ID" value="{{ request('search') }}">
        <button class="btn btn-primary">Tìm kiếm</button>
    </div>
</form>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Khách hàng</th>
            <th>Trip ID</th>
            <th>Số ghế</th>
            <th>Trạng thái</th>
            <th>Phương thức thanh toán</th>
            <th>Ngày đặt</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bookings as $booking)
        <tr>
            <td>{{ $booking->id }}</td>
            <td>{{ $booking->user->full_name ?? '-' }}</td>
            <td>{{ $booking->trip->id ?? '-' }}</td>
            <td>{{ $booking->so_ghe }}</td>
            <td>{{ ucfirst($booking->trang_thai) }}</td>
            <td>{{ $booking->phuong_thuc_thanh_toan }}</td>
            <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
            <td>
                <a href="#" class="btn btn-sm btn-info">Xem</a>
                {{-- Thêm Edit/Delete nếu cần --}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div>
    {{ $bookings->withQueryString()->links() }}
</div>
@endsection
