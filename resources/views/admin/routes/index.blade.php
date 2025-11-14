@extends('layouts.admin')

@section('title', 'Quản lý Tuyến đường')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Quản lý danh sách tuyến đường</h2>
        <a href="{{ route('admin.routes.create') }}" class="btn btn-success">Thêm tuyến mới</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Điểm đi</th>
                <th>Điểm đến</th>
                <th>Quãng đường (km)</th>
                <th>Thời gian dự kiến</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($routes as $route)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $route->diem_di }}</td>
                    <td>{{ $route->diem_den }}</td>
                    <td>{{ $route->quang_duong }}</td>
                   <td>{{ $route->thoi_gian_du_kien }}</td>
                    <td>
                        @if($route->trang_thai == 1)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-secondary">Tạm ngưng</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.routes.show', $route->id) }}" class="btn btn-sm btn-info">Xem</a>
                        <a href="{{ route('admin.routes.edit', $route->id) }}" class="btn btn-sm btn-warning">Sửa</a>

                        <form action="{{ route('admin.routes.destroy', $route->id) }}" method="POST" class="d-inline-block"
                            onsubmit="return confirm('Bạn có chắc muốn xóa tuyến này?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Chưa có tuyến nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-end mt-3">
        {{ $routes->links('pagination::bootstrap-4') }}
    </div>
@endsection