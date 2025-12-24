<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::latest('published_at')->paginate(9);
        return view('client.news.index', compact('news'));
    }

    public function show($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();

        $relatedNews = News::where('id', '!=', $news->id)
            ->latest('published_at')
            ->limit(5)
            ->get();

        return view('client.news.show', compact('news', 'relatedNews'));
    }
}
