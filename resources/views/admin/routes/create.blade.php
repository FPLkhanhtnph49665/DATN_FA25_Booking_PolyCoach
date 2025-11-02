@extends('layouts.admin')

@section('title', 'Thêm tuyến mới')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Thêm tuyến mới</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.bus-routes.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Điểm đi</label>
                <input type="text" name="diem_di" class="form-control" value="{{ old('diem_di') }}" required>
                @error('diem_di') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label>Điểm đến</label>
                <input type="text" name="diem_den" class="form-control" value="{{ old('diem_den') }}" required>
                @error('diem_den') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label>Quãng đường (km)</label>
                <input type="number" name="quang_duong" class="form-control" value="{{ old('quang_duong') }}" required min="1">
                @error('quang_duong') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label>Thời gian dự kiến</label>
                <input type="time" name="thoi_gian_du_kien" class="form-control" value="{{ old('thoi_gian_du_kien') }}" required>
                @error('thoi_gian_du_kien') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label>Trạng thái</label>
                <select name="trang_thai" class="form-select">
                    <option value="1" {{ old('trang_thai', 1) == 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('trang_thai') == 0 ? 'selected' : '' }}>Đã khóa</option>
                </select>
                @error('trang_thai') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-success">Lưu</button>
            <a href="{{ route('admin.bus-routes.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection
