{{-- resources/views/admin/contacts/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý Liên hệ')

@section('content')
<div class="mb-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                <i class="bi bi-chat-dots-fill"></i>
                Quản lý danh sách liên hệ
            </h2>
            <p class="text-muted small mb-0">
                Theo dõi và xử lý các yêu cầu, góp ý của khách hàng gửi qua form liên hệ.
            </p>
        </div>
    </div>

    {{-- Thông báo lỗi nhanh (nếu có) --}}
    @if($errors->any())
        <div class="alert alert-danger py-2 small">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Thông báo thành công --}}
    @if(session('success'))
        <div class="alert alert-success py-2 small">
            {{ session('success') }}
        </div>
    @endif

    {{-- Bộ lọc / tìm kiếm --}}
    <div class="card border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.contacts.index') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="search" class="form-label text-muted small mb-1">Tìm kiếm</label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        class="form-control"
                        placeholder="Tìm theo tên, email hoặc nội dung..."
                        value="{{ request('search') }}"
                    >
                </div>

                <div class="col-md-3">
                    <label for="status" class="form-label text-muted small mb-1">Trạng thái</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Chưa xử lý</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Đã phản hồi</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-search"></i>
                        <span>Lọc</span>
                    </button>
                    <a href="{{ route('admin.contacts.index') }}"
                       class="btn btn-outline-light flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-arrow-repeat"></i>
                        <span>Đặt lại</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Bảng danh sách liên hệ --}}
    <div class="card border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-muted small">#</th>
                            <th class="text-muted small">Tên</th>
                            <th class="text-muted small">Email</th>
                            <th class="text-muted small">Số điện thoại</th>
                            <th class="text-muted small">Nội dung</th>
                            <th class="text-muted small">Trạng thái</th>
                            <th class="text-muted small">Ngày gửi</th>
                            <th class="text-muted small text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contacts as $contact)
                            <tr>
                                <td class="text-muted small">
                                    {{ $contacts->firstItem() + $loop->index }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $contact->name }}
                                </td>

                                <td class="text-muted small">
                                    {{ $contact->email }}
                                </td>

                                <td class="text-muted small">
                                    {{ $contact->phone ?? '-' }}
                                </td>

                                <td class="text-muted small">
                                    {{ \Illuminate\Support\Str::limit($contact->message, 60) }}
                                </td>

                                <td>
                                    @if($contact->status == 0)
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                            <i class="bi bi-hourglass-split me-1"></i>
                                            Chưa xử lý
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Đã phản hồi
                                        </span>
                                    @endif
                                </td>

                                <td class="text-muted small">
                                    {{ $contact->created_at?->format('d/m/Y H:i') ?? '-' }}
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('admin.contacts.show', $contact->id) }}"
                                       class="btn btn-sm btn-outline-info me-1">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <form action="{{ route('admin.contacts.destroy', $contact->id) }}"
                                          method="POST"
                                          class="d-inline-block delete-contact-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    Chưa có liên hệ nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3 px-3 pb-3">
                {{ $contacts->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success_toast'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: @json(session('success_toast')),
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: @json(session('error')),
                showConfirmButton: true
            });
        </script>
    @endif

    <script>
        // SweetAlert2 confirm xóa liên hệ
        document.querySelectorAll('.delete-contact-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc muốn xóa liên hệ này?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush