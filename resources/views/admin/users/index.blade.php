{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý Users')

@section('content')
<div class="mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1 fw-semibold text-light d-flex align-items-center gap-2">
                <i class="bi bi-people-fill"></i>
                Danh sách Users
            </h2>
            <p class="text-muted small mb-0">
                Quản lý tài khoản người dùng hệ thống PolyCoach: tìm kiếm, lọc, cập nhật trạng thái & phân quyền.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary d-flex align-items-center gap-1">
                <i class="bi bi-person-plus-fill"></i>
                <span>Thêm User mới</span>
            </a>
        </div>
    </div>

    {{-- Bộ lọc tìm kiếm --}}
    <div class="card border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-light small mb-1">Từ khóa</label>
                    <input type="text"
                           name="keyword"
                           value="{{ request('keyword') }}"
                           class="form-control"
                           placeholder="Tìm theo họ tên, email, mã user...">
                </div>

                <div class="col-md-3">
                    <label class="form-label text-light small mb-1">Vai trò</label>
                    <select name="role" class="form-select">
                        <option value="">-- Tất cả vai trò --</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Người dùng</option>
                        <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Nhân viên</option>
                        <option value="checker" {{ request('role') == 'checker' ? 'selected' : '' }}>Kiểm soát viên</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-light small mb-1">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Kích hoạt</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Khóa</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-search"></i>
                        <span>Tìm kiếm</span>
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-arrow-repeat"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Bảng danh sách user --}}
    <div class="card border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-muted small">#</th>
                            <th class="text-muted small">Mã User</th>
                            <th class="text-muted small">Họ</th>
                            <th class="text-muted small">Tên</th>
                            {{-- <th class="text-muted small">Full Name</th> --}}
                            <th class="text-muted small">Ảnh</th>
                            <th class="text-muted small">Email</th>
                            <th class="text-muted small">SĐT</th>
                            <th class="text-muted small">Vai trò</th>
                            <th class="text-muted small">Trạng thái</th>
                            <th class="text-muted small">Ngày tạo</th>
                            <th class="text-muted small text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="text-muted small">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-dark border border-primary-subtle">
                                        {{ $user->user_code }}
                                    </span>
                                </td>
                                <td>{{ $user->first_name }}</td>
                                <td>{{ $user->last_name }}</td>
                                {{-- <td class="fw-semibold">{{ $user->full_name }}</td> --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $user->image ? asset('storage/' . $user->image) : asset('default-avatar.png') }}"
                                             alt="Avatar"
                                             class="rounded-circle border border-primary-subtle"
                                             width="42" height="42"
                                             style="object-fit: cover;">
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->masked_phone ?? '-' }}</td>
                                <td>
                                    @php
                                        $roleLabel = [
                                            'admin'   => 'Quản trị viên',
                                            'user'    => 'Người dùng',
                                            'staff'   => 'Nhân viên',
                                            'checker' => 'Kiểm soát viên',
                                        ][$user->role] ?? $user->role;
                                    @endphp

                                    @if($user->role === 'admin')
                                        <span class="badge bg-gradient bg-success">
                                            <i class="bi bi-shield-lock me-1"></i>{{ $roleLabel }}
                                        </span>
                                    @elseif($user->role === 'staff')
                                        <span class="badge bg-info">
                                            <i class="bi bi-person-workspace me-1"></i>{{ $roleLabel }}
                                        </span>
                                    @elseif($user->role === 'checker')
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-search-heart me-1"></i>{{ $roleLabel }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-person me-1"></i>{{ $roleLabel }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->status == 1)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 0.55rem;"></i>
                                            Hoạt động
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                            <i class="bi bi-slash-circle me-1"></i>
                                            Khóa tài khoản
                                        </span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    {{ $user->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                       class="btn btn-sm btn-outline-info me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('admin.users.destroy', $user->id) }}"
                                          method="POST"
                                          class="d-inline-block delete-user-form">
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
                                <td colspan="12" class="text-center py-4 text-muted">
                                    Chưa có user nào trong hệ thống.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3 px-3 pb-3">
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: @json(session('success')),
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
        // SweetAlert2 xác nhận xóa
        document.querySelectorAll('.delete-user-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc muốn xóa user này?',
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

