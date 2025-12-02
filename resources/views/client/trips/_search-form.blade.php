{{-- resources/views/client/trips/_search-form.blade.php --}}

<div class="pc-search-form-wrapper">
    <form action="{{ route('client.searchTrips') }}" method="GET" class="pc-search-form">
        {{-- Nơi xuất phát --}}
        <div class="pc-field">
            <span class="pc-field-label">Nơi xuất phát</span>
            <span class="pc-field-icon">
                <i class="fas fa-location-dot"></i>
            </span>
            <input type="text" name="from" class="pc-field-input" placeholder="VD: Hà Nội"
                value="{{ request('from') }}" required>
        </div>

        {{-- Nơi đến --}}
        <div class="pc-field">
            <span class="pc-field-label">Nơi đến</span>
            <span class="pc-field-icon">
                <i class="fas fa-map-marker-alt"></i>
            </span>
            <input type="text" name="to" class="pc-field-input" placeholder="VD: Đà Nẵng"
                value="{{ request('to') }}" required>
        </div>

        <form action="{{ route('client.searchTrips') }}" method="GET" class="row g-3 align-items-end">
            {{-- Điểm đi --}}
            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-semibold mb-1">Điểm đi</label>
                <div class="futa-input">
                    <select name="from" class="form-select border-0 shadow-none p-0" required>
                        <option value="">-- Chọn điểm đi --</option>
                        @foreach ($allFrom as $from)
                            <option value="{{ $from }}" {{ request('from') == $from ? 'selected' : '' }}>
                                {{ $from }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Điểm đến --}}
            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-semibold mb-1">Điểm đến</label>
                <div class="futa-input">
                    <select name="to" class="form-select border-0 shadow-none p-0" required>
                        <option value="">-- Chọn điểm đến --</option>
                        @foreach ($allTo as $to)
                            <option value="{{ $to }}" {{ request('to') == $to ? 'selected' : '' }}>
                                {{ $to }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>


            {{-- Ngày đi --}}
            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-semibold mb-1">Ngày đi</label>
                <div class="futa-input">
                    <input type="date" name="date" class="form-control border-0 shadow-none p-0"
                        value="{{ request('date', date('d-m-Y')) }}" required>
                </div>
            </div>

            {{-- Số vé --}}
            <div class="col-lg-2 col-md-4">
                <label class="form-label fw-semibold mb-1">Số vé</label>
                <div class="futa-input">
                    <select name="seats" class="form-select border-0 shadow-none p-0">
                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ request('seats', 1) == $i ? 'selected' : '' }}>
                                {{ $i }} vé
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            {{-- Nút tìm --}}
            <div class="col-12 d-flex justify-content-center mt-2">
                <button type="submit" class="btn btn-search px-5 py-2 fw-semibold">
                    Tìm chuyến xe
                </button>
            </div>
        </form>
</div>
</div>

{{-- ================== CSS ================== --}}
<style>
    .trip-search-wrapper {
        border-radius: 24px;
        border: 1.5px solid #ffd5c2;
        background: #fff7f3;
        padding: 20px 24px;
        transition: all 0.3s;
    }

    .trip-search-wrapper:hover {
        box-shadow: 0 8px 25px rgba(255, 120, 60, 0.18);
    }

    /* Tab Một chiều / Khứ hồi */
    .trip-type-pill {
        display: inline-flex;
        align-items: center;
        padding: 6px 16px;
        border-radius: 999px;
        border: 1px solid transparent;
        color: #ff7043;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
    }

    .trip-type-pill span {
        line-height: 1;
    }

    .trip-type-pill.active,
    .trip-type-pill:hover {
        background: #ffe0d1;
        border-color: #ff7043;
    }

    .pc-field-label {
        font-size: 11px;
        font-weight: 700;
        color: #848c96;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0;
        line-height: 1.1;
    }

    .pc-field-input {
        border: none;
        padding: 0;
        margin-top: 4px;
        font-size: 0.96rem;
        font-weight: 600;
        color: #222;
        background: transparent;
        outline: none;
    }

    .pc-field-input::placeholder {
        color: #9ca3af;
        font-weight: 500;
    }

    /* Ô input giống style FUTA */
    .futa-input {
        border-radius: 12px;
        background: #f8fbff;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
    }

    .futa-input:focus-within {
        border-color: #ff7043;
        box-shadow: 0 0 0 1px rgba(255, 112, 67, 0.2);
        background: #ffffff;
    }

    .futa-input input,
    .futa-input select {
        background: transparent;
    }

    /* Button tìm chuyến */
    .btn-search {
        border-radius: 999px;
        background-color: #ff7043;
        border: none;
        color: #fff;
        transition: all 0.2s;
    }

    .btn-search:hover {
        background-color: #f4511e;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(244, 81, 30, 0.35);
    }

    .futa-input select {
        background: transparent;
        border: none;
        width: 100%;
        padding: 0;
        font-size: 1rem;
        -webkit-appearance: none;
        /* Chrome, Safari */
        -moz-appearance: none;
        /* Firefox */
        appearance: none;
        cursor: pointer;
    }

    /* Thêm mũi tên select kiểu tùy chỉnh */
    .futa-input {
        position: relative;
    }

    .futa-input::after {
        content: "▼";
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.6rem;
        color: #888;
        pointer-events: none;
    }
</style>

{{-- ========== Flatpickr (nếu chưa include ở layout thì giữ 2 dòng này) ========== --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const departureInput = document.getElementById('pc_departure_date');
        const returnInput = document.getElementById('pc_return_date');
        const btnReturn = document.getElementById('pc_btn_add_return');
        const btnReturnText = btnReturn.querySelector('.pc-return-text');
        const defaultText = btnReturnText.textContent;

        // Lịch ngày đi
        flatpickr(departureInput, {
            dateFormat: "Y-m-d",
            minDate: "today",
            showMonths: 2,
            disableMobile: true
        });

        // Lịch ngày về
        const returnPicker = flatpickr(returnInput, {
            dateFormat: "Y-m-d",
            minDate: "today",
            showMonths: 2,
            disableMobile: true,
            onChange: function(selectedDates) {
                if (selectedDates.length) {
                    const d = selectedDates[0];
                    const dd = String(d.getDate()).padStart(2, '0');
                    const mm = String(d.getMonth() + 1).padStart(2, '0');
                    btnReturnText.textContent = `Ngày về: ${dd}/${mm}`;
                } else {
                    btnReturnText.textContent = defaultText;
                }
            }
        });

        // Bấm "Thêm ngày về" → mở lịch
        btnReturn.addEventListener('click', function() {
            returnPicker.open();
        });
    });
</script>
