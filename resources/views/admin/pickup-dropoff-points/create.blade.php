{{-- resources/views/admin/pickup-dropoff-points/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Thêm điểm đón / trả')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <div>
        <h2>Thêm điểm đón / trả</h2>
        <p class="text-muted mb-0">Tạo mới điểm đón hoặc trả khách cho tuyến xe.</p>
    </div>
    <a href="{{ route('admin.pickup-dropoff-points.index') }}" class="btn btn-secondary">
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
    <div class="card-body">
        <form action="{{ route('admin.pickup-dropoff-points.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                {{-- Thành phố --}}
                <div class="col-md-6 mb-3">
                    <label for="city_id" class="form-label">
                        Thành phố <span class="text-danger">*</span>
                    </label>
                    <select
                        name="city_id"
                        id="city_id"
                        class="form-select @error('city_id') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Chọn thành phố --</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}"
                                {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('city_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tuyến xe --}}
                <div class="col-md-6 mb-3">
                    <label for="route_id" class="form-label">
                        Tuyến xe <span class="text-danger">*</span>
                    </label>
                    <select
                        name="route_id"
                        id="route_id"
                        class="form-select @error('route_id') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Chọn tuyến --</option>
                        @foreach ($routes as $route)
                            <option value="{{ $route->id }}"
                                {{ old('route_id') == $route->id ? 'selected' : '' }}>
                                {{ $route->fromCity->name ?? '' }} - {{ $route->toCity->name ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('route_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Tên + Thời gian --}}
            <div class="row mb-3">
                <div class="col-md-8 mb-3">
                    <label for="name" class="form-label">
                        Tên điểm đón/trả <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="Ví dụ: Bến xe Mỹ Đình, Ngã tư ABC..."
                        required
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="time" class="form-label">
                        Thời gian (ví dụ: 08:30)
                    </label>
                    <input
                        type="text"
                        name="time"
                        id="time"
                        value="{{ old('time') }}"
                        class="form-control @error('time') is-invalid @enderror"
                        placeholder="08:30"
                    >
                    @error('time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Địa chỉ --}}
            <div class="mb-3">
                <label for="address" class="form-label">Địa chỉ</label>
                <input
                    type="text"
                    name="address"
                    id="address"
                    value="{{ old('address') }}"
                    class="form-control @error('address') is-invalid @enderror"
                    placeholder="Số nhà, tên đường, phường/xã..."
                >
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Loại + Trạng thái --}}
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label for="type" class="form-label">
                        Loại điểm <span class="text-danger">*</span>
                    </label>
                    <select
                        name="type"
                        id="type"
                        class="form-select @error('type') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Chọn loại --</option>
                        <option value="pickup"  {{ old('type') === 'pickup' ? 'selected' : '' }}>Điểm đón</option>
                        <option value="dropoff" {{ old('type') === 'dropoff' ? 'selected' : '' }}>Điểm trả</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 d-flex align-items-center mt-4">
                    <div class="form-check">
                        <input
                            type="checkbox"
                            name="active"
                            id="active"
                            value="1"
                            class="form-check-input"
                            {{ old('active', 1) ? 'checked' : '' }}
                        >
                        <label class="form-check-label" for="active">
                            Hoạt động
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.pickup-dropoff-points.index') }}" class="btn btn-outline-secondary">
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
