{{-- resources/views/admin/tickets/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý Vé')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Danh sách vé</h2>
        <a href="{{ route('admin.tickets.create') }}" class="btn btn-success">Thêm vé mới</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Chuyến</th>
                <th>Người dùng</th>
                <th>Số ghế</th>
                <th>Trạng thái</th>
                <th>Phương thức thanh toán</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $ticket->trip->id ?? 'N/A' }}</td>
                    <td>{{ $ticket->user->name ?? 'N/A' }}</td>
                    <td>{{ $ticket->so_ghe }}</td>
                    <td>
                        @if($ticket->trang_thai == 'pending')
                            <span class="badge bg-warning text-dark">Chờ thanh toán</span>
                        @elseif($ticket->trang_thai == 'paid')
                            <span class="badge bg-success">Đã thanh toán</span>
                        @else
                            <span class="badge bg-danger">Hủy</span>
                        @endif
                    </td>
                    <td>{{ $ticket->phuong_thuc_thanh_toan }}</td>
                    <td>
                        <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="btn btn-sm btn-info">Xem</a>

                        <a href="{{ route('admin.tickets.edit', $ticket->id) }}" class="btn btn-sm btn-warning">Sửa</a>

                        <form action="{{ route('admin.tickets.destroy', $ticket->id) }}" method="POST" class="d-inline-block"
                            onsubmit="return confirm('Bạn có chắc muốn xóa vé này?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Xóa</button>
                        </form>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Chưa có vé nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-end mt-3">
        {{ $tickets->links('pagination::bootstrap-4') }}
    </div>
@endsection
