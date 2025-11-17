@extends('layouts.admin')

@section('title', 'Chỉnh sửa chuyến xe')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Chỉnh sửa chuyến xe #{{ $trip->id }}</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.trips.update', $trip->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tuyến đường <span class="text-danger">*</span></label>
                        <select name="route_id" class="form-select" required>
                            <option value="">-- Chọn tuyến --</option>
                            @foreach($routes as $route)
                                <option value="{{ $route->id }}" {{ old('route_id', $trip->route_id) == $route->id ? 'selected' : '' }}>
                                    {{ $route->diem_di }} - {{ $route->diem_den }}
                                </option>
                            @endforeach
                        </select>
                        @error('route_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Xe <span class="text-danger">*</span></label>
                        <select name="bus_id" class="form-select" required>
                            <option value="">-- Chọn xe --</option>
                            @foreach($buses as $bus)
                                <option value="{{ $bus->id }}" {{ old('bus_id', $trip->bus_id) == $bus->id ? 'selected' : '' }}>
                                    {{ $bus->bien_so }} ({{ $bus->loai_xe }} - {{ $bus->so_ghe }} ghế)
                                </option>
                            @endforeach
                        </select>
                        @error('bus_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Ngày khởi hành <span class="text-danger">*</span></label>
                        <input type="date" name="ngay_khoi_hanh" class="form-control" 
                               value="{{ old('ngay_khoi_hanh', $trip->ngay_khoi_hanh?->format('Y-m-d')) }}" required>
                        @error('ngay_khoi_hanh') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Giờ khởi hành (0-23h) <span class="text-danger">*</span></label>
                        <select name="gio_khoi_hanh" class="form-select" required>
                            <option value="">-- Chọn giờ --</option>
                            @for($i = 0; $i < 24; $i++)
                                <option value="{{ sprintf('%02d:00', $i) }}" 
                                    {{ old('gio_khoi_hanh', $trip->gio_khoi_hanh) == sprintf('%02d:00', $i) ? 'selected' : '' }}>
                                    {{ sprintf('%02d:00', $i) }}
                                </option>
                                <option value="{{ sprintf('%02d:30', $i) }}" 
                                    {{ old('gio_khoi_hanh', $trip->gio_khoi_hanh) == sprintf('%02d:30', $i) ? 'selected' : '' }}>
                                    {{ sprintf('%02d:30', $i) }}
                                </option>
                            @endfor
                        </select>
                        @error('gio_khoi_hanh') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Giờ đến dự kiến (0-23h)</label>
                        <select name="gio_den" class="form-select">
                            <option value="">-- Chọn giờ --</option>
                            @for($i = 0; $i < 24; $i++)
                                <option value="{{ sprintf('%02d:00', $i) }}" 
                                    {{ old('gio_den', $trip->gio_den) == sprintf('%02d:00', $i) ? 'selected' : '' }}>
                                    {{ sprintf('%02d:00', $i) }}
                                </option>
                                <option value="{{ sprintf('%02d:30', $i) }}" 
                                    {{ old('gio_den', $trip->gio_den) == sprintf('%02d:30', $i) ? 'selected' : '' }}>
                                    {{ sprintf('%02d:30', $i) }}
                                </option>
                            @endfor
                        </select>
                        @error('gio_den') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Giá vé (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="gia_ve" class="form-control" 
                               value="{{ old('gia_ve', $trip->gia_ve) }}" min="0" step="1000" required>
                        @error('gia_ve') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                        <select name="trang_thai" class="form-select" required>
                            <option value="1" {{ old('trang_thai', $trip->trang_thai) == 1 ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ old('trang_thai', $trip->trang_thai) == 0 ? 'selected' : '' }}>Tạm ngưng</option>
                        </select>
                        @error('trang_thai') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success">Cập nhật</button>
                <a href="{{ route('admin.trips.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection