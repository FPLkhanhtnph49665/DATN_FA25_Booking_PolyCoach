{{-- resources/views/admin/payments/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tạo Thanh Toán Mới')

@section('content')
<div class="mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                <i class="bi bi-plus-circle"></i>
                Tạo Thanh Toán Mới
            </h2>
            <p class="text-muted small mb-0">
                Điền thông tin thanh toán cho vé và người dùng.
            </p>
        </div>

        <div>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-light d-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i>
                Quay Lại Danh Sách
            </a>
        </div>
    </div>

    {{-- Hiển thị lỗi validate --}}
    @if($errors->any())
        <div class="alert alert-danger py-2 small">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0">
        <div class="card-body">
            <form action="{{ route('admin.payments.store') }}" method="POST">
                @csrf

                {{-- Vé --}}
                <div class="mb-3">
                    <label for="ticket_id" class="form-label">Chọn Vé <span class="text-danger">*</span></label>
                    <select name="ticket_id" id="ticket_id" class="form-select">
                        <option value="">-- Chọn Vé --</option>
                        @foreach($tickets as $ticket)
                            <option value="{{ $ticket->id }}" {{ old('ticket_id') == $ticket->id ? 'selected' : '' }}>
                                Vé #{{ $ticket->id }} - {{ $ticket->trip?->ma_chuyen ?? '---' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Người dùng --}}
                <div class="mb-3">
                    <label for="user_id" class="form-label">Người Dùng <span class="text-danger">*</span></label>
                    <select name="user_id" id="user_id" class="form-select">
                        <option value="">-- Chọn Người Dùng --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->full_name ?? $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Số tiền --}}
                <div class="mb-3">
                    <label for="amount" class="form-label">Số Tiền <span class="text-danger">*</span></label>
                    <input type="number" name="amount" id="amount" class="form-control"
                        value="{{ old('amount') }}" placeholder="Nhập số tiền" min="0" step="0.01">
                </div>

                {{-- Phương thức thanh toán --}}
                <div class="mb-3">
                    <label for="payment_method" class="form-label">Phương Thức <span class="text-danger">*</span></label>
                    <input type="text" name="payment_method" id="payment_method" class="form-control"
                        value="{{ old('payment_method') }}" placeholder="Ví dụ: cash, momo">
                </div>

                {{-- Trạng thái --}}
                <div class="mb-3">
                    <label for="status" class="form-label">Trạng Thái <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select">
                        <option value="">-- Chọn Trạng Thái --</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Chờ Xử Lý</option>
                        <option value="success" {{ old('status') == 'success' ? 'selected' : '' }}>Thành Công</option>
                        <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>Thất Bại</option>
                    </select>
                </div>

                {{-- Mã giao dịch --}}
                <div class="mb-3">
                    <label for="transaction_code" class="form-label">Mã Giao Dịch</label>
                    <input type="text" name="transaction_code" id="transaction_code" class="form-control"
                        value="{{ old('transaction_code') }}" placeholder="Mã giao dịch (tùy chọn)">
                </div>

                {{-- Buttons --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-1">
                        <i class="bi bi-save"></i> Lưu Thanh Toán
                    </button>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-light d-flex align-items-center gap-1">
                        <i class="bi bi-x-circle"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
