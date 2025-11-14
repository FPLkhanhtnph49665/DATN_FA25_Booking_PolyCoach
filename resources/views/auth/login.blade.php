@extends('layouts.client')

@section('title', 'Đăng nhập tài khoản')

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
                        <h3 class="text-center mb-3">Đăng nhập</h3>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Số điện thoại hoặc Email</label>
                                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus placeholder="Nhập số điện thoại hoặc email">
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

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
                                @endif
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Đăng nhập</button>
                            </div>

                            <p class="text-center mt-3 mb-0">
                                Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
