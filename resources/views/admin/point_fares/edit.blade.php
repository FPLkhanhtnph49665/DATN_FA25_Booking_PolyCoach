@extends('layouts.admin')

@section('title', 'Chỉnh sửa giá vé')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3">Chỉnh sửa giá vé</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.point_fares.update', $pointFare->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- TUYẾN XE (ROUTE) - KHÔNG ĐỔI --}}
        <div class="mb-3">
            <label for="route_id" class="form-label">Tuyến xe <span class="text-danger">*</span></label>
            <select name="route_id" id="route_id" class="form-select @error('route_id') is-invalid @enderror" required>
                <option value="">-- Chọn tuyến xe --</option>
                @foreach($routes as $route)
                    <option value="{{ $route->id }}" 
                        {{ old('route_id', $pointFare->route_id) == $route->id ? 'selected' : '' }}>
                        {{ $route->fromCity->name ?? '---' }} → {{ $route->toCity->name ?? '---' }}
                    </option>
                @endforeach
            </select>
            @error('route_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- ĐIỂM ĐÓN (PICKUP POINT) - ĐÃ SỬA ĐỔI --}}
        <div class="mb-3">
            <label for="pickup_point_id" class="form-label">Điểm đón <span class="text-danger">*</span></label>
            <select name="pickup_point_id" id="pickup_point_id" class="form-select @error('pickup_point_id') is-invalid @enderror" required>
                <option value="">-- Chọn điểm đón --</option>
                {{-- Lọc từ biến $points (chung) với type = 'pickup' --}}
                @foreach($points->where('type', 'pickup') as $point)
                    <option value="{{ $point->id }}" 
                        {{ old('pickup_point_id', $pointFare->pickup_point_id) == $point->id ? 'selected' : '' }}>
                        {{ $point->name }} - {{ $point->address }}
                    </option>
                @endforeach
            </select>
            @error('pickup_point_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- ĐIỂM TRẢ (DROPOFF POINT) - ĐÃ SỬA ĐỔI --}}
        <div class="mb-3">
            <label for="dropoff_point_id" class="form-label">Điểm trả <span class="text-danger">*</span></label>
            <select name="dropoff_point_id" id="dropoff_point_id" class="form-select @error('dropoff_point_id') is-invalid @enderror" required>
                <option value="">-- Chọn điểm trả --</option>
                {{-- Lọc từ biến $points (chung) với type = 'dropoff' --}}
                @foreach($points->where('type', 'dropoff') as $point)
                    <option value="{{ $point->id }}" 
                        {{ old('dropoff_point_id', $pointFare->dropoff_point_id) == $point->id ? 'selected' : '' }}>
                        {{ $point->name }} - {{ $point->address }}
                    </option>
                @endforeach
            </select>
            @error('dropoff_point_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- GIÁ VÉ (PRICE) - CÓ THÊM OLD() --}}
        <div class="mb-3">
            <label for="price" class="form-label">Giá vé (VNĐ) <span class="text-danger">*</span></label>
            <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" 
                   value="{{ old('price', $pointFare->price) }}" min="0" required>
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- TRẠNG THÁI (STATUS) - CÓ THÊM OLD() --}}
        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                <option value="1" {{ old('status', $pointFare->status) == 1 ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ old('status', $pointFare->status) == 0 ? 'selected' : '' }}>Ngưng hoạt động</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        {{-- THÔNG BÁO LỖI TRÙNG LẶP (Được thêm ở hàm update) --}}
        @error('combination')
            <div class="alert alert-warning">
                - {{ $message }}
            </div>
        @enderror

        <div class="d-flex gap-2">
            <a href="{{ route('admin.point_fares.index') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">Cập nhật giá vé</button>
        </div>
    </form>
</div>
@endsection