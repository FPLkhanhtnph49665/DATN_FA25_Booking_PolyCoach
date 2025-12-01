@extends('layouts.admin')

@section('title', 'Thêm tuyến mới')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Thêm tuyến đường mới</h2>
    <a href="{{ route('admin.routes.index') }}" class="btn btn-secondary">
        ← Quay lại
    </a>
</div>

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
    <div class="card-header">
        <h4>Thông tin tuyến đường</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.routes.store') }}" method="POST">
            @csrf

            <div class="row">
                {{-- Thành phố đi --}}
                <div class="col-md-6 mb-3">
                    <label for="from_city_id" class="form-label">
                        Thành phố đi <span class="text-danger">*</span>
                    </label>
                    <select
                        name="from_city_id"
                        id="from_city_id"
                        class="form-select @error('from_city_id') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Chọn thành phố đi --</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}"
                                {{ old('from_city_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('from_city_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Thành phố đến --}}
                <div class="col-md-6 mb-3">
                    <label for="to_city_id" class="form-label">
                        Thành phố đến <span class="text-danger">*</span>
                    </label>
                    <select
                        name="to_city_id"
                        id="to_city_id"
                        class="form-select @error('to_city_id') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Chọn thành phố đến --</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}"
                                {{ old('to_city_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('to_city_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Quãng đường + Thời gian dự kiến --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="distance" class="form-label">
                        Quãng đường (km) <span class="text-danger">*</span>
                    </label>
                    <input
                        type="number"
                        name="distance"
                        id="distance"
                        class="form-control @error('distance') is-invalid @enderror"
                        value="{{ old('distance') }}"
                        min="1"
                        step="0.1"
                        required
                    >
                    @error('distance')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="estimated_time" class="form-label">
                        Thời gian dự kiến
                        <small class="text-light">(VD: 05:30 hoặc 5 giờ 30 phút)</small>
                    </label>
                    <input
                        type="text"
                        name="estimated_time"
                        id="estimated_time"
                        class="form-control @error('estimated_time') is-invalid @enderror"
                        value="{{ old('estimated_time') }}"
                        placeholder="VD: 05:30 hoặc 5 giờ 30 phút"
                    >
                    @error('estimated_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Trạng thái --}}
            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select
                    name="status"
                    id="status"
                    class="form-select @error('status') is-invalid @enderror"
                >
                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Dừng hoạt động</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.routes.index') }}" class="btn btn-outline-secondary">
                    Hủy
                </a>
                <button type="submit" class="btn btn-success">
                    Lưu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection