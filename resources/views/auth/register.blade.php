@extends('layouts.client')

@section('title', 'Đăng ký tài khoản')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm overflow-hidden auth-card">
                <div class="row g-0">
                    <div class="col-md-6 auth-left p-4 d-none d-md-flex flex-column justify-content-center align-items-start">
                        <h2 class="brand mb-1">PolyCoach</h2>
                        <p class="slogan text-white-80 mb-3">Cùng bạn trên mọi nẻo đường</p>
                        <img src="{{ asset('images/bus-login.png') }}" alt="Bus" class="auth-image mb-3">
                        <h5 class="text-white fw-semibold">XE TRUNG CHUYỂN — ĐÓN &amp; TRẢ TẬN NƠI</h5>
                    </div>

                    <div class="col-md-6 auth-right p-4">
                        <h3 class="text-center mb-3">Đăng ký</h3>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Họ và tên</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus placeholder="Nhập họ và tên">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="Nhập email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Nhập mật khẩu">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                                <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required placeholder="Nhập lại mật khẩu">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Đăng ký</button>
                            </div>

                            <p class="text-center mt-3 mb-0">Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập ngay</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
