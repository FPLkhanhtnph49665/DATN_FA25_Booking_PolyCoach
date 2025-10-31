@extends('layouts.admin')

@section('title', 'Thêm User mới')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Thêm User mới</h4>
        </div>
        <div class="card-body">
            {{-- Thêm enctype để upload ảnh --}}
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Họ</label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                        @error('first_name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Tên</label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                        @error('last_name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label>Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>

                <div class="mb-3">
                    <label>Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required>
                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label>Nhập lại mật khẩu</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                    @error('password_confirmation')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Vai trò</label>
                    <select name="role" class="form-select">
                        <option value="customer">Người dùng</option>
                        <option value="admin">Quản trị viên</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="1" selected>Kích hoạt</option>
                        <option value="0">Khóa tài khoản</option>
                    </select>
                </div>

                {{-- Thêm input để upload ảnh đại diện --}}
                <div class="mb-3">
                    <label>Ảnh đại diện</label>
                    <input type="file" name="image" class="form-control">
                    @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-success">Lưu</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
@endsection
