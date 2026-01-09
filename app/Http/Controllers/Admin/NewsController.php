<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderByRaw("
                    CASE
                        WHEN status = 'published' THEN 1
                        ELSE 2
                    END
                ")
                ->orderByDesc('published_at')
                ->latest('id')
                ->paginate(10);

        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|max:255',
            'excerpt'     => 'nullable|string',
            'content'     => 'required',
            'category'    => 'required|max:100',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_featured' => 'nullable|boolean',
            'status'      => 'required|in:draft,published',
        ]);

        $data['slug'] = Str::slug($data['title']);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
        }

        $data['published_at'] = $data['status'] === 'published'
            ? now()
            : null;

        News::create($data);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Thêm bài viết thành công');
    }

    public function show(News $news)
    {
        return view('admin.news.show', compact('news'));
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $data = $request->validate([
            'title'       => 'required|max:255',
            'excerpt'     => 'nullable|string',
            'content'     => 'required',
            'category'    => 'required|max:100',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_featured' => 'nullable|boolean',
            'status'      => 'required|in:draft,published',
        ]);

        $data['slug'] = Str::slug($data['title']);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
        }

        // XỬ LÝ CHUYỂN TRẠNG THÁI
        if ($news->status === 'draft' && $data['status'] === 'published') {
            $data['published_at'] = now();
        }

        if ($news->status === 'published' && $data['status'] === 'draft') {
            $data['published_at'] = null;
        }

        $news->update($data);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Cập nhật bài viết thành công');
    }

    public function destroy(News $news)
    {
        $news->delete();

        return back()->with('success', 'Đã chuyển vào thùng rác');
    }

    /* ========== TRASH ========== */

    public function trash()
    {
        $news = News::onlyTrashed()->latest()->paginate(10);
        return view('admin.news.trash', compact('news'));
    }

    public function restore($id)
    {
        News::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Khôi phục thành công');
    }

    public function forceDelete($id)
    {
        News::onlyTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Xóa vĩnh viễn');
    }
}
