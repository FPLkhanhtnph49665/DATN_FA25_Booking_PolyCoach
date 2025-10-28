@extends('layouts.admin')

@section('title', 'Quản lý Tuyến đường')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Danh sách tuyến đường</h2>
        <a href="#" class="btn btn-primary">Thêm tuyến mới</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="#" class="btn btn-secondary mb-3">Thùng rác</a>

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
                    <td>{{ \Carbon\Carbon::parse($route->thoi_gian_du_kien)->format('H:i:s') }}</td>
                    <td>
                        @if($route->trang_thai == 1)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-secondary">Tạm ngưng hoạt động</span>
                        @endif
                    </td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info">Xem</a>
                        
                        <a href="#" class="btn btn-sm btn-warning">Sửa</a>

                        <form action="" method="POST" class="d-inline-block"
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

    {{-- Pagination --}}
    <div class="d-flex justify-content-end mt-3">
        {{ $routes->links('pagination::bootstrap-4') }}
    </div>
@endsection
