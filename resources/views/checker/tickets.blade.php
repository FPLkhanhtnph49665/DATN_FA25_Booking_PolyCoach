@extends('layouts.checker')

@section('title', 'Danh sách vé')

@section('content')

    <h3 class="mb-4 text-whith fw-bold"><i class="bi bi-ticket-detailed"></i> Danh sách vé</h3>

    {{-- ===========================
    BỘ LỌC TÌM KIẾM
    =========================== --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">

            <form method="GET" class="row g-3">

                {{-- Mã vé --}}
                <div class="col-md-3">
                    <label class="form-label fw-bold">Mã vé</label>
                    <input type="text" name="code" value="{{ request('code') }}" class="form-control"
                        placeholder="Nhập mã vé...">
                </div>

                {{-- Trạng thái --}}
                <div class="col-md-3">
                    <label class="form-label fw-bold">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="checked" {{ request('status') == 'checked' ? 'selected' : '' }}>Đã kiểm</option>
                        <option value="unchecked" {{ request('status') == 'unchecked' ? 'selected' : '' }}>Chưa kiểm
                        </option>
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
    BẢNG VÉ
    =========================== --}}

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Mã vé</th>
                    <th>Chuyến</th>
                    <th>xe đi</th>
                    <th>số ghế</th>
                    <th>Giá</th>
                    <th>Trạng thái</th>
                    <th>thời gian</th>
                    <th class="text-center" style="width: 70px;"></th>
                </tr>
            </thead>

            <tbody>
                @forelse ($tickets as $ticket)
                    @php
                        $trip = $ticket->trip;
                        $route = $trip?->route;
                        $pCount = $ticket->passengers->count();
                    @endphp

                    <tr>

                        {{-- Mã vé --}}
                        <td>
                            <span class="fw-bold" data-bs-toggle="tooltip" title="Mã vé: {{ $ticket->code }}">
                                {{ $ticket->code }}
                            </span>
                        </td>

                        {{-- Chuyến --}}
                        <td>
                            @if ($trip && $route)
                                <div>
                                    <i class="bi bi-geo-alt text-primary"></i>
                                    {{ $route->fromCity->name }} → {{ $route->toCity->name }}
                                </div>

                                <small class="text-muted">
                                    <i class="bi bi-clock"></i>
                                    {{ $trip->departure_date?->format('d/m/Y') }}
                                </small>
                            @else
                                ---
                            @endif
                        </td>
                        {{-- Xe đi --}}
                        <td>
                            <div>
                                <i class="bi bi-bus-front text-primary"></i>
                                {{ $trip->bus->plate_number }}
                            </div>
                        </td>

                        {{-- Số ghế --}}
                        <td>
                            <span class="fw-bold" data-bs-toggle="tooltip" title="Mã ghế: {{ $ticket->seat_code }}">
                                {{ $ticket->seat_code }}
                            </span>
                        </td>

                        {{-- Giá --}}
                        <td>{{ number_format($ticket->price) }}VND</td>

                        {{-- Trạng thái với màu Vexere-style --}}
                        <td>
                            @if ($ticket->checked_at)
                                <span class="badge bg-success px-3 py-2">
                                    <i class="bi bi-check-circle"></i> Đã kiểm
                                </span>
                            @else
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    <i class="bi bi-exclamation-circle"></i> Chưa kiểm
                                </span>
                            @endif
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
                            <a href="{{ route('checker.tickets.show', $ticket->id) }}" class="btn btn-sm btn-primary"
                                data-bs-toggle="tooltip" title="Xem chi tiết">
                                <i class="bi bi-eye"></i>
                            </a>
                            {{-- Thay đổi nút kiểm tra vé --}}
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2 btn-check-ticket"
                                {{ $ticket->checked_at ? 'disabled' : '' }} data-bs-toggle="modal"
                                data-bs-target="#checkTicketModal" data-id="{{ $ticket->id }}"
                                data-status="{{ $ticket->status }}" data-code="{{ $ticket->code }}"
                                data-url="{{ route('checker.tickets.updateStatus', ':id') }}"
                                title="{{ $ticket->checked_at ? 'Vé này đã được kiểm tra' : 'Kiểm tra vé' }}">
                                <i class="bi bi-check2-circle"></i>
                            </button>
                        </td>

                    </tr>
                @empty

                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">
                            Không có vé nào.
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-end mt-3">
        {{ $tickets->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
    {{-- ===========================
    MODAL CẬP NHẬT TRẠNG THÁI VÉ
    =========================== --}}
    <div class="modal fade" id="checkTicketModal" tabindex="-1" aria-labelledby="checkTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="updateStatusForm" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="checkTicketModalLabel">
                            <i class="bi bi-gear-fill me-2"></i>
                            Cập nhật trạng thái vé
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-light border mb-3">
                            vé mã: <strong id="modal-ticket-code" class="text-primary fs-5">...</strong>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Trạng thái</label>
                            <select name="status" id="modal-status-select" class="form-select">
                                <option value="pending">Đang chờ xử lý</option>
                                <option value="paid">Đã thanh toán</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">hủy</button>
                        <button type="submit" class="btn btn-primary px-4">lưu thay đổi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        // Xử lý nút kiểm tra vé
        document.addEventListener('DOMContentLoaded', function() {
            const checkButtons = document.querySelectorAll('.btn-check-ticket');
            const statusForm = document.getElementById('updateStatusForm');
            const statusSelect = document.getElementById('modal-status-select');
            const ticketCodeText = document.getElementById('modal-ticket-code');

            checkButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // 1. Lấy dữ liệu từ thuộc tính data-
                    const ticketId = this.getAttribute('data-id');
                    const ticketCode = this.getAttribute('data-code');
                    const currentStatus = this.getAttribute('data-status');
                    const baseUrl = this.getAttribute('data-url');

                    // 2. Thay thế :id trong URL mẫu bằng ID thật
                    // Ví dụ: /tickets/:id/status -> /tickets/10/status
                    statusForm.action = baseUrl.replace(':id', ticketId);

                    // 3. Hiển thị thông tin lên Modal cho người dùng dễ nhìn
                    ticketCodeText.innerText = ticketCode;
                    statusSelect.value = currentStatus;
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
@endpush
