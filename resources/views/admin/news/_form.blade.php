{{-- Title --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Tiêu đề</label>
    <input type="text"
           name="title"
           class="form-control"
           value="{{ old('title', $news->title ?? '') }}"
           required>
</div>

{{-- Category --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Danh mục</label>
    <input type="text"
           name="category"
           class="form-control"
           value="{{ old('category', $news->category ?? '') }}">
</div>

{{-- Excerpt --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Mô tả ngắn</label>
    <textarea name="excerpt"
              class="form-control"
              rows="3">{{ old('excerpt', $news->excerpt ?? '') }}</textarea>
</div>

{{-- Content --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Nội dung bài viết</label>
    <textarea name="content"
              class="form-control"
              rows="8"
              required>{{ old('content', $news->content ?? '') }}</textarea>
</div>

{{-- Thumbnail --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Ảnh đại diện</label>
    <input type="file" name="thumbnail" class="form-control">

    @if (!empty($news?->thumbnail))
        <img src="{{ asset('storage/' . $news->thumbnail) }}"
             alt="Thumbnail"
             class="mt-2 rounded border"
             width="150">
    @endif
</div>

<div class="row">
    {{-- Featured --}}
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Đánh dấu</label>
        <select name="is_featured" class="form-select">
            <option value="0"
                {{ old('is_featured', $news->is_featured ?? 0) == 0 ? 'selected' : '' }}>
                Tin thường
            </option>
            <option value="1"
                {{ old('is_featured', $news->is_featured ?? 0) == 1 ? 'selected' : '' }}>
                Tin nổi bật
            </option>
        </select>
    </div>

    {{-- Status --}}
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Trạng thái bài viết</label>
        <select name="status" class="form-select">
            <option value="draft"
                {{ old('status', $news->status ?? 'draft') === 'draft' ? 'selected' : '' }}>
                Bản nháp
            </option>
            <option value="published"
                {{ old('status', $news->status ?? '') === 'published' ? 'selected' : '' }}>
                Đăng ngay
            </option>
        </select>

        @if(!empty($news?->published_at))
            <small class="text-muted">
                Đã đăng lúc: {{ $news->published_at->format('d/m/Y H:i') }}
            </small>
        @endif
    </div>
</div>
