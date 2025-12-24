@extends('layouts.client')

@section('title', 'Xác nhận mật khẩu')

@section('content')
<div class="auth-wrapper d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-8">
                <div class="card shadow-sm p-4">

                    <h3 class="text-center mb-3">Xác nhận mật khẩu</h3>

                    <p class="text-muted text-center mb-4" style="font-size: 0.95rem;">
                        Đây là khu vực bảo mật. Vui lòng nhập lại mật khẩu để tiếp tục.
                    </p>

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input
                                id="password"
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password"
                                required
                                placeholder="Nhập mật khẩu của bạn"
                                autocomplete="current-password"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Button -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
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
    min-height: calc(100vh - 180px);
    padding-top: 2rem;
    padding-bottom: 2rem;
}
</style>
