{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý Users')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Danh sách Users</h2>
    <a href="{{ route('admin.users.create') }}" class="btn btn-success">Thêm User mới</a>
</div>
<form action="{{ route('admin.users.index') }}" method="GET" class="mb-4">
    <div class="row g-2 align-items-center">
        <div class="col-md-3">
            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control"
                placeholder="Tìm theo tên, email, mã user...">
        </div>
        <div class="col-md-2">
            <select name="role" class="form-select">
                <option value="">-- Vai trò --</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Người dùng</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">-- Trạng thái --</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Kích hoạt</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Khóa</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search"></i> Tìm kiếm
            </button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary w-100">
                <i class="bi bi-arrow-repeat"></i> Làm mới
            </a>
        </div>
    </div>
</form>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: '{{ session('error') }}',
            showConfirmButton: true
        });
    </script>
@endif

<div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Mã User</th>
                <th>Họ</th>
                <th>Tên</th>
                <th>Full Name</th>
                <th>Ảnh</th>
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
                    <td>{{ $user->user_code }}</td>
                    <td>{{ $user->first_name }}</td>
                    <td>{{ $user->last_name }}</td>
                    <td>{{ $user->full_name }}</td>
                    <td>
                        <img src="{{ $user->image ? asset('storage/' . $user->image) : asset('default-avatar.png') }}"
                             alt="Avatar" class="img-thumbnail rounded-circle" width="50">
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->masked_phone ?? '-' }}</td>
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
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning mb-1">Sửa</a>

                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline-block delete-user-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger mb-1">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">Chưa có user nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="d-flex justify-content-end mt-3">
    {{ $users->links('pagination::bootstrap-4') }}
</div>

<script>
    // SweetAlert2 confirm xóa
    document.querySelectorAll('.delete-user-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Bạn có chắc muốn xóa user này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
