@extends('layouts.admin')

@section('title', 'Chỉnh sửa xe')

@section('content')
    <div class="container">
        <h2 class="mb-4">Chỉnh sửa thông tin xe</h2>

        <form action="{{ route('admin.buses.update', $bus->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="bien_so" class="form-label">Biển số</label>
                <input type="text" name="bien_so" id="bien_so" class="form-control"
                    value="{{ old('bien_so', $bus->bien_so) }}">
                @error('bien_so')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="so_ghe" class="form-label">Số ghế</label>
                <input type="number" name="so_ghe" id="so_ghe" class="form-control"
                    value="{{ old('so_ghe', $bus->so_ghe) }}">
                @error('so_ghe')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="loai_xe" class="form-label">Loại xe</label>
                <select name="loai_xe" id="loai_xe" class="form-control" required>
                    <option value="">-- Chọn loại xe --</option>
                    <option value="Ghế ngồi" {{ old('loai_xe', $bus->loai_xe) == 'Ghế ngồi' ? 'selected' : '' }}>Ghế ngồi
                    </option>
                    <option value="Giường nằm" {{ old('loai_xe', $bus->loai_xe) == 'Giường nằm' ? 'selected' : '' }}>Giường
                        nằm</option>
                    <option value="Limousine" {{ old('loai_xe', $bus->loai_xe) == 'Limousine' ? 'selected' : '' }}>Limousine
                    </option>
                </select>
                @error('loai_xe')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="trang_thai" class="form-label">Trạng thái</label>
                <select name="trang_thai" id="trang_thai" class="form-control">
                    <option value="1" {{ old('trang_thai', $bus->trang_thai) == 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('trang_thai', $bus->trang_thai) == 0 ? 'selected' : '' }}>Bảo dưỡng</option>
                </select>
            </div>


            <button type="submit" class="btn btn-success">Cập nhật</button>
            <a href="{{ route('admin.buses.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection