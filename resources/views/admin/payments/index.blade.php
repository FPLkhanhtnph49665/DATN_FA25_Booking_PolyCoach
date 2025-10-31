{{-- resources/views/admin/payments/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý Thanh toán')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Quản lý danh sách thanh toán</h2>
        <a href="#" class="btn btn-success">Thêm thanh toán mới</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="#" class="btn btn-secondary mb-3">Thùng rác</a>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Vé</th>
                <th>Người dùng</th>
                <th>Số tiền</th>
                <th>Phương thức</th>
                <th>Trạng thái</th>
                <th>Ngày thanh toán</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>#{{ $payment->ticket->id ?? 'N/A' }}</td>
                    <td>{{ $payment->user->name ?? 'N/A' }}</td>
                    <td>{{ number_format($payment->so_tien, 0, ',', '.') }}₫</td>
                    <td>{{ $payment->phuong_thuc }}</td>
                    <td>
                        @if($payment->trang_thai == 'pending')
                            <span class="badge bg-warning text-dark">Chờ xử lý</span>
                        @elseif($payment->trang_thai == 'success')
                            <span class="badge bg-success">Thành công</span>
                        @else
                            <span class="badge bg-danger">Thất bại</span>
                        @endif
                    </td>
                    <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info">Xem</a>

                        <a href="#" class="btn btn-sm btn-warning">Sửa</a>

                        <form action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST" class="d-inline-block"
                            onsubmit="return confirm('Bạn có chắc muốn xóa thanh toán này?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Xóa</button>
                        </form>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Chưa có thanh toán nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-end mt-3">
        {{ $payments->links('pagination::bootstrap-4') }}
    </div>
@endsection
