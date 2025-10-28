{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý Users')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Danh sách Users</h2>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Thêm User mới</a>
    </div>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Họ</th>
                <th>Tên</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Status</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->first_name }}</td>
                    <td>{{ $user->last_name }}</td>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? '-' }}</td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge bg-success">Quản trị viên</span>
                        @else
                            <span class="badge bg-secondary">Người dùng</span>
                        @endif
                    </td>
                    <td>
                        @if($user->status == 1)
                            <span class="badge bg-success">Kích hoạt</span>
                        @else
                            <span class="badge bg-danger">Khóa tài khoản</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Sửa</a>

                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline-block"
                            onsubmit="return confirm('Bạn có chắc muốn xóa user này?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Chưa có user nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-end mt-3">
        {{ $users->links('pagination::bootstrap-4') }}
    </div>
@endsection
