@extends('layouts.checker')

@section('title', 'Danh sách vé')

@section('content')

    <h3 class="mb-4">Danh sách vé</h3>

    {{-- ===========================
    BỘ LỌC TÌM KIẾM
    =========================== --}}
    <div class="card mb-4">
        <div class="card-body">

            <form method="GET" class="row g-3">

                {{-- Mã vé --}}
                <div class="col-md-3">
                    <label class="form-label">Mã vé</label>
                    <input type="text" name="code" value="{{ request('code') }}" class="form-control"
                        placeholder="Nhập mã vé...">
                </div>

                {{-- Trạng thái --}}
                <div class="col-md-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="checked" {{ request('status') == 'checked' ? 'selected' : '' }}>Đã kiểm</option>
                        <option value="unchecked" {{ request('status') == 'unchecked' ? 'selected' : '' }}>Chưa kiểm</option>
                    </select>
                </div>

                {{-- Tuyến --}}
                <div class="col-md-4">
                    <label class="form-label">Tuyến</label>
                    <select name="route_id" class="form-select">
                        <option value="">-- Tất cả tuyến --</option>
                        @foreach($routes as $r)
                            <option value="{{ $r->id }}" {{ request('route_id') == $r->id ? 'selected' : '' }}>
                                {{ $r->fromCity->name }} → {{ $r->toCity->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Button --}}
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Lọc
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- ===========================
    BẢNG VÉ
    =========================== --}}

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Mã vé</th>
                    <th>Chuyến</th>
                    <th>SL</th>
                    <th>Giá</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th class="text-center" style="width: 70px;">Xem</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($tickets as $ticket)
                    @php
                        $trip = $ticket->trip;
                        $route = $trip?->route;
                        $pCount = $ticket->passengers->count();
                    @endphp

                    <tr>

                        {{-- Mã vé --}}
                        <td>
                            <span class="fw-bold" data-bs-toggle="tooltip" title="Mã vé: {{ $ticket->code }}">
                                {{ $ticket->code }}
                            </span>
                        </td>

                        {{-- Chuyến --}}
                        <td>
                            @if($trip && $route)
                                <div>
                                    <i class="bi bi-geo-alt text-primary"></i>
                                    {{ $route->fromCity->name }} → {{ $route->toCity->name }}
                                </div>

                                <small class="text-muted">
                                    <i class="bi bi-clock"></i>
                                    {{ $trip->departure_date?->format('d/m/Y') }} • {{ $trip->departure_time }}
                                </small>
                            @else
                                ---
                            @endif
                        </td>

                        {{-- SL hành khách & ghế --}}
                        <td>
                            <span data-bs-toggle="tooltip"
                                title="Ghế: {{ $ticket->passengers->pluck('seat_number')->join(', ') }}">
                                <i class="bi bi-people"></i> {{ $pCount }} khách
                                <br>
                                <i class="bi bi-chair"></i> {{ $pCount }} ghế
                            </span>
                        </td>

                        {{-- Giá --}}
                        <td>{{ number_format($ticket->price) }}đ</td>

                        {{-- Trạng thái với màu Vexere-style --}}
                        <td>
                            @if($ticket->checked_at)
                                <span class="badge bg-success px-3 py-2">
                                    <i class="bi bi-check-circle"></i> Đã kiểm
                                </span>
                            @else
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    <i class="bi bi-exclamation-circle"></i> Chưa kiểm
                                </span>
                            @endif
                        </td>

                        {{-- Ngày tạo --}}
                        <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>

                        {{-- Action --}}
                        <td class="text-center">
                            <a href="{{ route('checker.tickets.show', $ticket->id) }}" class="btn btn-sm btn-primary"
                                data-bs-toggle="tooltip" title="Xem chi tiết">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>

                    </tr>
                @empty

                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">
                            Không có vé nào.
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

@push('scripts')
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endpush
