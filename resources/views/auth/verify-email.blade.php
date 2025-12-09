@extends('layouts.client')

@section('title', 'Xác minh email')

@section('content')
<div class="auth-wrapper d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-8">
                <div class="card shadow-sm p-4 rounded-4 border-0">

                    <!-- Title -->
                    <h3 class="text-center mb-3 fw-bold">Xác minh email</h3>

                    <p class="text-muted text-center mb-4" style="font-size: 0.95rem;">
                        Cảm ơn bạn đã đăng ký! Trước khi bắt đầu, vui lòng kiểm tra email của bạn và nhấn vào liên kết xác minh.
                        Nếu bạn không nhận được email, bạn có thể yêu cầu gửi lại.
                    </p>

                    <!-- Status message -->
                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success py-2 mb-4">
                            Một liên kết xác minh mới đã được gửi đến email bạn dùng khi đăng ký.
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mt-4">

                        <!-- Resend verification -->
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg">
                                Gửi lại email xác minh
                            </button>
                        </form>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="btn btn-link text-muted text-decoration-none fw-semibold"
                                style="font-size: 0.9rem;"
                            >
                                Đăng xuất
                            </button>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
.auth-wrapper {
    min-height: calc(100vh - 160px);
    padding-top: 2rem;
    padding-bottom: 2rem;
    background: #f5f7fa;
}
</style>
