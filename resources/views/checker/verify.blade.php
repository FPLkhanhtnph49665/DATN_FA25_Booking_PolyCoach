@extends('layouts.checker')
@section('title', 'Kiểm tra vé')

@section('content')

<h3 class="mb-3"><i class="bi bi-upc-scan me-2"></i> Kiểm tra vé</h3>

<form action="{{ route('checker.check') }}" method="POST" class="row g-3 mb-4">
    @csrf

    <div class="col-md-6">
        <label class="form-label">Mã vé</label>
        <input type="text" name="code" class="form-control" placeholder="Nhập mã vé..." required>
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <button class="btn btn-primary w-100">
            <i class="bi bi-search"></i> Kiểm tra
        </button>
    </div>
</form>

{{-- SUCCESS --}}
@if(session('success'))
    <div class="alert alert-success">
        <strong>{{ session('success') }}</strong>
    </div>

    <div class="card mt-3">
        <div class="card-header fw-bold">
            Thông tin vé
        </div>
        <div class="card-body">
            <pre class="mb-0">{{ print_r(session('ticket'), true) }}</pre>
        </div>
    </div>
@endif

{{-- ERROR --}}
@if(session('error'))
    <div class="alert alert-danger mt-3">
        <strong>{{ session('error') }}</strong>
    </div>
@endif

@endsection
