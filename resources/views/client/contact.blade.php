@extends('layouts.client')

@section('content')

<style>
    body {
        background-color: #f5f5f5;
    }

    /* ===== HERO CAM GIỐNG FUTA ===== */
    .contact-hero {
        background: linear-gradient(135deg, #ff6a00 0%, #ff9933 50%, #ff6a00 100%);
        color: #fff;
        padding: 24px 0 70px;
        margin-bottom: -40px;
    }
    .contact-hero-title {
        font-size: 22px;
        font-weight: 700;
    }
    .contact-hero-sub {
        font-size: 14px;
        opacity: .9;
    }

    /* ===== WRAPPER CHÍNH ===== */
    .contact-wrapper {
        margin-top: 40px;
        margin-bottom: 40px;
    }

    .contact-card {
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        border: 1px solid #eee;
        padding: 24px 28px;
    }

    .contact-card h5 {
        font-size: 18px;
        font-weight: 600;
    }

    .contact-info-box {
        border-radius: 12px;
        background: #fff7f0;
        border: 1px solid #ffd2aa;
        padding: 16px 18px;
        font-size: 14px;
        margin-bottom: 16px;
    }

    .contact-info-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }
    .contact-info-icon {
        width: 30px;
        height: 30px;
        border-radius: 999px;
        background: #ff595e;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 16px;
        flex-shrink: 0;
    }

    .btn-main {
        background-color: #ff595e;
        border-color: #ff595e;
        color: #fff;
        border-radius: 999px;
        padding: 8px 24px;
        font-weight: 600;
    }
    .btn-main:hover {
        background-color: #ff505e;
        border-color: #ff505e;
        color: #fff;
    }
</style>
<div class="container contact-wrapper">
    <div class="row">
        {{-- CỘT TRÁI: FORM LIÊN HỆ --}}
        <div class="col-lg-8 mb-3">
            <div class="contact-card">
                <h5 class="mb-3">Gửi liên hệ</h5>

                @if(session('success'))
                    <div class="alert alert-success py-2">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger py-2">
                        <ul class="mb-0 small">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('client.contact.submit') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', auth()->user()->full_name ?? '') }}"
                               required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', auth()->user()->phone ?? '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', auth()->user()->email ?? '') }}"
                                   required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Chủ đề</label>
                        <input type="text" name="subject"
                               class="form-control @error('subject') is-invalid @enderror"
                               value="{{ old('subject') }}"
                               placeholder="Ví dụ: Hỗ trợ đặt vé, phản ánh chất lượng dịch vụ...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                        <textarea name="message" rows="5"
                                  class="form-control @error('message') is-invalid @enderror"
                                  required>{{ old('message') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-main">
                            Gửi liên hệ
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- CỘT PHẢI: THÔNG TIN CÔNG TY / HOTLINE --}}
        <div class="col-lg-4">
            <div class="contact-info-box">
                <div class="fw-semibold mb-2">Trung tâm tổng đài & CSKH</div>

                <div class="contact-info-row">
                    <div class="contact-info-icon">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Hotline</div>
                        <div class="fw-bold fs-5 text-danger">1900 6067</div>
                    </div>
                </div>

                <div class="contact-info-row">
                    <div class="contact-info-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Email</div>
                        <div>hotro@polycoach.vn</div>
                    </div>
                </div>

                <div class="contact-info-row">
                    <div class="contact-info-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Địa chỉ</div>
                        <div>Phố Trịnh Văn Bô, Nam Từ Liêm, Hà Nội</div>
                    </div>
                </div>

                <hr>

                <div class="small text-muted">
                    Thời gian làm việc: 7:00 – 21:00, tất cả các ngày trong tuần (bao gồm ngày lễ).
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
