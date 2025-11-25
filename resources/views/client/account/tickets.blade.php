@extends('layouts.client')

@section('content')

<style>
    body {
        background-color: #f5f5f5;
    }

    .account-hero {
        background: linear-gradient(135deg, #ff595e 0%, #ff9933 50%, #ff595e 100%);
        color: #fff;
        padding: 24px 0 70px;
        margin-bottom: -40px;
    }
    .account-hero-title {
        font-size: 22px;
        font-weight: 700;
    }

    .account-wrapper {
        margin-top: 40px;
        margin-bottom: 40px;
    }

    .account-sidebar {
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        border: 1px solid #eee;
        padding: 10px 0;
    }
    .account-menu-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        font-size: 14px;
        color: #444;
        text-decoration: none;
    }
    .account-menu-item:hover {
        background: #fff7f0;
    }
    .account-menu-item.active {
        background: #e8f3ff;
        font-weight: 600;
        border-left: 3px solid #1e88e5;
    }
    .account-menu-icon {
        width: 28px;
        height: 28px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: #fff;
    }
    .icon-green { background: #00b14f; }
    .icon-orange{ background: #ff595e; }
    .icon-blue  { background: #1e88e5; }
    .icon-red   { background: #f44336; }
    .icon-gray  { background: #9e9e9e; }

    .account-main-card {
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        border: 1px solid #eee;
        padding: 24px 28px;
    }

    .ticket-filter-row input,
    .ticket-filter-row select {
        font-size: 13px;
        height: 36px;
    }

    .btn-main {
        background-color: rgba(255, 89, 94);
        border-color: rgba(255, 89, 94);
        color: #fff;
        border-radius: 999px;
        padding: 6px 20px;
        font-weight: 600;
    }
    .btn-main:hover {
        background-color: rgba(255, 89, 94);
        border-color: rgba(255, 89, 94);
        color: #fff;
    }

    .table-ticket th {
        font-size: 13px;
        background: #fafafa;
    }
    .table-ticket td {
        font-size: 13px;
        vertical-align: middle;
    }

    .no-data {
        text-align: center;
        padding: 40px 0;
        color: #999;
        font-size: 13px;
    }
</style>

@php
    /** @var \App\Models\User $user */
    $user = $user ?? auth()->user();
@endphp

<div class="container account-wrapper">
    <div class="row">
        {{-- SIDEBAR --}}
        <div class="col-lg-3 mb-3">
            <div class="account-sidebar">
                <a href="javascript:void(0)" class="account-menu-item">
                    <span class="account-menu-icon icon-green">F</span>
                    <span>PoLyCoachPay</span>
                </a>
                <a href="{{ route('client.account.show') }}" class="account-menu-item">
                    <span class="account-menu-icon icon-orange">
                        <i class="bi bi-person"></i>
                    </span>
                    <span>Thông tin tài khoản</span>
                </a>
                <a href="{{ route('client.account.tickets') }}" class="account-menu-item active">
                    <span class="account-menu-icon icon-blue">
                        <i class="bi bi-ticket-perforated"></i>
                    </span>
                    <span>Lịch sử mua vé</span>
                </a>
                <a href="#" class="account-menu-item">
                    <span class="account-menu-icon icon-blue">
                        <i class="bi bi-geo-alt"></i>
                    </span>
                    <span>Địa chỉ của bạn</span>
                </a>
                <a href="{{ route('password.request') }}" class="account-menu-item">
                    <span class="account-menu-icon icon-red">
                        <i class="bi bi-shield-lock"></i>
                    </span>
                    <span>Đặt lại mật khẩu</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="account-menu-item w-100 text-start border-0 bg-transparent">
                        <span class="account-menu-icon icon-gray">
                            <i class="bi bi-box-arrow-right"></i>
                        </span>
                        <span>Đăng xuất</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="col-lg-9">
            <div class="account-main-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Lịch sử mua vé</h5>
                    <a href="{{ route('client.trips') }}" class="btn btn-main">
                        Đặt vé
                    </a>
                </div>

                <p class="small text-muted mb-3">
                    Theo dõi và quản lý quá trình lịch sử mua vé của bạn
                </p>

                {{-- FILTER --}}
                <form method="GET" class="ticket-filter-row mb-3">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-3">
                            <input type="text" name="code" class="form-control"
                                   placeholder="Mã vé"
                                   value="{{ request('code') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date" class="form-control"
                                   value="{{ request('date') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="route" class="form-control"
                                   placeholder="Tuyến đường"
                                   value="{{ request('route') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">Trạng thái</option>
                                <option value="pending"   {{ request('status')=='pending'   ? 'selected' : '' }}>Chờ thanh toán</option>
                                <option value="paid"      {{ request('status')=='paid'      ? 'selected' : '' }}>Đã thanh toán</option>
                                <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        <div class="col-md-2 text-end">
                            <button class="btn btn-outline-secondary btn-sm me-1" type="submit">
                                Tìm
                            </button>
                            <a href="{{ route('client.account.tickets') }}" class="btn btn-outline-secondary btn-sm">
                                Xóa lọc
                            </a>
                        </div>
                    </div>
                </form>

                {{-- TABLE --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-ticket mb-0">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 80px;">Mã vé</th>
                                <th style="width: 80px;">Số ghế</th>
                                <th>Tuyến đường</th>
                                <th style="width: 120px;">Ngày đi</th>
                                <th style="width: 120px;">Số tiền</th>
                                <th style="width: 120px;">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                @php
                                    $trip     = $ticket->trip;
                                    $route    = $trip?->route;
                                    $fromCity = $route?->fromCity;
                                    $toCity   = $route?->toCity;

                                    // Số ghế trong ticket
                                    $qty   = (int)($ticket->seat_number ?? 0);
                                    // Giá vé 1 ghế
                                    $price = $trip ? (float)($trip->ticket_price ?? 0) : 0;
                                    $total = $qty * $price;
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        #{{ $ticket->id }}
                                    </td>
                                    <td class="text-center">
                                        {{ $qty }}
                                    </td>
                                    <td>
                                        @if($route)
                                            {{ $fromCity?->name }} → {{ $toCity?->name }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($trip)
                                            {{ \Carbon\Carbon::parse($trip->departure_date)->format('d/m/Y') }}
                                            <br>
                                            <span class="text-muted small">
                                                {{ \Carbon\Carbon::parse($trip->departure_time)->format('H:i') }}
                                            </span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($total, 0, ',', '.') }}đ
                                    </td>
                                    <td class="text-center">
                                        {!! $ticket->status_label ?? ucfirst($ticket->status) !!}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="no-data">
                                            <div class="mb-2">
                                                🪑
                                            </div>
                                            <div>Hiện chưa có lịch sử mua vé.</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
