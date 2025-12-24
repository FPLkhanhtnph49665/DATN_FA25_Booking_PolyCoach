@extends('layouts.client')

@section('title', 'Tin tức – PolyCoach')

@section('content')

<div class="row g-4">
@foreach ($news as $item)
    <div class="col-md-4 d-flex">
        <article class="news-card d-flex flex-column w-100">

            <div class="news-image">
                <span class="news-badge">
                    {{ $item->category }}
                </span>

                <img
                    src="{{ asset($item->thumbnail) }}"
                    alt="{{ $item->title }}"
                    loading="lazy"
                >
            </div>

            <div class="news-body d-flex flex-column flex-grow-1">
                <h5 class="news-title">
                    {{ $item->title }}
                </h5>

                <p class="news-desc">
                    {{ $item->excerpt }}
                </p>

                <a
                    href="{{ route('client.news.show', $item->slug) }}"
                    class="news-link mt-auto"
                >
                    Xem chi tiết <span>→</span>
                </a>
            </div>

        </article>
    </div>
@endforeach
</div>
<style>
    .news-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0,0,0,0.06);
}

.news-image {
    position: relative;
    overflow: hidden;
}

.news-image img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    transition: transform .4s ease;
}

.news-card:hover .news-image img {
    transform: scale(1.08);
}

.news-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: #e63946;
    color: #fff;
    font-size: 13px;
    padding: 4px 10px;
    border-radius: 20px;
    z-index: 2;
}

.news-body {
    padding: 18px;
}

.news-title {
    font-size: 18px;
    font-weight: 600;
    line-height: 1.4;
    margin-bottom: 8px;
}

.news-desc {
    font-size: 15px;
    line-height: 1.6;
    color: #555;
    margin-bottom: 16px;
}

.news-link {
    font-weight: 500;
    color: #1d3557;
    text-decoration: none;
}

.news-link span {
    margin-left: 4px;
    transition: transform .3s;
}

.news-link:hover span {
    transform: translateX(4px);
}

</style>
<div class="mt-4">
    {{ $news->links() }}
</div>

@endsection
