{{-- resources/views/checker/tickets.blade.php --}}
@extends('layouts.checker')

@section('title', 'Danh sách vé')

@section('content')

<h3 class="mb-3">Danh sách vé</h3>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Mã vé</th>
            <th>Chuyến</th>
            <th>Hành khách</th>
            <th>Giá</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        @foreach ($tickets as $ticket)
        <tr>
            <td>{{ $ticket->code }}</td>
            <td>{{ $ticket->trip->route->name ?? '---' }}</td>
            <td>{{ $ticket->passenger->name ?? '---' }}</td>
            <td>{{ number_format($ticket->price) }}đ</td>
            <td>
                @if($ticket->checked_at)
                    <span class="badge bg-success">Đã kiểm tra</span>
                @else
                    <span class="badge bg-warning text-dark">Chưa kiểm</span>
                @endif
            </td>
            <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>

            <td>
                <a href="{{ route('checker.tickets.show', $ticket->id) }}" class="btn btn-sm btn-primary">
                    Xem
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $tickets->links() }}

@endsection
