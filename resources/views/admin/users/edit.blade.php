@extends('layouts.admin')

@section('title', 'Chỉnh sửa User')

@section('content')
<div class="mb-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                <i class="bi bi-person-gear"></i>
                Chỉnh sửa User
            </h2>
            <p class="text-muted small mb-0">
                Cập nhật thông tin tài khoản: họ tên, liên hệ, vai trò và trạng thái.
            </p>
        </div>

        <a href="{{ route('admin.users.index') }}"
           class="btn btn-outline-light d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Quay lại danh sách</span>
        </a>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Hàng 1: Họ / Tên --}}
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Họ</label>
                        <input type="text"
                               name="first_name"
                               class="form-control @error('first_name') is-invalid @enderror"
                               value="{{ old('first_name', $user->first_name) }}"
                               required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tên</label>
                        <input type="text"
                               name="last_name"
                               class="form-control @error('last_name') is-invalid @enderror"
                               value="{{ old('last_name', $user->last_name) }}"
                               required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Hàng 2: Email / Phone --}}
                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="form-label mt-2">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label mt-2">Số điện thoại</label>
                        <input type="text"
                               name="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Hàng 3: Mật khẩu / Nhập lại --}}
                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="form-label mt-2">Mật khẩu (để trống nếu không đổi)</label>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Nếu không nhập, mật khẩu hiện tại sẽ được giữ nguyên.
                        </small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label mt-2">Nhập lại mật khẩu</label>
                        <input type="password"
                               name="password_confirmation"
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
                        <select name="role"
                                class="form-select @error('role') is-invalid @enderror">
                            {{-- value phải trùng enum: admin | user | staff | checker --}}
                            @php
                                $roleOld = old('role', $user->role);
                            @endphp
                            <option value="user" {{ $roleOld === 'user' ? 'selected' : '' }}>
                                Người dùng
                            </option>
                            <option value="admin" {{ $roleOld === 'admin' ? 'selected' : '' }}>
                                Quản trị viên
                            </option>
                            <option value="staff" {{ $roleOld === 'staff' ? 'selected' : '' }}>
                                Nhân viên
                            </option>
                            <option value="checker" {{ $roleOld === 'checker' ? 'selected' : '' }}>
                                Kiểm soát viên
                            </option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label mt-2">Trạng thái</label>
                        @php
                            $statusOld = old('status', (string) $user->status);
                        @endphp
                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror">
                            <option value="1" {{ $statusOld === '1' ? 'selected' : '' }}>
                                Kích hoạt
                            </option>
                            <option value="0" {{ $statusOld === '0' ? 'selected' : '' }}>
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
                        <input type="file"
                               name="image"
                               class="form-control @error('image') is-invalid @enderror"
                               accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Định dạng: JPG, PNG, WEBP. Bỏ trống nếu muốn giữ ảnh cũ.
                        </small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label mt-2 d-block">Ảnh hiện tại</label>
                        @if($user->image)
                            <img src="{{ asset('storage/' . $user->image) }}"
                                 alt="Avatar"
                                 class="img-thumbnail rounded-circle"
                                 width="80">
                        @else
                            <span class="text-muted small">Chưa có ảnh đại diện</span>
                        @endif
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Cập nhật
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light">
                        Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection