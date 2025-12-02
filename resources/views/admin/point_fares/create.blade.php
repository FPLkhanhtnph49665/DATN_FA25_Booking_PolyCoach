{{-- resources/views/admin/point_fares/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Thêm giá vé chặng')

@section('content')
<div class="mb-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                <i class="bi bi-currency-dollar"></i>
                Thiết lập Giá vé chặng
            </h2>
            <p class="text-light small mb-0">
                Tạo mới giá vé áp dụng cho một tuyến đường cụ thể.
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
            <form action="{{ route('admin.point_fares.store') }}" method="POST" class="row g-3">
                @csrf

                {{-- 1. TUYẾN XE (MỚI THÊM) --}}
                <div class="col-12">
                    <label for="route_id" class="form-label small text-light mb-1">
                        Thuộc Tuyến xe <span class="text-danger">*</span>
                    </label>
                    <select
                        name="route_id"
                        id="route_id"
                        class="form-select @error('route_id') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Chọn tuyến xe áp dụng --</option>
                        @if(isset($routes))
                            @foreach ($routes as $route)
                                <option 
                                    value="{{ $route->id }}" 
                                    {{ old('route_id') == $route->id ? 'selected' : '' }}
                                >
                                    {{ $route->diem_di }} → {{ $route->diem_den }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('route_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text text-muted small">
                        Chọn tuyến xe mà giá vé này sẽ được áp dụng.
                    </div>
                </div>

                {{-- 2. Điểm đón (Pickup Point) --}}
                <div class="col-md-6">
                    <label for="pickup_point_id" class="form-label small text-light mb-1">
                        Điểm đón (Nơi xuất phát) <span class="text-danger">*</span>
                    </label>
                    <select
                        name="pickup_point_id"
                        id="pickup_point_id"
                        class="form-select @error('pickup_point_id') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Chọn điểm đón --</option>
                        @if(isset($pickupPoints))
                            @foreach ($pickupPoints as $point)
                                <option 
                                    value="{{ $point->id }}" 
                                    {{ old('pickup_point_id') == $point->id ? 'selected' : '' }}
                                >
                                    {{-- Sử dụng ten_diem_don và dia_chi --}}
                                    {{ $point->ten_diem_don }} - {{ $point->dia_chi }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('pickup_point_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 3. Điểm trả (Dropoff Point) --}}
                <div class="col-md-6">
                    <label for="dropoff_point_id" class="form-label small text-light mb-1">
                        Điểm trả (Nơi đến) <span class="text-danger">*</span>
                    </label>
                    <select
                        name="dropoff_point_id"
                        id="dropoff_point_id"
                        class="form-select @error('dropoff_point_id') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Chọn điểm trả --</option>
                        @if(isset($dropoffPoints))
                            @foreach ($dropoffPoints as $point)
                                <option 
                                    value="{{ $point->id }}" 
                                    {{ old('dropoff_point_id') == $point->id ? 'selected' : '' }}
                                >
                                    {{-- Sử dụng ten_diem_tra và dia_chi --}}
                                    {{ $point->ten_diem_tra }} - {{ $point->dia_chi }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('dropoff_point_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 4. Giá vé (Price) --}}
                <div class="col-md-6">
                    <label for="price" class="form-label small text-light mb-1">
                        Giá vé (VND) <span class="text-danger">*</span>
                    </label>
                    <input
                        type="number"
                        name="price"
                        id="price"
                        value="{{ old('price') }}"
                        class="form-control @error('price') is-invalid @enderror"
                        placeholder="Ví dụ: 80000"
                        required
                        min="0"
                    >
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 5. Trạng thái --}}
                <div class="col-md-6">
                    <label for="status" class="form-label small text-light mb-1">Trạng thái</label>
                    <select
                        name="status"
                        id="status"
                        class="form-select @error('status') is-invalid @enderror"
                    >
                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>Ngưng hoạt động</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nút hành động --}}
                <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('admin.point_fares.index') }}" class="btn btn-outline-light">
                        Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Lưu giá vé chặng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection