<div class="card border-0 shadow-sm p-4 rounded-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="fw-bold mb-0 text-uppercase letter-spacing-1">Bộ lọc</h6>
        <a href="{{ url()->current() }}" class="text-decoration-none small text-primary">Xóa tất cả</a>
    </div>

    <form method="GET" action="{{ route('client.trips') }}">
        {{-- Giữ tham số cũ --}}
        <input type="hidden" name="from" value="{{ request('from') }}">
        <input type="hidden" name="to" value="{{ request('to') }}">
        <input type="hidden" name="date" value="{{ request('date') }}">

        {{-- Section: Giờ đi --}}
        <div class="filter-section mb-4">
            <label class="form-label fw-bold small text-muted mb-3">GIỜ ĐI</label>
            <div class="filter-options d-flex flex-column gap-2">
                @php
                    $times = [
                        'sang' => ['label' => 'Sáng sớm', 'range' => '00:00 - 06:00'],
                        'sang2' => ['label' => 'Buổi sáng', 'range' => '06:00 - 12:00'],
                        'chieu' => ['label' => 'Buổi chiều', 'range' => '12:00 - 18:00'],
                        'toi' => ['label' => 'Buổi tối', 'range' => '18:00 - 24:00'],
                    ];
                @endphp
                @foreach($times as $key => $val)
                <label class="d-flex align-items-center cursor-pointer">
                    <input type="checkbox" class="form-check-input me-2" name="time[]" value="{{ $key }}"
                           {{ in_array($key, (array)request('time')) ? 'checked' : '' }}>
                    <div class="small">
                        <span class="d-block fw-semibold">{{ $val['label'] }}</span>
                        <span class="text-muted" style="font-size: 11px;">{{ $val['range'] }}</span>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <hr class="my-4 opacity-50">

        {{-- Section: Loại xe --}}
        <div class="filter-section mb-4">
            <label class="form-label fw-bold small text-muted mb-3">LOẠI XE</label>
            <div class="row g-2">
                @php
                    $types = [
                        'seat' => 'Ghế ngồi',
                        'sleeper' => 'Giường nằm',
                        'limousine' => 'Limousine'
                    ];
                @endphp
                @foreach($types as $val => $label)
                <div class="col-12">
                    <input type="checkbox" class="btn-check" id="type-{{ $val }}" name="bus_type[]" value="{{ $val }}"
                           {{ in_array($val, (array)request('bus_type')) ? 'checked' : '' }}>
                    <label class="btn btn-outline-light text-dark border btn-sm w-100 text-start px-3 py-2" for="type-{{ $val }}">
                        {{ $label }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm mt-2">
            ÁP DỤNG LỌC
        </button>
    </form>
</div>

<style>
    .btn-check:checked + .btn-outline-light {
        background-color: #e7f1ff !important;
        border-color: #0d6efd !important;
        color: #0d6efd !important;
    }
    .cursor-pointer { cursor: pointer; }
    .letter-spacing-1 { letter-spacing: 1px; }
</style>
