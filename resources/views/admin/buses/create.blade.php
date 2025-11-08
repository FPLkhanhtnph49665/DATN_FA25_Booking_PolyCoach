@extends('layouts.admin')
@section('title', 'Thêm xe mới')

@section('content')
<h2>Thêm xe mới</h2>

<form action="{{ route('admin.buses.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Biển số</label>
        <input type="text" name="bien_so" class="form-control" value="{{ old('bien_so') }}">
        @error('bien_so') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label>Số ghế</label>
        <input type="number" name="so_ghe" class="form-control" value="{{ old('so_ghe') }}">
        @error('so_ghe') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

 <div class="mb-3">
    <label for="loai_xe" class="form-label">Loại xe</label>
    <select name="loai_xe" id="loai_xe" class="form-control" required>
        <option value="">-- Chọn loại xe --</option>
        <option value="Ghế ngồi" {{ old('loai_xe') == 'Ghế ngồi' ? 'selected' : '' }}>Ghế ngồi</option>
        <option value="Giường nằm" {{ old('loai_xe') == 'Giường nằm' ? 'selected' : '' }}>Giường nằm</option>
        <option value="Limousine" {{ old('loai_xe') == 'Limousine' ? 'selected' : '' }}>Limousine</option>
    </select>
    @error('loai_xe')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
    <button class="btn btn-success">Lưu</button>
    <a href="{{ route('admin.buses.index') }}" class="btn btn-secondary">Hủy</a>
</form>
@endsection
