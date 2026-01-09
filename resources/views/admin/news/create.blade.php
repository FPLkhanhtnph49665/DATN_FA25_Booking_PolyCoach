@extends('layouts.admin')

@section('title', 'Thêm tin tức')

@section('content')
<div class="container-fluid">
    <h4>Thêm tin tức</h4>

    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.news._form')
        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Quay lại</a>
        <button class="btn btn-primary">Lưu</button>
    </form>
</div>
@endsection
