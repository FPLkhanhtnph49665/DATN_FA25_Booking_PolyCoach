@extends('layouts.checker')

@section('title', 'Dashboard')

@section('content')

<div class="row g-3">

    {{-- WELCOME CARD --}}
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h2 class="fw-bold mb-2">Xin chào, {{ auth()->user()->full_name ?? auth()->user()->name }} 👋</h2>
                <p class="text-light mb-0">
                    Chào mừng bạn đến hệ thống kiểm soát vé PolyCoach Checker.
                    Hãy chọn chức năng bên menu trái để bắt đầu kiểm tra vé.
                </p>
            </div>
        </div>
    </div>

    {{-- QUICK ACTION --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column">
                <h5 class="fw-semibold mb-3"><i class="bi bi-qr-code-scan me-2"></i>Quét / Kiểm tra mã vé</h5>
                <p class="text-light flex-grow-1">
                    Nhập mã vé hoặc sử dụng thiết bị quét để xác thực vé hợp lệ.
                </p>
                <a href="{{ route('checker.verify') }}" class="btn btn-primary mt-auto">
                    Bắt đầu kiểm tra
                </a>
            </div>
        </div>
    </div>

    {{-- VIEW TICKETS --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column">
                <h5 class="fw-semibold mb-3"><i class="bi bi-ticket-detailed me-2"></i>Danh sách vé</h5>
                <p class="text-light flex-grow-1">
                    Xem danh sách tất cả vé mà bạn có quyền kiểm tra.
                </p>
                <a href="{{ route('checker.tickets.index') }}" class="btn btn-outline-primary mt-auto">
                    Xem danh sách vé
                </a>
            </div>
        </div>
    </div>

</div>

@endsection
