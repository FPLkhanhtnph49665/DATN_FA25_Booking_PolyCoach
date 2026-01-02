{{-- resources/views/admin/point_fares/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Thêm giá vé mới')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-3">Thêm giá vé mới</h1>

        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}
        {{-- THÔNG BÁO LỖI TRÙNG LẶP (Được thêm ở hàm update) --}}
        @error('combination')
            <div class="alert alert-warning">
                 {{ $message }}
            </div>
        @enderror

        <form action="{{ route('admin.point_fares.store') }}" method="POST">
            @csrf

            {{-- Chọn Tuyến Xe --}}
            <div class="mb-3">
                <label for="route_id" class="form-label">Tuyến xe <span class="text-danger">*</span></label>
                <select name="route_id" id="route_id" class="form-select  @error('route_id') is-invalid @enderror">
                    <option value="">-- Chọn tuyến xe --</option>
                    @foreach ($routes as $route)
                        <option value="{{ $route->id }}" {{ old('route_id') == $route->id ? 'selected' : '' }}>
                            {{ $route->fromCity->name ?? '---' }} → {{ $route->toCity->name ?? '---' }}
                        </option>
                    @endforeach
                </select>
                @error('route_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Chọn Điểm Đón --}}
            <div class="mb-3">
                <label for="pickup_point_id" class="form-label">Điểm đón <span class="text-danger">*</span></label>
                <select name="pickup_point_id" id="pickup_point_id"
                    class="form-select @error('pickup_point_id') is-invalid @enderror">
                    <option value="">-- Vui lòng chọn tuyến trước --</option>
                    {{-- Lưu ý: Thêm data-route-id vào từng option --}}
                    @foreach ($points->where('type', 'pickup') as $point)
                        <option value="{{ $point->id }}" data-route-id="{{ $point->route_id }}"
                            {{ old('pickup_point_id') == $point->id ? 'selected' : '' }}>
                            {{ $point->name }} - {{ $point->address }}
                        </option>
                    @endforeach
                </select>
                @error('pickup_point_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Chọn Điểm Trả --}}
            <div class="mb-3">
                <label for="dropoff_point_id" class="form-label">Điểm trả <span class="text-danger">*</span></label>
                <select name="dropoff_point_id" id="dropoff_point_id"
                    class="form-select @error('dropoff_point_id') is-invalid @enderror">
                    <option value="">-- Vui lòng chọn tuyến trước --</option>
                    {{-- Lưu ý: Thêm data-route-id vào từng option --}}
                    @foreach ($points->where('type', 'dropoff') as $point)
                        <option value="{{ $point->id }}" data-route-id="{{ $point->route_id }}"
                            {{ old('dropoff_point_id') == $point->id ? 'selected' : '' }}>
                            {{ $point->name }} - {{ $point->address }}
                        </option>
                    @endforeach
                </select>
                @error('dropoff_point_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Giá vé (VNĐ) <span class="text-danger">*</span></label>
                <input type="number" name="price" id="price"
                    class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" min="0">
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const routeSelect = document.getElementById('route_id');
            const pickupSelect = document.getElementById('pickup_point_id');
            const dropoffSelect = document.getElementById('dropoff_point_id');

            // 1. Sao chép danh sách options gốc ban đầu (để dùng lại khi filter)
            // Bỏ qua option đầu tiên (là option placeholder "-- Chọn ... --")
            const allPickupOptions = Array.from(pickupSelect.querySelectorAll('option:not(:first-child)'));
            const allDropoffOptions = Array.from(dropoffSelect.querySelectorAll('option:not(:first-child)'));

            // Hàm lọc options
            function filterPointsByRoute(routeId) {
                // Reset 2 ô select về trạng thái rỗng (chỉ giữ lại placeholder)
                pickupSelect.innerHTML = '<option value="">-- Chọn điểm đón --</option>';
                dropoffSelect.innerHTML = '<option value="">-- Chọn điểm trả --</option>';

                if (!routeId) return; // Nếu chưa chọn tuyến thì dừng

                // Lọc và thêm lại các option điểm đón khớp route_id
                allPickupOptions.forEach(option => {
                    if (option.getAttribute('data-route-id') == routeId) {
                        pickupSelect.appendChild(option);
                    }
                });

                // Lọc và thêm lại các option điểm trả khớp route_id
                allDropoffOptions.forEach(option => {
                    if (option.getAttribute('data-route-id') == routeId) {
                        dropoffSelect.appendChild(option);
                    }
                });

                // Nếu có giá trị old() từ server (trường hợp validate lỗi), JS cần chọn lại đúng giá trị đó
                const oldPickupId = "{{ old('pickup_point_id') }}";
                const oldDropoffId = "{{ old('dropoff_point_id') }}";

                if (oldPickupId) pickupSelect.value = oldPickupId;
                if (oldDropoffId) dropoffSelect.value = oldDropoffId;
            }

            // 2. Lắng nghe sự kiện thay đổi tuyến xe
            routeSelect.addEventListener('change', function() {
                filterPointsByRoute(this.value);
                // Reset giá trị đã chọn về rỗng khi đổi tuyến để tránh lỗi dữ liệu
                pickupSelect.value = "";
                dropoffSelect.value = "";
            });

            // 3. Kích hoạt lọc ngay khi tải trang (để xử lý trường hợp Form Submit lỗi và redirect lại)
            if (routeSelect.value) {
                filterPointsByRoute(routeSelect.value);
            } else {
                // Nếu chưa chọn tuyến, xóa hết option để giao diện sạch sẽ
                pickupSelect.innerHTML = '<option value="">-- Vui lòng chọn tuyến trước --</option>';
                dropoffSelect.innerHTML = '<option value="">-- Vui lòng chọn tuyến trước --</option>';
            }
        });
    </script>
@endpush
