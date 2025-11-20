@extends('layouts.client')

@section('content')
<div class="container my-5">

    <h4>Trip List</h4>
    @if($trips->count())
        @foreach($trips as $trip)
            <div class="futa-trip-card mb-3">
                {{-- HEADER: departure – duration – arrival + bus type, seats, price --}}
                <div class="futa-trip-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-4">

                        {{-- Departure time --}}
                        <div class="text-center">
                            <div class="fw-bold fs-4">{{ $trip->departure_time_formatted }}</div>
                            <div class="small text-muted">
                                {{ $trip->route->fromCity->name ?? '-' }}
                            </div>
                        </div>

                        {{-- Duration --}}
                        <div class="text-center futa-trip-duration">
                            <div class="small text-muted">
                                {{ $trip->route->estimated_time ?? '4h' }}
                            </div>
                            <div class="futa-dot-line my-1">
                                <span class="dot"></span>
                                <span class="line"></span>
                                <span class="dot"></span>
                            </div>
                            <div class="small text-muted">
                                (Estimated)
                            </div>
                        </div>

                        {{-- Arrival time --}}
                        <div class="text-center">
                            <div class="fw-bold fs-4">
                                {{ $trip->arrival_time_formatted ?? '21:30' }}
                            </div>
                            <div class="small text-muted">
                                {{ $trip->route->toCity->name ?? '-' }}
                            </div>
                        </div>

                    </div>

                    {{-- Bus type – available seats – price --}}
                    <div class="text-end">
                        <div class="small text-muted mb-1">
                            {{ $trip->bus->type ?? 'Limousine' }}
                        </div>
                        <div class="small text-success mb-1">
                            {{ $trip->availableSeats() }} seats available
                        </div>
                        <div class="futa-price">
                            {{ number_format($trip->ticket_price, 0, '.', '.') }}₫
                        </div>
                    </div>
                </div>

                {{-- BODY: route name, note, links + choose button --}}
                <div class="futa-trip-body">
                    <div class="mb-2">
                        <strong>
                            {{ $trip->route->fromCity->name ?? '-' }}
                            – {{ $trip->route->toCity->name ?? '-' }}
                        </strong>
                    </div>

                    <div class="small text-muted mb-3">
                        Note: Any restrictions on pick-up or drop-off areas will be displayed here.
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small futa-links">
                            <a href="javascript:void(0)">Seat map</a>
                            <span> | </span>
                            <a href="javascript:void(0)">Schedule</a>
                            <span> | </span>
                            <a href="javascript:void(0)">Transfer</a>
                            <span> | </span>
                            <a href="javascript:void(0)">Policy</a>
                        </div>

                        <a href="{{ route('client.trips.show',['trip_id' => $trip->id]) }}"
                           class="btn btn-warning rounded-pill px-4 fw-semibold futa-btn-choose">
                            Choose Trip
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p>No trips available.</p>
    @endif
</div>
@endsection

<style>
/* same styles as your original, no changes needed except text colors if desired */
.futa-trip-card {
    border-radius: 12px;
    background: #fff;
    border: 1px solid #eee;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    overflow: hidden;
}

.futa-trip-header {
    padding: 16px 20px;
    border-bottom: 1px solid #f5f5f5;
}

.futa-trip-body {
    padding: 16px 20px 12px;
}

.futa-trip-duration .futa-dot-line {
    display: flex;
    align-items: center;
    gap: 4px;
}

.futa-trip-duration .dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ff7a00;
}

.futa-trip-duration .line {
    flex: 1;
    height: 2px;
    background-image: linear-gradient(to right, #ff7a00 33%, rgba(255, 122, 0, 0) 0%);
    background-position: bottom;
    background-size: 8px 2px;
    background-repeat: repeat-x;
}

.futa-price {
    color: #ff0000;
    font-weight: 700;
    font-size: 1.1rem;
}

.futa-links a {
    color: #444;
    text-decoration: none;
}

.futa-links a:hover {
    text-decoration: underline;
}

.futa-btn-choose {
    background-color: #ff7a00;
    border-color: #ff7a00;
    color: #fff;
}

.futa-btn-choose:hover {
    background-color: #ff8f26;
    border-color: #ff8f26;
    color: #fff;
}
</style>
