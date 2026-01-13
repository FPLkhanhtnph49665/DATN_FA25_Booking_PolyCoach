{{-- resources/views/checker/tickets/show.blade.php --}}
@extends('layouts.checker')

@section('title', 'Chi tiết vé')

@section('content')

    <h3 class="mb-3">Chi tiết vé #{{ $ticket->code }}</h3>

    <div class="card mb-4">
        <div class="card-body">

            {{-- Thông tin vé --}}
            <h5 class="mb-3 fw-bold">Thông tin vé</h5>
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
                    <th>Thanh toán</th>
                    <td>{{ $ticket->payment_method }}</td>
                </tr>
                <tr>
                    <th>Trạng thái:</th>
                    <td>
                        @if ($ticket->status === 'paid')
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle"></i> Khách đã thanh toán
                            </span>
                        @elseif($ticket->status === 'cancelled')
                            <span class="badge bg-danger">
                                <i class="bi bi-x-circle"></i> Khách đã hủy chuyến
                            </span>
                        @else
                            {{-- Mặc định là pending --}}
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-clock"></i> Chưa kiểm
                            </span>
                        @endif
                    </td>
                </tr>
                {{-- được check bởi --}}
                <tr>
                    <th>Được kiểm bởi:</th>
                    <td>{{ $ticket->checker->full_name ?? '---' }}</td>
                </tr>
            </table>

            {{-- Thông tin chuyến --}}
            <h5 class="mt-4 mb-3 fw-bold">Thông tin chuyến</h5>
            @if ($ticket->trip)
                <table class="table table-borderless">
                    <tr>
                        <th>Đi từ:</th>
                        <td>{{ $ticket->trip->route->fromCity->name ?? '---' }}</td>
                    </tr>
                    <tr>
                        <th>Đến:</th>
                        <td>{{ $ticket->trip->route->toCity->name ?? '---' }}</td>
                    </tr>
                    {{-- Thêm Điểm đón và Điểm trả cụ thể --}}
                    <tr>
                        <th>Mã chuyến:</th>
                        <td>{{ $ticket->trip->trip_code ?? '---' }}</td>
                    </tr>
                    <tr>
                        <th>Ngày khởi hành:</th>
                        <td>{{ $ticket->trip->departure_date?->format('d/m/Y') ?? '---' }}</td>
                    </tr>
                    <tr>
                        <th>Giờ khởi hành:</th>
                        <td>{{ $ticket->trip->departure_time->format('H:i') ?? '---' }}</td>
                    </tr>
                </table>
            @else
                <p class="text-muted">Không có thông tin chuyến.</p>
            @endif

            {{-- Hành khách --}}
            <h5 class="mt-4 mb-3 fw-bold">Hành khách</h5>
            @if ($ticket->user)
                <table class="table table-borderless">
                    <tr>
                        <th>Họ và tên:</th>
                        <td>{{ $ticket->user->full_name ?? '---' }}</td>
                    </tr>
                    <tr>
                        <th>Số điện thoại:</th>
                        <td>{{ $ticket->user->phone ?? '---' }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $ticket->user->email ?? '---' }}</td>
                    </tr>
                    <tr>

                    </tr>
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
