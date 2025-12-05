{{-- resources/views/checker/tickets/show.blade.php --}}
@extends('layouts.checker')

@section('title', 'Chi tiết vé')

@section('content')

<h3 class="mb-3">Chi tiết vé #{{ $ticket->code }}</h3>

<div class="card mb-4">
    <div class="card-body">

        {{-- Thông tin vé --}}
        <h5 class="mb-3">Thông tin vé</h5>
        <table class="table table-borderless">
            <tr>
                <th>Mã vé:</th>
                <td>{{ $ticket->code ?? '---' }}</td>
            </tr>
            <tr>
                <th>Giá:</th>
                <td>{{ number_format($ticket->price ?? 0) }}đ</td>
            </tr>
            <tr>
                <th>Trạng thái:</th>
                <td>
                    @if($ticket->checked_at)
                        <span class="badge bg-success">Đã kiểm tra</span>
                    @else
                        <span class="badge bg-warning text-dark">Chưa kiểm</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Ngày tạo:</th>
                <td>{{ $ticket->created_at?->format('d/m/Y H:i') ?? '---' }}</td>
            </tr>
        </table>

        {{-- Thông tin chuyến --}}
        <h5 class="mt-4 mb-3">Thông tin chuyến</h5>
        @if($ticket->trip)
            <table class="table table-borderless">
                <tr>
                    <th>Đi từ:</th>
                    <td>{{ $ticket->trip->route->fromCity->name ?? '---' }}</td>
                </tr>
                <tr>
                    <th>Đến:</th>
                    <td>{{ $ticket->trip->route->toCity->name ?? '---' }}</td>
                </tr>
                <tr>
                    <th>Mã chuyến:</th>
                    <td>{{ $ticket->trip->ma_chuyen ?? '---' }}</td>
                </tr>
                <tr>
                    <th>Ngày khởi hành:</th>
                    <td>{{ $ticket->trip->departure_date?->format('d/m/Y') ?? '---' }}</td>
                </tr>
                <tr>
                    <th>Giờ khởi hành:</th>
                    <td>{{ $ticket->trip->departure_time ?? '---' }}</td>
                </tr>
            </table>
        @else
            <p class="text-muted">Không có thông tin chuyến.</p>
        @endif

        {{-- Hành khách --}}
        <h5 class="mt-4 mb-3">Hành khách</h5>
        @if($ticket->passengers && $ticket->passengers->count())
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên hành khách</th>
                        <th>Ghế</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ticket->passengers as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $p->name ?? '---' }}</td>
                            <td>{{ $p->seat_number ?? '---' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted">Không có hành khách.</p>
        @endif

        {{-- Back button --}}
        <div class="mt-4">
            <a href="{{ route('checker.tickets.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại danh sách vé
            </a>
        </div>
    </div>
</div>

@endsection
