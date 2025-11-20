@extends('layouts.admin')

@section('title', 'Thêm thành phố')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <div>
        <h2>Thêm thành phố</h2>
        <p class="text-muted mb-0">Tạo mới thành phố để sử dụng trong hệ thống.</p>
    </div>
    <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">
        ← Quay lại
    </a>
</div>

{{-- Hiển thị lỗi validate chung (nếu muốn) --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Đã có lỗi xảy ra:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>- {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.cities.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">
                    Tên thành phố <span class="text-danger">*</span>
                </label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror"
                    placeholder="Ví dụ: Hà Nội, TP. Hồ Chí Minh..."
                    required
                >
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="code" class="form-label">
                    Mã thành phố (tuỳ chọn)
                </label>
                <input
                    type="text"
                    name="code"
                    id="code"
                    value="{{ old('code') }}"
                    class="form-control @error('code') is-invalid @enderror"
                    placeholder="Ví dụ: HN, HCM..."
                >
                @error('code')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input
                    type="checkbox"
                    name="status"
                    id="status"
                    value="1"
                    class="form-check-input"
                    {{ old('status', 1) ? 'checked' : '' }}
                >
                <label class="form-check-label" for="status">
                    Hoạt động
                </label>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.cities.index') }}" class="btn btn-outline-secondary">
                    Hủy
                </a>
                <button type="submit" class="btn btn-primary">
                    Lưu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
