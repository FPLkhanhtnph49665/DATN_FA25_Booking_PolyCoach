@extends('layouts.admin')

@section('title', 'Point Fares List')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">Point Fares</h1>
            <a href="{{ route('admin.point_fares.create') }}" class="btn btn-primary">Thêm giá vé mới</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($pointFares->count())
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Tuyến xe</th>
                            <th>Điểm đón</th>
                            <th>Điểm trả</th>
                            <th>Giá vé</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pointFares as $pointFare)
                            <tr>
                                <td>{{ $loop->iteration + ($pointFares->currentPage() - 1) * $pointFares->perPage() }}</td>
                                <td>
                                    {{ $pointFare->route->fromCity->name ?? '---' }}
                                    → {{ $pointFare->route->toCity->name ?? '---' }}
                                </td>
                                <td>{{ $pointFare->pickupPoint->name ?? '---' }}</td>
                                <td>{{ $pointFare->dropoffPoint->name ?? '---' }}</td>
                                <td>{{ number_format($pointFare->price, 0, ',', '.') }} VNĐ</td>
                                <td>{{ $pointFare->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.point_fares.edit', $pointFare->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a>

                                    <form action="{{ route('admin.point_fares.destroy', $pointFare->id) }}" method="POST"
                                        style="display:inline-block;" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-3 px-3 pb-3">
                {{ $pointFares->links('pagination::bootstrap-4') }}
            </div>
        @else
            <p>No point fares found.</p>
        @endif
    </div>
@endsection
