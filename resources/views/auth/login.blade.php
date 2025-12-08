@extends('layouts.client')

@section('title', 'Đăng nhập tài khoản')

@section('content')
    <div class="auth-wrapper d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm overflow-hidden auth-card">
                        <div class="row g-0">
                            <div class="col-md-6 auth-left p-4 d-none d-md-flex flex-column justify-content-center">

        <!-- Logo + Slogan -->
        <div class="mb-4">
            <h2 class="brand mb-1 fw-bold text-danger" style="font-size: 2.4rem; letter-spacing: 1px;">
                PolyCoach
            </h2>
            <p class="slogan text-white-80 mb-0" style="font-size: 1.1rem;">
                Cùng bạn trên mọi nẻo đường
            </p>
        </div>

        <!-- Ảnh minh họa -->
        <div class="w-100 d-flex justify-content-center mb-4">
            <img src="{{ asset('add_to_login.png') }}" alt="Bus" class="auth-image rounded-4 shadow-lg"
                style="width: 90%; object-fit: cover; border: 1px solid rgba(255,255,255,0.12);">
        </div>

        <!-- Tiêu đề công nghệ -->
        <h5 class="text-danger fw-semibold mt-2" style="font-size: 1.25rem; letter-spacing: 0.5px; text-transform: uppercase;">
            Xe trung chuyển — Đón &amp; trả tận nơi
        </h5>

        <p class="text-white-70 mt-2" style="max-width: 90%; font-size: 0.95rem;">
            Dịch vụ xe trung chuyển hiện đại, tiện lợi, đưa đón hành khách dễ dàng và an toàn.
        </p>

        </div>
                            <div class="col-md-6 auth-right p-4">
                                <h3 class="text-center mb-3">Đăng nhập</h3>

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Số điện thoại hoặc Email</label>
                                        <input
                                            id="email"
                                            type="text"
                                            class="form-control @error('email') is-invalid @enderror"
                                            name="email"
                                            value="{{ old('email') }}"
                                            required
                                            autofocus
                                            placeholder="Nhập số điện thoại hoặc email"
                                        >
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Mật khẩu</label>
                                        <input
                                            id="password"
                                            type="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            name="password"
                                            required
                                            placeholder="Nhập mật khẩu"
                                        >
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="remember"
                                                id="remember"
                                                {{ old('remember') ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label" for="remember">
                                                Ghi nhớ đăng nhập
                                            </label>
                                        </div>

                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
                                        @endif
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">Đăng nhập</button>
                                    </div>

                                    <p class="text-center mt-3 mb-0">
                                        Chưa có tài khoản?
                                        <a href="{{ route('register') }}">Đăng ký ngay</a>
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
    padding-top: 2rem;
    padding-bottom: 2rem;
}

</style>
