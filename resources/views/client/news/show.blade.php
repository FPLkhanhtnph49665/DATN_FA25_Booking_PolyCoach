@extends('layouts.client')

@section('title', $news->title)

@section('content')
<div class="container my-5">
    <div class="row g-4">

        <!-- NỘI DUNG CHÍNH -->
        <div class="col-lg-8">
            <article class="bg-white p-4 p-lg-5 rounded-4 shadow-sm">

                <h1 class="article-title mb-3">
                    {{ $news->title }}
                </h1>

                <div class="article-meta mb-4">
                    <span>
                        {{ $news->published_at->format('d/m/Y') }}
                    </span>
                    <span class="mx-2">•</span>
                    <span>{{ $news->category }}</span>
                </div>

                <img
                    src="{{ asset($news->thumbnail) }}"
                    alt="{{ $news->title }}"
                    class="article-cover mb-4"
                >

                <div class="article-content">
                    {!! $news->content !!}
                </div>

            </article>
        </div>

        <!-- SIDEBAR -->
        <div class="col-lg-4">
            <aside class="sticky-top" style="top: 90px">

                <!-- TIN LIÊN QUAN -->
                <div class="bg-white p-4 rounded-4 shadow-sm mb-4">
                    <h5 class="fw-bold mb-3">Tin liên quan</h5>

                    <ul class="list-unstyled mb-0">
                        @foreach ($relatedNews as $item)
                            <li class="mb-2">
                                <a
                                    href="{{ route('client.news.show', $item->slug) }}"
                                    class="text-decoration-none text-dark d-block"
                                >
                                    {{ $item->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- CTA -->
                <div class="bg-danger text-white p-4 rounded-4 shadow-sm">
                    <h5 class="fw-bold mb-2">Đặt vé xe ngay</h5>
                    <p class="small mb-3">
                        Chủ động lịch trình – Tết trọn vẹn bên gia đình
                    </p>
                    <a href="#" class="btn btn-light w-100 fw-semibold">
                        Đặt vé ngay
                    </a>
                </div>

            </aside>
        </div>

    </div>
</div>
@endsection
<style>
    .article-title {
    font-size: 28px;
    font-weight: 700;
    line-height: 1.4;
}

.article-meta {
    font-size: 14px;
    color: #777;
}

.article-cover {
    width: 100%;
    border-radius: 14px;
    object-fit: cover;
}

.article-content {
    font-size: 16px;
    line-height: 1.8;
    color: #333;
}

.article-content p {
    margin-bottom: 1rem;
}

</style>
