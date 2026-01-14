@extends('layouts.checker')

@section('title', 'Danh sách chuyến xe')

@section('content')

    <h3 class="mb-4 text-whith fw-bold"><i class="bi bi-bus-front"></i> Danh sách Chuyến Xe</h3>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <form method="GET" class="row g-3">
                {{-- Mã chuyến --}}
                <div class="col-md-3">
                    <label class="form-label fw-bold">Mã chuyến</label>
                    <input type="text" name="code" value="{{ request('code') }}" class="form-control"
                        placeholder="Nhập mã chuyến (VD: TRIP-2025...)">
                </div>

                {{-- Trạng thái: Dùng Constant từ Model --}}
                <div class="col-md-3">
                    <label class="form-label fw-bold">Trạng thái</label>
                    <select name="trip_status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="1" {{ request('trip_status') == 1 ? 'selected' : '' }}>Chưa xuất phát</option>
                        <option value="2" {{ request('trip_status') == 2 ? 'selected' : '' }}>Đã tạm hoãn</option>
                        <option value="3" {{ request('trip_status') == 3 ? 'selected' : '' }}>Đã xuất phát</option>
                        <option value="4" {{ request('trip_status') == 4 ? 'selected' : '' }}>Đã hoàn thành</option>
                    </select>
                </div>

                {{-- Tuyến --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tuyến</label>
                    <select name="route_id" class="form-select">
                        <option value="">-- Tất cả tuyến --</option>
                        @foreach ($routes as $r)
                            <option value="{{ $r->id }}" {{ request('route_id') == $r->id ? 'selected' : '' }}>
                                {{ $r->fromCity->name }} → {{ $r->toCity->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Button --}}
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Lọc
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===========================
       BẢNG DANH SÁCH
    =========================== --}}

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Mã chuyến</th>
                    <th>tuyến</th>
                    <th>Xe đi</th>
                    <th>Số khách</th>
                    <th>Trạng thái</th>
                    <th>Thời gian</th>
                    <th class="text-center" style="width: 70px;"></th>
                </tr>
            </thead>

            <tbody>
                @forelse ($trips as $trip)
                    <tr>
                        {{-- Mã chuyến --}}
                        <td>
                            <span class="fw-bold">
                                {{ $trip->trip_code }}
                            </span>
                        </td>

                        {{-- Tuyến --}}
                        <td>
                            <div>
                                <i class="bi bi-geo-alt text-primary"></i>
                                {{ $trip->route->fromCity->name }} → {{ $trip->route->toCity->name }}
                            </div>

                            <small class="text-muted">
                                <i class="bi bi-clock"></i>
                                {{ $trip->departure_date?->format('d/m/Y') }}
                            </small>
                        </td>

                        {{-- Xe đi --}}

                        <td>
                            <div>
                                <i class="bi bi-bus-front text-primary"></i>
                                {{ $trip->bus->plate_number }}
                            </div>
                        </td>

                        {{-- Số khách (Đếm theo số lượng vé đã bán) --}}
                        <td class="text-center">
                            <span class="badge bg-secondary rounded-pill">
                                <i class="bi bi-people-fill"></i>
                                {{-- Sử dụng tickets_count để đếm chính xác số ghế đã đặt --}}
                                {{ $trip->tickets_count ?? 0 }}/{{ $trip->bus->seat_count ?? '?' }}
                            </span>
                        </td>

                        {{-- Trạng thái: Dùng Accessor đã tạo trong Model --}}
                        <td class="text-center">
                            {{-- Lưu ý: Bạn cần thêm logic getStatusBadgeAttribute trong Model như tôi đã gợi ý trước đó --}}
                            {{-- Nếu chưa có badge, mặc định dùng logic if --}}
                            @php
                                $badgeClass = match ($trip->trip_status) {
                                    1 => 'bg-info text-dark',
                                    2 => 'bg-danger',
                                    3 => 'bg-primary',
                                    4 => 'bg-success',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} px-3 py-2">
                                {{ $trip->status_text }}
                            </span>
                        </td>

                        {{-- Giờ đi --}}
                        <td class="text-center">
                            <h5 class="mb-0 text-dark fw-bold">
                                {{ \Carbon\Carbon::parse($trip->departure_time)->format('H:i') }}
                            </h5>
                            @if ($trip->arrival_time)
                                <small class="text-muted">Đến:
                                    {{ \Carbon\Carbon::parse($trip->arrival_time)->format('H:i') }}</small>
                            @endif
                        </td>

                        {{-- Action --}}
                        <td class="text-center">
                            @php
                                // Kiểm tra nếu trạng thái là Đã tạm hoãn (2) hoặc Đã hoàn thành (4)
                                $isLocked = in_array($trip->trip_status, [2, 4]);
                            @endphp
                            {{-- Nút mở Modal --}}
                            <button type="button" class="btn btn-sm btn-outline-primary btn-update-status"
                                {{ $isLocked ? 'disabled' : '' }} data-id="{{ $trip->id }}"
                                data-code="{{ $trip->trip_code }}" data-status="{{ $trip->trip_status }}"
                                data-bs-toggle="modal" data-bs-target="#checkTicketModal"
                                title="{{ $isLocked ? 'Chuyến xe đã kết thúc hoặc bị hoãn, không thể cập nhật' : 'Cập nhật trạng thái' }}">
                                <i class="bi bi-check2-circle"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" width="64" alt="Empty"
                                class="mb-3 opacity-50">
                            <p class="mb-0">Không tìm thấy chuyến xe nào phù hợp.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-end mt-4">
        {{ $trips->withQueryString()->links('pagination::bootstrap-4') }}
    </div>

    {{-- ===========================
        MODAL CẬP NHẬT TRẠNG THÁI
    =========================== --}}
    <div class="modal fade" id="checkTicketModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            {{-- Form này cần ID hoặc Class để JS can thiệp action --}}
            <form id="updateStatusForm" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-gear-fill me-2"></i> Cập nhật trạng thái chuyến
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="alert alert-light border mb-3">
                            Chuyến xe: <strong id="modal-trip-code" class="text-primary fs-5">...</strong>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Trạng thái</label>
                            <select name="trip_status" id="modal-status-select" class="form-select">
                                <option value="1">Chưa xuất phát</option>
                                <option value="2">Đã tạm hoãn</option>
                                <option value="3">Đã xuất phát</option>
                                <option value="4">Đã hoàn thành</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary px-4">Lưu thay đổi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- JAVASCRIPT XỬ LÝ MODAL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const updateButtons = document.querySelectorAll('.btn-update-status');
    const modalCode = document.getElementById('modal-trip-code');
    const modalSelect = document.getElementById('modal-status-select');
    const form = document.getElementById('updateStatusForm');

    const baseUrl = "{{ url('checker/trips') }}";

    updateButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const code = this.getAttribute('data-code');
            const status = this.getAttribute('data-status'); // Trạng thái hiện tại

            // 1. Cập nhật thông tin cơ bản
            modalCode.textContent = code;
            modalSelect.value = status;
            form.action = `${baseUrl}/${id}/update-status`;

            // 2. Logic ẩn/hiện các option dựa trên trạng thái hiện tại
            const options = modalSelect.querySelectorAll('option');
            
            options.forEach(option => {
                // Trước tiên, hiển thị lại tất cả các option (reset trạng thái)
                option.style.display = 'block';
                option.disabled = false;

                if (status == '3') { // Nếu trạng thái hiện tại là "Đã xuất phát"
                    // Ẩn "Chưa xuất phát" (1) và "Đã tạm hoãn" (2)
                    if (option.value == '1' || option.value == '2') {
                        option.style.display = 'none';
                        option.disabled = true;
                    }
                }
                
                // Lưu ý: "Đã xuất phát" (3) vẫn hiện để giữ giá trị mặc định, 
                // và "Đã hoàn thành" (4) hiện để người dùng chọn.
            });
        });
    });
});
    </script>
    {{-- Thông báo thành công --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: {!! json_encode(session('success')) !!},
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        </script>
    @endif

    {{-- Thông báo lỗi (nếu có) --}}
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: {!! json_encode(session('error')) !!},
                confirmButtonText: 'Đóng'
            });
        </script>
    @endif
@endsection
