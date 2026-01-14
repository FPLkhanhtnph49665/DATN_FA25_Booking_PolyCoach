{{-- resources/views/checker/tickets/show.blade.php --}}
@extends('layouts.checker')

@section('title', 'Chi tiết vé')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('checker.tickets.index') }}" class="btn btn-light shadow-sm me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h3 class="mb-0">Chi tiết vé <span class="text-primary">#{{ $ticket->code }}</span></h3>
    </div>

    <div class="row g-4">
        {{-- Cột trái: Thông tin chuyến đi & Trạng thái --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <h5 class="fw-bold text-uppercase small text-white">Thông tin hành trình</h5>
                        <div>
                            @if ($ticket->status === 'paid')
                                <span class="badge rounded-pill bg-success px-3">
                                    <i class="bi bi-check-circle me-1"></i> Đã thanh toán
                                </span>
                            @elseif($ticket->status === 'cancelled')
                                <span class="badge rounded-pill bg-danger px-3">
                                    <i class="bi bi-x-circle me-1"></i> Đã hủy
                                </span>
                            @else
                                <span class="badge rounded-pill bg-warning text-dark px-3">
                                    <i class="bi bi-clock me-1"></i> Chờ kiểm tra
                                </span>
                            @endif
                        </div>
                    </div>

                    @if ($ticket->trip)
                        <div class="row mb-4">
                            <div class="col-md-5 text-center">
                                <p class="text-white mb-1 small">Điểm đi</p>
                                <h4 class="fw-bold">{{ $ticket->trip->route->fromCity->name ?? '---' }}</h4>
                            </div>
                            <div class="col-md-2 d-flex align-items-center justify-content-center">
                                <i class="bi bi-arrow-right text-primary fs-3 d-none d-md-block"></i>
                                <i class="bi bi-arrow-down text-primary fs-3 d-md-none"></i>
                            </div>
                            <div class="col-md-5 text-center">
                                <p class="text-white mb-1 small">Điểm đến</p>
                                <h4 class="fw-bold">{{ $ticket->trip->route->toCity->name ?? '---' }}</h4>
                            </div>
                        </div>

                        <hr class="text-black-50 shadow-sm">

                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <label class="text-white small d-block">Mã chuyến</label>
                                <span class="fw-bold">{{ $ticket->trip->trip_code ?? '---' }}</span>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="text-white small d-block">Ngày khởi hành</label>
                                <span class="fw-bold">{{ $ticket->trip->departure_date?->format('d/m/Y') ?? '---' }}</span>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="text-white small d-block">Giờ khởi hành</label>
                                <span class="fw-bold text-primary">{{ $ticket->trip->departure_time->format('H:i') ?? '---' }}</span>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="text-white small d-block">Biển số xe</label>
                                <span class="badge bg-dark">{{ $ticket->trip->bus->plate_number ?? '---' }}</span>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-light text-center">Không có thông tin chuyến đi.</div>
                    @endif
                </div>
            </div>

            {{-- Thông tin thanh toán & Checker --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-uppercase small text-white mb-3">Chi tiết giao dịch</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <p class="mb-1 text-white">Giá vé:</p>
                            <h5 class="text-danger fw-bold">{{ number_format($ticket->price ?? 0) }}đ</h5>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-white">Phương thức:</p>
                            <p class="fw-bold">{{ $ticket->payment_method ?? '---' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-white">Kiểm soát bởi:</p>
                            <p class="fw-bold text-info"><i class="bi bi-person-badge"></i> {{ $ticket->checker->full_name ?? 'Chưa kiểm' }}</p>
                            <p class="fw-bold text-info"><i class="bi bi-telephone"></i> {{ $ticket->checker->phone ?? 'Chưa có số điện thoại' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cột phải: Thông tin hành khách --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-uppercase small text-white mb-4">Thông tin hành khách</h5>
                    @if ($ticket->user)
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-person fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $ticket->user->full_name }}</h6>
                                <small class="text-white">Hành khách</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="text-white small d-block"><i class="bi bi-telephone me-1"></i> Số điện thoại</label>
                            <p class="fw-normal">{{ $ticket->user->phone ?? '---' }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="text-white small d-block"><i class="bi bi-envelope me-1"></i> Email</label>
                            <p class="fw-normal text-truncate">{{ $ticket->user->email ?? '---' }}</p>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-person-x fs-1 text-muted"></i>
                            <p class="text-muted">Không có thông tin khách hàng</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card { border-radius: 12px; }
    .badge { font-weight: 500; }
    label { margin-bottom: 2px; }
    hr { opacity: 0.1; }
</style>
@endsection