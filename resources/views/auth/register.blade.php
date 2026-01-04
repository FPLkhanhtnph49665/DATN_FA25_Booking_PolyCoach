@extends('layouts.client')

@section('title', 'Đăng ký tài khoản')

@section('content')
    <div class="auth-wrapper d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm overflow-hidden auth-card">
                        <div class="row g-0">
                            <div class="col-md-6 auth-left p-4 d-none d-md-flex flex-column justify-content-center">
                                <!-- Logo & Slogan -->
                                <div class="mb-4">
                                    <h2 class="brand fw-bold text-danger mb-1"
                                        style="font-size: 2.3rem; letter-spacing: .5px;">
                                        PolyCoach
                                    </h2>
                                    <p class="slogan text-white-80 mb-0" style="font-size: 1.1rem;">
                                        Cùng bạn trên mọi nẻo đường
                                    </p>
                                </div>

                                <!-- Ảnh minh họa -->
                                <div class="w-100 d-flex justify-content-center mb-4">
                                    <img src="{{ asset('add_to_login.png') }}" alt="Bus"
                                        class="auth-image rounded-4 shadow-lg"
                                        style="width: 88%; object-fit: cover; border: 1px solid rgba(255,255,255,0.12);">
                                </div>

                                <!-- Tiêu đề -->
                                <h5 class="text-danger fw-semibold mt-2"
                                    style="font-size: 1.15rem; letter-spacing: 0.6px; text-transform: uppercase;">
                                    Xe trung chuyển — Đón &amp; trả tận nơi
                                </h5>

                                <p class="text-white-70 mt-1" style="font-size: .95rem; max-width: 90%;">
                                    Dịch vụ trung chuyển tiện lợi, hiện đại – đưa đón tận nơi nhanh chóng và an toàn.
                                </p>

                            </div>
                            <div class="col-md-6 auth-right p-4">
                                <h3 class="text-center mb-3">Đăng ký</h3>

                                <form method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">Họ và tên đệm</label>
                                        <input id="first_name" type="text"
                                            class="form-control @error('first_name') is-invalid @enderror" name="first_name"
                                            value="{{ old('first_name') }}" autofocus placeholder="Nhập họ và tên đệm">
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Tên</label>
                                        <input id="last_name" type="text"
                                            class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                                            value="{{ old('last_name') }}" placeholder="Nhập tên">
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" placeholder="Nhập email">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Mật khẩu</label>
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            placeholder="Nhập mật khẩu">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                                        <input id="password_confirmation" type="password"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            name="password_confirmation" placeholder="Nhập lại mật khẩu">
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">Đăng ký</button>
                                    </div>

                                    <p class="text-center mt-3 mb-0">
                                        Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập ngay</a>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    .auth-wrapper {
        min-height: calc(100vh - 180px);
        /* 180px = header + footer, chỉnh lên/xuống nếu cần */
        padding-top: 2rem;
        padding-bottom: 2rem;
    }

    .auth-card {
        max-width: 900px;
        margin: 0 auto;
    }
</style>
