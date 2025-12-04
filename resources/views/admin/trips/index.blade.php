{{-- resources/views/admin/trips/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý Chuyến')

@section('content')
    <div class="mb-4">
        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                    <i class="bi bi-calendar2-week-fill"></i>
                    Danh sách chuyến
                </h2>
                <p class="text-muted small mb-0">
                    Quản lý các chuyến xe theo tuyến, xe, ngày giờ khởi hành và tình trạng ghế.
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.trips.create') }}" class="btn btn-primary d-flex align-items-center gap-1">
                    <i class="bi bi-plus-circle"></i>
                    <span>Thêm chuyến mới</span>
                </a>
            </div>
        </div>

        {{-- Thông báo lỗi nhanh --}}
        @if ($errors->any())
            <div class="alert alert-danger py-2 small">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Bộ lọc / tìm kiếm --}}
        <div class="card border-0 mb-4">
            <div class="card-body">
                <form action="{{ route('admin.trips.index') }}" method="GET" class="row g-3 align-items-end">
                    {{-- Tìm theo tuyến (thành phố đi / đến) --}}
                    <div class="col-md-5">
                        <label for="search" class="form-label text-muted small mb-1">Tìm kiếm</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="form-control" placeholder="Tuyến, thành phố đi / đến...">
                    </div>

                    {{-- Trạng thái --}}
                    <div class="col-md-3">
                        <label for="status" class="form-label text-muted small mb-1">Trạng thái</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">-- Tất cả --</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Khóa</option>
                        </select>
                    </div>

                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit"
                            class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                            <i class="bi bi-search"></i>
                            <span>Lọc</span>
                        </button>
                        <a href="{{ route('admin.trips.index') }}"
                            class="btn btn-outline-light flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Đặt lại</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Bảng danh sách --}}
        <div class="card border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="text-muted small">#</th>
                                <th class="text-muted small">Tuyến</th>
                                <th class="text-muted small">Xe</th>
                                <th class="text-muted small">Ngày khởi hành</th>
                                <th class="text-muted small">Giờ khởi hành</th>
                                <th class="text-muted small">Giờ đến dự kiến</th>
                                <th class="text-muted small">Giá vé</th>
                                <th class="text-muted small">Ghế còn trống</th>
                                <th class="text-muted small">Trạng thái</th>
                                <th class="text-muted small text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($trips as $trip)
                                <tr>
                                    {{-- đánh số theo phân trang --}}
                                    <td class="text-muted small">
                                        {{ $trips->firstItem() + $loop->index }}
                                    </td>

                                    {{-- Tuyến: dùng quan hệ fromCity / toCity trong Route model --}}
                                    <td class="fw-semibold">
                                        {{ $trip->route?->fromCity?->name ?? '-' }}
                                        <span class="text-muted">→</span>
                                        {{ $trip->route?->toCity?->name ?? '-' }}
                                    </td>

                                    {{-- Xe: dùng plate_number (theo Bus model mới) --}}
                                    <td>
                                        {{ $trip->bus?->plate_number ?? '-' }}
                                    </td>

                                    {{-- Ngày khởi hành --}}
                                    <td class="text-muted small">
                                        {{ $trip->departure_date ? \Carbon\Carbon::parse($trip->departure_date)->format('d/m/Y') : '-' }}
                                    </td>

                                    {{-- Giờ khởi hành & giờ đến dự kiến --}}
                                    <td class="text-muted small">
                                        {{ $trip->departure_time ? \Carbon\Carbon::parse($trip->departure_time)->format('H:i') : '-' }}
                                    </td>

                                    <td class="text-muted small">
                                        {{ $trip->arrival_time ? \Carbon\Carbon::parse($trip->arrival_time)->format('H:i') : '-' }}
                                    </td>

                                    {{-- Giá vé --}}
                                    <td>
                                        <span class="badge bg-secondary-subtle text-dark border border-primary-subtle">
                                            {{ number_format($trip->ticket_price ?? 0, 0, ',', '.') }}₫
                                        </span>
                                    </td>

                                    {{-- Ghế còn trống --}}
                                    <td>
                                        @php
                                            $availableSeats = $trip->available_seats ?? null;
                                            $availableCount = is_array($availableSeats)
                                                ? count($availableSeats)
                                                : $trip->available_seats_count ?? 0;
                                            $totalSeats = $trip->bus?->seat_count ?? 0;
                                        @endphp
                                        <span class="badge bg-info-subtle text-info border border-info-subtle">
                                            {{ $availableCount }} / {{ $totalSeats }}
                                        </span>
                                    </td>

                                    {{-- Trạng thái --}}
                                    <td>
                                        @if ($trip->status == 1)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                <i class="bi bi-circle-fill me-1" style="font-size: 0.55rem;"></i>
                                                Hoạt động
                                            </span>
                                        @else
                                            <span
                                                class="badge bg-secondary-subtle text-light border border-secondary-subtle">
                                                <i class="bi bi-slash-circle me-1"></i>
                                                Khóa
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Hành động --}}
                                    <td class="text-center">
                                        <a href="{{ route('admin.trips.edit', $trip->id) }}"
                                            class="btn btn-sm btn-outline-warning me-1">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('admin.trips.destroy', $trip->id) }}" method="POST"
                                            class="d-inline-block delete-trip-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-muted">
                                        Chưa có chuyến nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-end mt-3 px-3 pb-3">
                    {{ $trips->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: @json(session('success')),
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: @json(session('error')),
                showConfirmButton: true
            });
        </script>
    @endif

    <script>
        // SweetAlert2 confirm xóa chuyến
        document.querySelectorAll('.delete-trip-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc muốn xóa chuyến này?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
