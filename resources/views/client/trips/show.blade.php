{{-- resources/views/client/trips/show.blade.php --}}
@extends('layouts.client')

@section('title', 'Chi tiết chuyến đi')

@section('content')
<div class="container my-5">

    <h2 class="mb-4">Chi tiết chuyến đi</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4 class="card-title">{{ $trip->from }} → {{ $trip->to }}</h4>
            <p class="card-text mb-2">
                <strong>Ngày giờ khởi hành:</strong> {{ \Carbon\Carbon::parse($trip->departure_time)->format('d/m/Y H:i') }}
            </p>
            <p class="card-text mb-2">
                <strong>Nhà xe:</strong> {{ $trip->bus->name ?? 'Chưa có thông tin' }}
            </p>
            <p class="card-text mb-2">
                <strong>Loại xe:</strong> {{ $trip->bus->type ?? 'Chưa có thông tin' }}
            </p>
            <p class="card-text mb-2">
                <strong>Số ghế:</strong> {{ $trip->bus->seat_count ?? 'Chưa có thông tin' }}
            </p>
            <p class="card-text mb-2">
                <strong>Giá vé:</strong> {{ number_format($trip->price, 0, ',', '.') }} đ
            </p>
            <p class="card-text mb-2">
                <strong>Mô tả:</strong> {{ $trip->description ?? 'Không có mô tả' }}
            </p>
        </div>
    </div>

    {{-- Form đặt vé --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">Đặt vé ngay</h5>
            <form action="{{ route('client.bookings.store') }}" method="POST">
                @csrf
                <input type="hidden" name="trip_id" value="{{ $trip->id }}">

                <div class="mb-3">
                    <label for="customer_name" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" name="customer_name" id="customer_name" required>
                </div>

                <div class="mb-3">
                    <label for="customer_phone" class="form-label">Số điện thoại</label>
                    <input type="text" class="form-control" name="customer_phone" id="customer_phone" required>
                </div>

                <div class="mb-3">
                    <label for="seats" class="form-label">Số ghế muốn đặt</label>
                    <input type="number" class="form-control" name="seats" id="seats" min="1" max="{{ $trip->bus->seat_count ?? 1 }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Đặt vé</button>
            </form>
        </div>
    </div>

</div>
@endsection
