@extends('layouts.admin')

@section('title', 'Thêm vé mới')

@section('content')
<div class="mb-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-semibold text-light d-flex align-items-center gap-2">
            <i class="bi bi-plus-circle"></i>
            Thêm vé mới
        </h2>

        <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    {{-- Thông báo lỗi --}}
    @if ($errors->any())
        <div class="alert alert-danger small">
            <strong>Lỗi!</strong> {{ $errors->first() }}
        </div>
    @endif

    <div class="card border-0">
        <div class="card-body">

            <form action="{{ route('admin.tickets.store') }}" method="POST">
                @csrf

                <div class="row g-3">

                    {{-- Chuyến --}}
                    <div class="col-md-6">
                        <label class="form-label">Chuyến xe <span class="text-danger">*</span></label>
                        <select name="trip_id" class="form-select" required>
                            <option value="">-- Chọn chuyến --</option>

                            @foreach($trips as $trip)
                                <option value="{{ $trip->id }}"
                                    {{ old('trip_id') == $trip->id ? 'selected' : '' }}>
                                    {{ $trip->route->fromCity->name ?? 'N/A' }} →
                                    {{ $trip->route->toCity->name ?? 'N/A' }}
                                    ({{ $trip->departure_date?->format('d/m/Y') }} {{ $trip->departure_time }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Người dùng --}}
                    <div class="col-md-6">
                        <label class="form-label">Khách hàng <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Chọn khách hàng --</option>

                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->full_name ?? $user->name }} — {{ $user->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Số ghế --}}
                    <div class="col-md-6">
                        <label class="form-label">Số ghế <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="seat_number"
                            class="form-control"
                            placeholder="VD: A1, A2 hoặc 12, 13"
                            value="{{ old('seat_number') }}"
                            required>
                        <div class="form-text">Nhập nhiều ghế cách nhau bằng dấu phẩy.</div>
                    </div>

                    {{-- Trạng thái --}}
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>

                    {{-- Phương thức thanh toán --}}
                    <div class="col-md-3">
                        <label class="form-label">Thanh toán</label>
                        <select name="payment_method" class="form-select">
                            <option value="">-- Không chọn --</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tiền mặt</option>
                            <option value="vnpay" {{ old('payment_method') == 'vnpay' ? 'selected' : '' }}>VNPay</option>
                            <option value="momo" {{ old('payment_method') == 'momo' ? 'selected' : '' }}>Momo</option>
                        </select>
                    </div>

                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-circle"></i> Lưu vé
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
