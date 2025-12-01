{{-- resources/views/admin/cities/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Thêm thành phố')

@section('content')
<div class="mb-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                <i class="bi bi-geo-alt-fill"></i>
                Thêm thành phố
            </h2>
            <p class="text-light small mb-0">
                Tạo mới thành phố để sử dụng trong cấu hình tuyến xe, điểm đón/trả trong hệ thống PolyCoach.
            </p>
        </div>
    </div>

    {{-- Hiển thị lỗi validate chung --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-1">Đã có lỗi xảy ra:</div>
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form tạo mới --}}
    <div class="card border-0">
        <div class="card-body">
            <form action="{{ route('admin.cities.store') }}" method="POST" class="row g-3">
                @csrf

                {{-- Tên thành phố --}}
                <div class="col-md-6">
                    <label for="name" class="form-label small text-light mb-1">
                        Tên thành phố <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="Ví dụ: Hà Nội, Đà Nẵng, TP. Hồ Chí Minh..."
                        required
                    >
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Mã thành phố --}}
                <div class="col-md-3">
                    <label for="code" class="form-label small text-light mb-1">
                        Mã thành phố <span class="text-light">(tùy chọn)</span>
                    </label>
                    <input
                        type="text"
                        name="code"
                        id="code"
                        value="{{ old('code') }}"
                        class="form-control @error('code') is-invalid @enderror"
                        placeholder="Ví dụ: HN, DN, HCM..."
                    >
                    @error('code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Trạng thái --}}
                <div class="col-md-3">
                    <label for="status" class="form-label small text-light mb-1">Trạng thái</label>
                    <select
                        name="status"
                        id="status"
                        class="form-select @error('status') is-invalid @enderror"
                    >
                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>
                            Hoạt động
                        </option>
                        <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>
                            Ngưng hoạt động
                        </option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nút --}}
                <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('admin.cities.index') }}" class="btn btn-outline-light">
                        Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Lưu thành phố
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection