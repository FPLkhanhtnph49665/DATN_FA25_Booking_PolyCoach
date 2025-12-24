@extends('layouts.client')

@section('title', 'Xác nhận mật khẩu')

@section('content')
<div class="auth-wrapper d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <div class="card shadow border-0 rounded-4 p-4">

                    <!-- Title -->
                    <h3 class="text-center mb-2 fw-bold">Xác nhận mật khẩu</h3>

                    <p class="text-muted text-center mb-4">
                        Đây là khu vực bảo mật. Vui lòng nhập lại mật khẩu để tiếp tục.
                    </p>

                    <!-- Alert -->
                    @if (session('status'))
                        <div class="alert alert-success py-2">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Form -->
                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Mật khẩu</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-control form-control-lg @error('password') is-invalid @enderror"
                                placeholder="Nhập mật khẩu của bạn"
                                required
                                autocomplete="current-password"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Button -->
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3">
                                Xác nhận
                            </button>
                        </div>

                    </form>

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
.card {
    background: #ffffff;
}
</style>
