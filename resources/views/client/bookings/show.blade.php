@extends('layouts.client')

@section('content')
    <div class="container my-4">

        {{-- Thanh breadcrumb / quay lại --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('client.trips') }}" class="btn btn-link p-0">
                ← Về danh sách chuyến
            </a>

            <span class="badge bg-success">
                Đặt vé thành công
            </span>
        </div>

        {{-- Tiêu đề --}}
        <h4 class="mb-3">
            Xác nhận đặt vé
        </h4>

        ...
    </div>

    {{-- Popup SweetAlert2 sau khi đặt vé thành công --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Đặt vé thành công!',
                    text: @json(session('success')),
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    allowOutsideClick: false
                });
            });
        </script>
    @endif

@endsection


<div class="row">
    {{-- LEFT: Thông tin chuyến + booking --}}
    <div class="col-lg-8 mb-3">

        {{-- Thông tin chuyến xe --}}
        <div class="card mb-3 shadow-sm border-0">
            <div class="card-header bg-white border-0">
                <strong>
                    {{ optional(optional($booking->trip)->route)->diem_di }}
                    →
                    {{ optional(optional($booking->trip)->route)->diem_den }}
                </strong>
                <div class="small text-muted">
                    Mã chuyến: #{{ optional($booking->trip)->id }} ·
                    Mã đặt vé: #{{ $booking->id }}
                </div>
            </div>

            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4">
                        <div class="small text-muted">Ngày khởi hành</div>
                        <div class="fw-semibold">
                            @if(optional($booking->trip)->ngay_khoi_hanh)
                                {{ \Carbon\Carbon::parse($booking->trip->ngay_khoi_hanh)->format('d/m/Y') }}
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted">Giờ xuất bến</div>
                        <div class="fw-semibold">
                            {{ optional($booking->trip)->gio_khoi_hanh ?? '—' }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted">Giờ dự kiến đến</div>
                        <div class="fw-semibold">
                            {{ optional($booking->trip)->gio_den ?? '—' }}
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="small text-muted">Bến đi</div>
                        <div class="fw-semibold">
                            {{ optional(optional($booking->trip)->route)->ben_di
    ?? optional(optional($booking->trip)->route)->diem_di
    ?? '—' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Bến đến</div>
                        <div class="fw-semibold">
                            {{ optional(optional($booking->trip)->route)->ben_den
    ?? optional(optional($booking->trip)->route)->diem_den
    ?? '—' }}
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="small text-muted">Loại xe</div>
                        <div class="fw-semibold">
                            {{ optional($booking->trip)->loai_xe ?? '—' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Ghế đã đặt</div>
                        <div class="fw-semibold">
                            @php
                                $seats = $booking->seats
                                    ? explode(',', $booking->seats)
                                    : [];
                                $seats = array_filter(array_map('trim', $seats));
                            @endphp

                            @if(count($seats))
                                {{ implode(', ', $seats) }}
                            @else
                                Không có dữ liệu ghế
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Thông tin hành khách --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0">
                <strong>Thông tin hành khách</strong>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="small text-muted">Họ tên</div>
                        <div class="fw-semibold">
                            {{ $booking->customer_name ?? '—' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Số điện thoại</div>
                        <div class="fw-semibold">
                            {{ $booking->customer_phone ?? '—' }}
                        </div>
                    </div>
                </div>

                @if(!empty($booking->customer_note))
                    <div class="mb-2">
                        <div class="small text-muted">Ghi chú</div>
                        <div>{{ $booking->customer_note }}</div>
                    </div>
                @endif

                <div class="small text-muted mt-2">
                    Vui lòng đến bến trước giờ xuất bến ít nhất 30 phút để làm thủ tục.
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT: Tóm tắt thanh toán --}}
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0">
                <strong>Thông tin thanh toán</strong>
            </div>
            <div class="card-body">

                @php
                    $seatCount = count($seats ?? []);
                    $pricePerSeat = optional($booking->trip)->gia_ve ?? 0;
                    $total = $booking->total_amount ?? ($seatCount * $pricePerSeat);
                @endphp

                <div class="d-flex justify-content-between mb-1 small">
                    <span>Giá vé / ghế</span>
                    <span>
                        {{ number_format($pricePerSeat, 0, '.', '.') }}đ
                    </span>
                </div>

                <div class="d-flex justify-content-between mb-1 small">
                    <span>Số ghế</span>
                    <span>{{ $seatCount }}</span>
                </div>

                <div class="d-flex justify-content-between mb-2 small">
                    <span>Mã đặt vé</span>
                    <span>#{{ $booking->id }}</span>
                </div>

                <hr>

                <div class="d-flex justify-content-between mb-2 fw-bold">
                    <span>Tổng tiền</span>
                    <span class="text-danger">
                        {{ number_format($total, 0, '.', '.') }}đ
                    </span>
                </div>

                <div class="mb-3 small text-muted">
                    Trạng thái:
                    <strong>
                        {{ $booking->status_text ?? ($booking->status ?? 'Đã tạo') }}
                    </strong>
                </div>

                <a href="{{ route('client.home') }}" class="btn btn-primary w-100 mb-2">
                    Về trang chủ
                </a>

                <a href="{{ route('client.trips') }}" class="btn btn-outline-secondary w-100">
                    Đặt thêm chuyến khác
                </a>
            </div>
        </div>
    </div>
</div>
</div>


@endsection
