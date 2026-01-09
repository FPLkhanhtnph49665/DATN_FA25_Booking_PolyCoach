@extends('layouts.admin')

@section('title', 'Thùng rác tin tức')

@section('content')
<div class="container-fluid">
    <h4>Thùng rác</h4>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tiêu đề</th>
                <th>Ngày xóa</th>
                <th width="200">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @foreach($news as $item)
            <tr>
                <td>{{ $item->title }}</td>
                <td>{{ $item->deleted_at }}</td>
                <td>
                    <a href="{{ route('admin.news.restore', $item->id) }}" class="btn btn-success btn-sm">
                        Khôi phục
                    </a>
                    <a onclick="return confirm('Xóa vĩnh viễn?')"
                       href="{{ route('admin.news.forceDelete', $item->id) }}"
                       class="btn btn-danger btn-sm">
                        Xóa vĩnh viễn
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $news->links() }}
</div>
@endsection
