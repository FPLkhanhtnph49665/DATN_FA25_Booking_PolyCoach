@extends('layouts.admin')

@section('title', 'Cập nhật tin tức')

@section('content')
<div class="container-fluid">
    <h4>Cập nhật tin tức</h4>

    <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.news._form')
        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Quay lại</a>
        <button class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection
