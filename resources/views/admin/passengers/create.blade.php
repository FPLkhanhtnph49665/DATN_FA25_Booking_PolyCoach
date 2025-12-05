@extends('layouts.admin')

@section('title', 'Thêm hành khách')

@section('content')
<div class="mb-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-semibold text-light d-flex align-items-center gap-2">
            <i class="bi bi-person-plus"></i>
            Thêm hành khách mới
        </h3>

        <a href="{{ route('admin.passengers.index') }}"
           class="btn btn-outline-light d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card border-0">
        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger small py-2">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.passengers.store') }}" method="POST" class="row g-3">
                @csrf

                {{-- Tên --}}
                <div class="col-md-6">
                    <label class="form-label">Tên hành khách</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name') }}" required>
                </div>

                {{-- SĐT --}}
                <div class="col-md-6">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control"
                           value="{{ old('phone') }}">
                </div>

                {{-- Tuổi --}}
                <div class="col-md-4">
                    <label class="form-label">Tuổi</label>
                    <input type="number" name="age" class="form-control"
                           value="{{ old('age') }}" min="1" max="120">
                </div>

                {{-- Ghế --}}
                <div class="col-md-4">
                    <label class="form-label">Ghế</label>
                    <input type="text" name="seat_number" class="form-control"
                           placeholder="VD: A05" value="{{ old('seat_number') }}" required>
                </div>

                {{-- Vé --}}
                <div class="col-md-4">
                    <label class="form-label">Vé liên quan (tuỳ chọn)</label>
                    <select name="ticket_id" class="form-select">
                        <option value="">-- Không chọn --</option>
                        @foreach($tickets as $ticket)
                            <option value="{{ $ticket->id }}"
                                {{ old('ticket_id') == $ticket->id ? 'selected' : '' }}>
                                #{{ $ticket->id }} - {{ $ticket->user->full_name ?? $ticket->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Chuyến --}}
                <div class="col-md-12">
                    <label class="form-label">Chuyến</label>
                    <select name="trip_id" class="form-select" required>
                        <option value="">-- Chọn chuyến --</option>
                        @foreach($trips as $trip)
                            <option value="{{ $trip->id }}"
                                {{ old('trip_id') == $trip->id ? 'selected' : '' }}>
                                {{ $trip->departure_date->format('d/m/Y') }}
                                - {{ $trip->departure_time }}
                                | Tuyến:
                                {{ $trip->route->fromCity->name }} →
                                {{ $trip->route->toCity->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-primary d-flex align-items-center gap-1">
                        <i class="bi bi-save"></i>
                        <span>Lưu hành khách</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
