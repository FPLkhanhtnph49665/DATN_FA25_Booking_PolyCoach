{{-- resources/views/admin/contacts/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý Liên hệ')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Danh sách liên hệ</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Nội dung</th>
                <th>Trạng thái</th>
                <th>Ngày gửi</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contacts as $contact)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ $contact->phone ?? '-' }}</td>
                    <td>{{ Str::limit($contact->message, 50) }}</td>
                    <td>
                        @if($contact->status == 0)
                            <span class="badge bg-warning text-dark">Chưa xử lý</span>
                        @else
                            <span class="badge bg-success">Đã phản hồi</span>
                        @endif
                    </td>
                    <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-sm btn-info">Xem</a>

                        <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" class="d-inline-block"
                            onsubmit="return confirm('Bạn có chắc muốn xóa liên hệ này?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Chưa có liên hệ nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-end mt-3">
        {{ $contacts->links('pagination::bootstrap-4') }}
    </div>
@endsection
