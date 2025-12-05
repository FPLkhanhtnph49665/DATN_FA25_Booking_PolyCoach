{{-- resources/views/checker/tickets.blade.php --}}
@extends('layouts.checker')

@section('title', 'Danh sách vé')

@section('content')

<h3 class="mb-3">Danh sách vé</h3>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>Mã vé</th>
                <th>Chuyến</th>
                <th>Hành khách</th>
                <th>Ghế</th>
                <th>Giá</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @forelse ($tickets as $ticket)
            <tr>
                {{-- Mã vé --}}
                <td>{{ $ticket->code ?? '---' }}</td>

                {{-- Chuyến --}}
                <td>
                    @if($ticket->trip)
                        {{ $ticket->trip->route->fromCity->name ?? '---' }}
                        → {{ $ticket->trip->route->toCity->name ?? '---' }}
                        <br>
                        <small class="text-muted">
                            {{ $ticket->trip->departure_date?->format('d/m/Y') ?? '' }}
                            {{ $ticket->trip->departure_time ?? '' }}
                        </small>
                    @else
                        ---
                    @endif
                </td>

                {{-- Hành khách --}}
                <td>
                    @if($ticket->passengers && $ticket->passengers->count())
                        <ul class="mb-0 ps-3">
                            @foreach($ticket->passengers as $p)
                                <li>{{ $p->name ?? '---' }}</li>
                            @endforeach
                        </ul>
                    @else
                        ---
                    @endif
                </td>

                {{-- Ghế --}}
                <td>
                    @if($ticket->passengers && $ticket->passengers->count())
                        <ul class="mb-0 ps-3">
                            @foreach($ticket->passengers as $p)
                                <li>{{ $p->seat_number ?? '---' }}</li>
                            @endforeach
                        </ul>
                    @else
                        ---
                    @endif
                </td>

                {{-- Giá --}}
                <td>{{ number_format($ticket->price ?? 0) }}đ</td>

                {{-- Trạng thái --}}
                <td>
                    @if($ticket->checked_at)
                        <span class="badge bg-success">Đã kiểm tra</span>
                    @else
                        <span class="badge bg-warning text-dark">Chưa kiểm</span>
                    @endif
                </td>

                {{-- Ngày tạo --}}
                <td>{{ $ticket->created_at?->format('d/m/Y H:i') ?? '---' }}</td>

                {{-- Action --}}
                <td>
                    <a href="{{ route('checker.tickets.show', $ticket->id) }}" class="btn btn-sm btn-primary">
                        Xem
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted py-3">
                    Chưa có vé nào.
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

@endsection
