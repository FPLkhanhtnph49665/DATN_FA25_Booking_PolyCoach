@extends('layouts.client')

@section('title', 'Quên mật khẩu')

@section('content')
<div class="auth-wrapper d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm overflow-hidden auth-card">
                    <div class="row g-0">

                        <!-- LEFT -->
                        <div class="col-md-6 auth-left p-4 d-none d-md-flex flex-column justify-content-center">

                            <div class="mb-4">
                                <h2 class="brand mb-1 fw-bold text-danger" style="font-size: 2.4rem; letter-spacing: 1px;">
                                    PolyCoach
                                </h2>
                                <p class="slogan text-white-80 mb-0" style="font-size: 1.1rem;">
                                    Cùng bạn trên mọi nẻo đường
                                </p>
                            </div>

                            <div class="w-100 d-flex justify-content-center mb-4">
                                <img src="{{ asset('add_to_login.png') }}"
                                     alt="Bus"
                                     class="auth-image rounded-4 shadow-lg"
                                     style="width: 90%; object-fit: cover; border: 1px solid rgba(255,255,255,0.12);">
                            </div>

                            <h5 class="text-danger fw-semibold mt-2"
                                style="font-size: 1.2rem; letter-spacing: 0.5px; text-transform: uppercase;">
                                Xe trung chuyển — Đón &amp; trả tận nơi
                            </h5>

                            <p class="text-white-70 mt-2" style="max-width: 90%; font-size: 0.95rem;">
                                Dịch vụ xe trung chuyển hiện đại, tiện lợi, đưa đón hành khách dễ dàng và an toàn.
                            </p>
                        </div>

                        <!-- RIGHT -->
                        <div class="col-md-6 auth-right p-4">
                            <h3 class="text-center mb-3">Quên mật khẩu</h3>

                            <p class="text-muted text-center mb-4" style="font-size: 0.95rem;">
                                Nhập email của bạn và hệ thống sẽ gửi liên kết để đặt lại mật khẩu.
                            </p>

                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input
                                        id="email"
                                        type="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autofocus
                                        placeholder="Nhập email của bạn"
                                    >
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        Gửi liên kết đặt lại mật khẩu
                                    </button>
                                </div>

                                <!-- Back to login -->
                                <p class="text-center mt-3 mb-0">
                                    <a href="{{ route('login') }}">Quay lại đăng nhập</a>
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
