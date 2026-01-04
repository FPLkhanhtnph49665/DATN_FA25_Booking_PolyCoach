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

        {{-- Form chỉnh sửa xe --}}
        <div class="card border-0">
            <div class="card-body">
                <form action="{{ route('admin.buses.update', $bus->id) }}" method="POST" enctype="multipart/form-data"
                    class="row g-3">
                    @csrf
                    @method('PUT')

                    {{-- Biển số --}}
                    <div class="col-md-4">
                        <label for="plate_number" class="form-label small text-light mb-1">
                            Biển số xe <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="plate_number" id="plate_number"
                            class="form-control @error('plate_number') is-invalid @enderror"
                            value="{{ old('plate_number', $bus->plate_number) }}" placeholder="VD: 29B-123.45">
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
                        <select name="seat_count" id="seat_count"
                            class="form-select @error('seat_count') is-invalid @enderror">
                            @foreach ([4, 7, 16, 32] as $count)
                                <option value="{{ $count }}"
                                    {{ old('seat_count', $bus->seat_count) == $count ? 'selected' : '' }}>
                                    {{ $count }} ghế
                                </option>
                            @endforeach
                        </select>
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
                        <select name="type" id="type" class="form-select @error('type') is-invalid @enderror">
                            <option value="" disabled>-- Chọn loại xe --</option>
                            <option value="Seat" {{ old('type', $bus->type) == 'seat' ? 'selected' : '' }}>Seat</option>
                            <option value="Sleeper" {{ old('type', $bus->type) == 'sleeper' ? 'selected' : '' }}>Sleeper
                            </option>
                            <option value="Limousine" {{ old('type', $bus->type) == 'limousine' ? 'selected' : '' }}>
                                Limousine</option>
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
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
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
                    {{-- Thêm thuộc tính multiple và đổi tên thành images[] --}}
                    <div class="col-12 mt-4">
                        <label class="form-label small text-light mb-3 d-block text-uppercase fw-bold">Album hình ảnh của
                            xe</label>

                        {{-- 1. Hiển thị danh sách ảnh hiện có --}}
                        <div class="row g-3 mb-4" id="current-images">
                            @foreach ($bus->images as $img)
                                {{-- Thêm ID vào div cha để JavaScript tìm và xóa --}}
                                <div class="col-6 col-md-3 col-lg-2" id="image-container-{{ $img->id }}">
                                    <div class="position-relative group border border-secondary rounded overflow-hidden">
                                        <img src="{{ asset($img->image_path) }}" class="img-fluid"
                                            style="height: 120px; width: 100%; object-fit: cover;">

                                        {{-- Nút xóa ảnh --}}
                                        <div class="position-absolute top-0 end-0 p-1">
                                            <button type="button" class="btn btn-danger btn-sm p-0 px-1"
                                                onclick="confirmDeleteImage({{ $img->id }})">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- 2. Khu vực tải thêm ảnh mới --}}
                        <div class="p-4 border border-dashed border-secondary rounded bg-dark">
                            <div class="text-center">
                                <i class="bi bi-images text-muted display-6"></i>
                                <div class="mt-3">
                                    <label for="images" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-circle me-1"></i> Chọn thêm nhiều ảnh
                                    </label>
                                    <input type="file" name="images[]" id="images" class="d-none" accept="image/*"
                                        multiple onchange="previewMultipleFiles(this)">
                                </div>
                                <p class="text-white small mt-2 mb-0">Bạn có thể chọn nhiều ảnh cùng lúc (JPG, PNG, WebP.
                                    Tối đa 2MB/ảnh)</p>
                            </div>

                            {{-- Nơi hiển thị các ảnh vừa chọn (chưa lưu) --}}
                            <div id="new-images-preview" class="row g-2 mt-3"></div>
                        </div>
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
    {{-- Script để xem trước ảnh ngay khi chọn file --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Xem trước nhiều ảnh khi chọn
        function previewMultipleFiles(input) {
            var previewContainer = document.getElementById("new-images-preview");
            previewContainer.innerHTML = "";

            if (input.files) {
                [...input.files].forEach(file => {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var div = document.createElement("div");
                        div.className = "col-4 col-md-2";
                        div.innerHTML = `
                        <div class="position-relative border border-info rounded overflow-hidden">
                            <img src="${e.target.result}" class="img-fluid" style="height: 80px; width: 100%; object-fit: cover;">
                            <span class="position-absolute bottom-0 start-0 w-100 bg-info text-dark text-center small" style="font-size: 10px;">MỚI</span>
                        </div>`;
                        previewContainer.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        // Xác nhận xóa ảnh
        function confirmDeleteImage(imageId) {
            Swal.fire({
                title: 'Xóa ảnh này?',
                text: "Ảnh sẽ bị xóa vĩnh viễn khỏi hệ thống!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Xóa ngay',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteImage(imageId);
                }
            })
        }

        // Gọi AJAX xóa ảnh
        function deleteImage(imageId) {
            // Sử dụng đường dẫn tuyệt đối
            const url = `/admin/bus-images/${imageId}`;

            fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const element = document.getElementById(`image-container-${imageId}`);
                        if (element) {
                            element.style.transition = "all 0.3s ease";
                            element.style.opacity = "0";
                            setTimeout(() => element.remove(), 300);
                        }
                        Swal.fire({
                            icon: 'success',
                            title: 'Đã xóa!',
                            timer: 1000,
                            showConfirmButton: false
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Lỗi!', 'Không thể thực hiện yêu cầu.', 'error');
                });
        }
    </script>
@endsection
