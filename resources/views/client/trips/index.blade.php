{{-- resources/views/client/trips/index.blade.php --}}
@extends('layouts.client') {{-- Giả sử bạn có master layout client --}}

@section('title', 'Danh sách chuyến đi')

@section('content')
<div class="container my-5">

    <h2 class="mb-4">Tìm chuyến đi</h2>

    {{-- Form tìm kiếm --}}
    <form action="{{ route('client.searchTrips') }}" method="GET" class="row g-3 mb-5">
        <div class="col-md-3">
            <input type="text" name="from" class="form-control" placeholder="Điểm đi" value="{{ request('from') }}">
        </div>
        <div class="col-md-3">
            <input type="text" name="to" class="form-control" placeholder="Điểm đến" value="{{ request('to') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">Tìm chuyến</button>
        </div>
    </form>

    {{-- Danh sách chuyến --}}
    @if($trips->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach($trips as $trip)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $trip->from }} → {{ $trip->to }}</h5>
                            <p class="card-text mb-1">
                                <strong>Ngày đi:</strong> {{ \Carbon\Carbon::parse($trip->departure_time)->format('d/m/Y H:i') }}
                            </p>
                            <p class="card-text mb-1">
                                <strong>Giá vé:</strong> {{ number_format($trip->price, 0, ',', '.') }} đ
                            </p>
                            <p class="card-text mb-1">
                                <strong>Nhà xe:</strong> {{ $trip->bus->name ?? 'Chưa có thông tin' }}
                            </p>
                            <a href="{{ route('client.trips.show', $trip->id) }}" class="btn btn-outline-primary mt-2">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Phân trang --}}
        <div class="mt-4">
            {{ $trips->withQueryString()->links() }}
        </div>
    @else
        <p>Không tìm thấy chuyến nào phù hợp.</p>
    @endif

</div>
@endsection
