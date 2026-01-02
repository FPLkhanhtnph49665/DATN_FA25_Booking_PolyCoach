@extends('layouts.admin')

@section('title', 'Thêm người dùng mới')

@section('content')
    <div class="mb-4">
        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                    <i class="bi bi-person-plus-fill"></i>
                    Thêm người dùng mới
                </h2>
                <p class="text-white small mb-0">
                    Tạo tài khoản người dùng mới cho hệ thống PolyCoach.
                </p>
            </div>
        </div>

        {{-- Form --}}
        <div class="card border-0">
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Hàng 1: Họ / Tên --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Họ</label>
                            <input type="text" name="first_name"
                                class="form-control @error('first_name') is-invalid @enderror"
                                value="{{ old('first_name') }}" placeholder="VD: Nguyễn">
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tên</label>
                            <input type="text" name="last_name"
                                class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}"
                                placeholder="VD: Văn A">
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Hàng 2: Email / Phone --}}
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label mt-2">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="name@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label mt-2">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}" placeholder="VD: 0901234567">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Hàng 3: Mật khẩu / Nhập lại --}}
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label mt-2">Mật khẩu</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Tối thiểu 8 ký tự, nên có chữ hoa, chữ thường, số và ký tự đặc biệt.
                            </small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label mt-2">Nhập lại mật khẩu</label>
                            <input type="password" name="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Hàng 4: Role / Status --}}
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label mt-2">Vai trò</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror">
                                {{-- value phải trùng enum: admin | user | staff | checker --}}
                                <option value="user" {{ old('role', 'user') === 'user' ? 'selected' : '' }}>
                                    Người dùng
                                </option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                    Quản trị viên
                                </option>
                                <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>
                                    Nhân viên
                                </option>
                                <option value="checker" {{ old('role') === 'checker' ? 'selected' : '' }}>
                                    Kiểm soát viên
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label mt-2">Trạng thái</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="1" {{ old('status', '1') === '1' ? 'selected' : '' }}>
                                    Kích hoạt
                                </option>
                                <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>
                                    Khóa tài khoản
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Hàng 5: Ảnh đại diện --}}
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label mt-2">Ảnh đại diện</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                                accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Định dạng: JPG, PNG, WEBP. Kích thước tối đa khoảng 2MB (tuỳ cấu hình).
                            </small>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light">
                            Hủy bỏ
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Lưu người dùng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
