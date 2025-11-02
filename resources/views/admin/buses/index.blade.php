@extends('layouts.admin')

@section('title', 'Quản lý Xe')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Quản lý danh sách Xe</h2>
        <a href="{{ route('admin.buses.create') }}" class="btn btn-primary">Thêm xe mới</a>
    </div>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Biển số</th>
                <th>Số ghế</th>
                <th>Loại xe</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($buses as $bus)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $bus->bien_so }}</td>
                    <td>{{ $bus->so_ghe }}</td>
                    <td>
    @if ($bus->loai_xe === 'Giường')
        <span class="badge bg-info">Giường nằm</span>
    @elseif ($bus->loai_xe === 'Limousine')
        <span class="badge bg-warning">Limousine</span>
    @else
        <span class="badge bg-secondary">Không xác định</span>
    @endif
</td>
                    <td>
                        @if ($bus->trang_thai == 1)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-danger">Đang bảo dưỡng</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.buses.edit', $bus->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="#" class="btn btn-sm btn-danger">Xóa</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Chưa có xe nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-end mt-3">
        {{ $buses->links('pagination::bootstrap-4') }}
    </div>
@endsection
