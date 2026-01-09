@extends('layouts.admin')

@section('title', 'Chi tiết tin tức')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            {{-- Card bài viết --}}
            <div class="card shadow-sm border-0">

                {{-- Thumbnail --}}
                @if ($news->thumbnail)
                    <img src="{{ asset('storage/' . $news->thumbnail) }}"
                         class="card-img-top"
                         style="max-height: 420px; object-fit: cover;"
                         alt="{{ $news->title }}">
                @endif

                <div class="card-body px-4 py-4">

                    {{-- Category + Status --}}
                    <div class="mb-2">
                        <span class="badge bg-primary me-2">
                            {{ $news->category }}
                        </span>

                        @if ($news->status === 'published')
                            <span class="badge bg-success">Đã đăng</span>
                        @else
                            <span class="badge bg-secondary">Bản nháp</span>
                        @endif
                    </div>

                    {{-- Title --}}
                    <h2 class="fw-bold mb-3">
                        {{ $news->title }}
                    </h2>

                    {{-- Meta --}}
                    <div class="text-white small mb-4">
                        <i class="bi bi-calendar-event me-1"></i>
                        {{ $news->published_at?->format('d/m/Y H:i') ?? 'Chưa xuất bản' }}
                    </div>

                    {{-- Excerpt --}}
                    @if ($news->excerpt)
                        <div class="alert alert-light border-start border-4 border-primary">
                            <strong>Tóm tắt:</strong><br>
                            {{ $news->excerpt }}
                        </div>
                    @endif

                    {{-- Content --}}
                    <div class="news-content fs-6 lh-lg">
                        {!! nl2br(e($news->content)) !!}
                    </div>

                </div>

                {{-- Footer --}}
                <div class="card-footer bg-white border-0 d-flex justify-content-between px-4 py-3">
                    <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Quay lại
                    </a>

                    <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil-square me-1"></i> Chỉnh sửa
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
