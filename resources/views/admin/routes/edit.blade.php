@extends('layouts.admin')

@section('title', 'Chỉnh sửa tuyến')

@section('content')

    {{-- trùng tuyến đường --}}
    @error('route_exists')
        <div class="alert alert-warning">
            {{ $message }}
        </div>
    @enderror

    <div class="card">
        <div class="card-header">
            <h4>Chỉnh sửa tuyến: {{ $route->fromCity->name }} - {{ $route->toCity->name }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.routes.update', $route->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Thành phố đi</label>
                    <select name="from_city_id" class="form-select @error('from_city_id') is-invalid @enderror">
                        <option value="">-- Chọn thành phố đi --</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}"
                                {{ old('from_city_id', $route->from_city_id) == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('from_city_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Điểm đến</label>
                    <select name="to_city_id" class="form-select @error('to_city_id') is-invalid @enderror">
                        <option value="">-- Chọn điểm đến --</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}"
                                {{ old('to_city_id', $route->to_city_id) == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('to_city_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Quãng đường (km)</label>
                    <input type="number" name="distance" class="form-control"
                        value="{{ old('distance', $route->distance) }}" min="1">
                    @error('distance')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Thời gian dự kiến</label>
                    <input type="time" name="estimated_time" class="form-control"
                        value="{{ old('estimated_time', \Carbon\Carbon::parse($route->estimated_time)->format('H:i')) }}">
                    @error('estimated_time')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="1" {{ old('status', $route->status) == 1 ? 'selected' : '' }}>Hoạt động
                        </option>
                        <option value="0" {{ old('status', $route->status) == 0 ? 'selected' : '' }}>Tạm ngưng
                        </option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('admin.routes.index') }}" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
@endsection
