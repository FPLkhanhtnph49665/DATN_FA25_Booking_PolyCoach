@extends('layouts.admin')

@section('title', 'Thêm giá vé mới')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3">Thêm giá vé mới</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.point_fares.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="route_id" class="form-label">Tuyến xe <span class="text-danger">*</span></label>
            <select name="route_id" id="route_id" class="form-select" required>
                <option value="">-- Chọn tuyến xe --</option>
                @foreach($routes as $route)
                    <option value="{{ $route->id }}" {{ old('route_id') == $route->id ? 'selected' : '' }}>
                        {{ $route->fromCity->name ?? '---' }} → {{ $route->toCity->name ?? '---' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="pickup_point_id" class="form-label">Điểm đón <span class="text-danger">*</span></label>
            <select name="pickup_point_id" id="pickup_point_id" class="form-select" required>
                <option value="">-- Chọn điểm đón --</option>
                @foreach($points->where('type', 'pickup') as $point)
                    <option value="{{ $point->id }}" {{ old('pickup_point_id') == $point->id ? 'selected' : '' }}>
                        {{ $point->name }} - {{ $point->address }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="dropoff_point_id" class="form-label">Điểm trả <span class="text-danger">*</span></label>
            <select name="dropoff_point_id" id="dropoff_point_id" class="form-select" required>
                <option value="">-- Chọn điểm trả --</option>
                @foreach($points->where('type', 'dropoff') as $point)
                    <option value="{{ $point->id }}" {{ old('dropoff_point_id') == $point->id ? 'selected' : '' }}>
                        {{ $point->name }} - {{ $point->address }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá vé (VNĐ) <span class="text-danger">*</span></label>
            <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" min="0" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select name="status" id="status" class="form-select">
                <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>Ngưng hoạt động</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.point_fares.index') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">Lưu giá vé</button>
        </div>
    </form>
</div>
@endsection
