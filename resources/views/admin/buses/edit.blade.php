{{-- resources/views/admin/buses/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Chỉnh sửa Xe')

@section('content')
<div class="mb-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                <i class="bi bi-bus-front-fill"></i>
                Chỉnh sửa xe
            </h2>
            <p class="text-light small mb-0">
                Cập nhật thông tin xe: biển số, số ghế, loại xe và trạng thái hoạt động trong hệ thống PolyCoach.
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

    {{-- Form chỉnh sửa xe --}}
    <div class="card border-0">
        <div class="card-body">
            <form action="{{ route('admin.buses.update', $bus->id) }}"
                  method="POST"
                  class="row g-3">
                @csrf
                @method('PUT')

                {{-- Biển số --}}
                <div class="col-md-4">
                    <label for="plate_number" class="form-label small text-light mb-1">
                        Biển số xe <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        name="plate_number"
                        id="plate_number"
                        class="form-control @error('plate_number') is-invalid @enderror"
                        value="{{ old('plate_number', $bus->plate_number) }}"
                        placeholder="VD: 29B-123.45"
                        required
                    >
                    @error('plate_number')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Số ghế --}}
                <div class="col-md-2">
                    <label for="seat_count" class="form-label small text-light mb-1">
                        Số ghế <span class="text-danger">*</span>
                    </label>
                    <input
                        type="number"
                        name="seat_count"
                        id="seat_count"
                        class="form-control @error('seat_count') is-invalid @enderror"
                        value="{{ old('seat_count', $bus->seat_count) }}"
                        min="1"
                        placeholder="VD: 16, 29, 45..."
                        required
                    >
                    @error('seat_count')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Loại xe --}}
                <div class="col-md-3">
                    <label for="type" class="form-label small text-light mb-1">
                        Loại xe <span class="text-danger">*</span>
                    </label>
                    <select
                        name="type"
                        id="type"
                        class="form-select @error('type') is-invalid @enderror"
                        required
                    >
                        <option value="" disabled>-- Chọn loại xe --</option>
                        <option value="Seat" {{ old('type', $bus->type) === 'Seat' ? 'selected' : '' }}>Seat</option>
                        <option value="Sleeper" {{ old('type', $bus->type) === 'Sleeper' ? 'selected' : '' }}>Sleeper</option>
                        <option value="Limousine" {{ old('type', $bus->type) === 'Limousine' ? 'selected' : '' }}>Limousine</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Trạng thái --}}
                <div class="col-md-3">
                    <label for="status" class="form-label small text-light mb-1">
                        Trạng thái
                    </label>
                    <select
                        name="status"
                        id="status"
                        class="form-select @error('status') is-invalid @enderror"
                    >
                        <option value="1" {{ old('status', $bus->status) == 1 ? 'selected' : '' }}>
                            Hoạt động
                        </option>
                        <option value="0" {{ old('status', $bus->status) == 0 ? 'selected' : '' }}>
                            Bảo dưỡng / Ngưng hoạt động
                        </option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Nút --}}
                <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('admin.buses.index') }}" class="btn btn-outline-light">
                        Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Cập nhật xe
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection