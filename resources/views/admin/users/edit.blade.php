@extends('layouts.admin')

@section('title', 'Chỉnh sửa User')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Chỉnh sửa User</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Họ</label>
                        <input type="text" name="first_name" class="form-control"
                            value="{{ old('first_name', $user->first_name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Tên</label>
                        <input type="text" name="last_name" class="form-control"
                            value="{{ old('last_name', $user->last_name) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label>Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>

                <div class="mb-3">
                    <label>Mật khẩu (để trống nếu không đổi)</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Vai trò</label>
                    <select name="role" class="form-select">
                        <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Người dùng</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Kích hoạt</option>
                        <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Khóa tài khoản</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
@endsection
