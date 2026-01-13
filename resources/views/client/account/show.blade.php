@extends('layouts.client')

@section('content')

    <style>
        body {
            background-color: #f5f5f5;
        }

        .account-hero {
            background: linear-gradient(135deg, #ff595e 0%, #ff9933 50%, #ff595e 100%);
            color: #fff;
            padding: 24px 0 70px;
            margin-bottom: -40px;
        }

        .account-hero-title {
            font-size: 22px;
            font-weight: 700;
        }

        .account-wrapper {
            margin-top: 40px;
            margin-bottom: 40px;
        }

        .account-sidebar {
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border: 1px solid #eee;
            padding: 10px 0;
        }

        .account-menu-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            font-size: 14px;
            color: #444;
            text-decoration: none;
        }

        .account-menu-item:hover {
            background: #fff7f0;
        }

        .account-menu-item.active {
            background: #ffe0cc;
            font-weight: 600;
            border-left: 3px solid #ff595e;
        }

        .account-menu-icon {
            width: 28px;
            height: 28px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #fff;
        }

        .icon-green {
            background: #00b14f;
        }

        .icon-orange {
            background: #ff595e;
        }

        .icon-blue {
            background: #1e88e5;
        }

        .icon-red {
            background: #f44336;
        }

        .icon-gray {
            background: #9e9e9e;
        }

        .account-main-card {
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border: 1px solid #eee;
            padding: 24px 28px;
        }

        .avatar-wrapper {
            text-align: center;
            margin-bottom: 16px;
        }

        .avatar-wrapper img {
            width: 160px;
            height: 160px;
            border-radius: 999px;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .avatar-upload-btn {
            margin-top: 12px;
            display: inline-block;
            padding: 6px 18px;
            border-radius: 999px;
            border: 1px solid #ff595e;
            color: #ff595e;
            font-size: 13px;
            cursor: pointer;
        }

        .avatar-upload-btn:hover {
            background: #ff595e;
            color: #fff;
        }

        .account-field-label {
            font-size: 14px;
            color: #777;
            width: 140px;
        }

        .account-field-value {
            font-size: 14px;
        }

        .btn-main {
            background-color: #ff595e;
            border-color: #ff595e;
            color: #fff;
            border-radius: 999px;
            padding: 8px 28px;
            font-weight: 600;
        }

        .btn-main:hover {
            background-color: #ff8f26;
            border-color: #ff8f26;
            color: #fff;
        }
    </style>

    @php
        /** @var \App\Models\User $user */
        $user = $user ?? auth()->user();
        $avatarUrl =
            $user && $user->image
                ? asset('storage/' . $user->image)
                : 'https://via.placeholder.com/300x300.png?text=Avatar';
    @endphp

    <div class="container account-wrapper">
        <div class="row">
            {{-- SIDEBAR --}}
            <div class="col-lg-3 mb-3">
                <div class="account-sidebar">
                    <a href="javascript:void(0)" class="account-menu-item">
                        <span class="account-menu-icon icon-green">F</span>
                        <span>PoLyCoachPay</span>
                    </a>
                    <a href="{{ route('client.account.show') }}" class="account-menu-item active">
                        <span class="account-menu-icon icon-orange">
                            <i class="bi bi-person"></i>
                        </span>
                        <span>Thông tin tài khoản</span>
                    </a>
                    <a href="{{ route('client.account.tickets') }}" class="account-menu-item">
                        <span class="account-menu-icon icon-blue">
                            <i class="bi bi-ticket-perforated"></i>
                        </span>
                        <span>Lịch sử mua vé</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="account-menu-item w-100 text-start border-0 bg-transparent">
                            <span class="account-menu-icon icon-gray">
                                <i class="bi bi-box-arrow-right"></i>
                            </span>
                            <span>Đăng xuất</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- MAIN CONTENT --}}
            <div class="col-lg-9">
                <div class="account-main-card">
                    <h5 class="mb-3">Thông tin tài khoản</h5>

                    @if (session('success'))
                        <div class="alert alert-success py-2">{{ session('success') }}</div>
                    @endif

                    {{-- @if ($errors->any())
                        <div class="alert alert-danger py-2">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif --}}

                    <form action="{{ route('client.account.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            {{-- Avatar bên trái --}}
                            <div class="col-md-4">
                                <div class="avatar-wrapper">
                                    {{-- Hiển thị ảnh hiện tại --}}
                                    @if ($user->image)
                                        <img src="{{ asset($user->image) }}" alt="Avatar"
                                            class="@error('image') border border-danger @enderror">
                                    @else
                                        <img src="https://via.placeholder.com/300x300.png?text=Avatar" alt="Avatar"
                                            class="@error('image') border border-danger @enderror">
                                    @endif

                                    <label class="avatar-upload-btn mt-2">
                                        Chọn ảnh
                                        <input type="file" name="image" class="d-none" accept="image/*">
                                    </label>

                                    {{-- Hiển thị lỗi ảnh tại đây --}}
                                    @error('image')
                                        <div class="text-danger small mt-1">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror

                                    <div class="small text-muted mt-1">
                                        Dung lượng tối đa 1MB. Định dạng .JPEG, .PNG
                                    </div>
                                </div>
                            </div>

                            {{-- Thông tin bên phải --}}
                            <div class="col-md-8">
                                <div class="mb-3 row">
                                    <label class="account-field-label col-sm-4 col-form-label">
                                        Họ <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" name="first_name"
                                            class="form-control form-control-sm @error('first_name') is-invalid @enderror"
                                            value="{{ old('first_name', $user->first_name) }}">
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="account-field-label col-sm-4 col-form-label">
                                        Tên <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" name="last_name"
                                            class="form-control form-control-sm @error('last_name') is-invalid @enderror"
                                            value="{{ old('last_name', $user->last_name) }}">
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="account-field-label col-sm-4 col-form-label">
                                        Số điện thoại
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" name="phone"
                                            class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', $user->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="account-field-label col-sm-4 col-form-label">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="email" name="email"
                                            class="form-control form-control-sm @error('email') is-invalid @enderror"
                                            value="{{ old('email', $user->email) }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="account-field-label col-sm-4 col-form-label">
                                        mật khẩu (để trống nếu không đổi)
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="password" name="password"
                                            class="form-control form-control-sm @error('password') is-invalid @enderror">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            Nếu không nhập, mật khẩu hiện tại sẽ được giữ nguyên.
                                        </small>
                                        <input type="password" name="password_confirmation"
                                            class="form-control form-control-sm mt-2 @error('password_confirmation') is-invalid @enderror"
                                            placeholder="Nhập lại mật khẩu">
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-main">
                                        Cập nhật
                                    </button>
                                </div>
                            </div>
                        </div> {{-- row --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
