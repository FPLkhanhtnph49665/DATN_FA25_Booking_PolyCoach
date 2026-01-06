{{-- resources/views/admin/trips/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Thêm Chuyến')

@section('content')
    <div class="mb-4">
        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                    <i class="bi bi-calendar2-week-fill"></i>
                    Thêm chuyến mới
                </h2>
                <p class="text-light small mb-0">
                    Tạo mới chuyến xe theo tuyến, xe, ngày giờ khởi hành và giá vé.
                </p>
            </div>
        </div>

        {{-- Thông báo lỗi --}}
        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold mb-1">Đã có lỗi xảy ra:</div>
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}

        {{-- Form tạo chuyến --}}
        <div class="card border-0">
            <div class="card-body">
                <form action="{{ route('admin.trips.store') }}" method="POST" class="row g-3">
                    @csrf

                    {{-- Tuyến --}}
                    <div class="col-md-6">
                        <label for="route_id" class="form-label small text-light mb-1">
                            Tuyến <span class="text-danger">*</span>
                        </label>
                        <select name="route_id" id="route_id" class="form-select @error('route_id') is-invalid @enderror">
                            <option value="" disabled {{ old('route_id') ? '' : 'selected' }}>-- Chọn tuyến --
                            </option>
                            @foreach ($routes as $route)
                                <option value="{{ $route->id }}" {{ old('route_id') == $route->id ? 'selected' : '' }}>
                                    {{ $route->fromCity?->name }} → {{ $route->toCity?->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('route_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Xe --}}
                    <div class="col-md-6">
                        <label for="bus_id" class="form-label small text-light mb-1">
                            Xe <span class="text-danger">*</span>
                        </label>
                        <select name="bus_id" id="bus_id" class="form-select @error('bus_id') is-invalid @enderror">
                            <option value="" disabled {{ old('bus_id') ? '' : 'selected' }}>-- Chọn xe --</option>
                            @foreach ($buses as $bus)
                                <option value="{{ $bus->id }}" {{ old('bus_id') == $bus->id ? 'selected' : '' }}>
                                    {{ $bus->plate_number }} ({{ $bus->seat_count }} ghế - {{ $bus->type }})
                                </option>
                            @endforeach
                        </select>
                        @error('bus_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Ngày khởi hành --}}
                    <div class="col-md-4">
                        <label for="departure_date" class="form-label small text-light mb-1">
                            Ngày khởi hành <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="departure_date" id="departure_date"
                            class="form-control @error('departure_date') is-invalid @enderror"
                            min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                            value="{{ old('departure_date', \Carbon\Carbon::today()->format('Y-m-d')) }}">
                        @error('departure_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Giờ khởi hành --}}
                    <div class="col-md-4">
                        <label for="departure_time" class="form-label small text-light mb-1">
                            Giờ khởi hành <span class="text-danger">*</span>
                        </label>
                        <input type="time" name="departure_time" id="departure_time"
                            class="form-control @error('departure_time') is-invalid @enderror"
                            value="{{ old('departure_time') }}" step="60" {{-- đảm bảo format H:i, không có giây --}}>
                        @error('departure_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Giờ đến dự kiến --}}
                    <div class="col-md-4">
                        <label for="arrival_time" class="form-label small text-light mb-1">
                            Giờ đến dự kiến <span class="text-danger">*</span>
                        </label>
                        <input type="time" name="arrival_time" id="arrival_time"
                            class="form-control @error('arrival_time') is-invalid @enderror"
                            value="{{ old('arrival_time') }}" step="60">
                        @error('arrival_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Giá vé --}}
                    <div class="col-md-4">
                        <label for="ticket_price" class="form-label small text-light mb-1">
                            Giá vé (VND) <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="ticket_price" id="ticket_price"
                            class="form-control @error('ticket_price') is-invalid @enderror"
                            value="{{ old('ticket_price') }}" min="0" step="1000" placeholder="VD: 150000">
                        @error('ticket_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Trạng thái --}}
                    <div class="col-md-4">
                        <label for="status" class="form-label small text-light mb-1">
                            Trạng thái
                        </label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>
                                Hoạt động
                            </option>
                            <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>
                                Khóa
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nút --}}
                    <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('admin.trips.index') }}" class="btn btn-outline-light">
                            Hủy
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>
                            Lưu chuyến
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
