<div class="trip-search-wrapper mb-5">
    {{-- Tabs chọn loại vé --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-3">
            <label class="trip-type-pill active mb-0">
                <input type="radio" name="trip_type" value="one_way" checked class="d-none">
                <span>Một chiều</span>
            </label>
            <label class="trip-type-pill mb-0">
                <input type="radio" name="trip_type" value="round_trip" class="d-none">
                <span>Khứ hồi</span>
            </label>
        </div>
        <a href="#" class="small text-danger fw-medium text-decoration-none">Hướng dẫn mua vé</a>
    </div>

    {{-- Khung form tìm kiếm --}}
    <div class="search-form-container">
        <h4 class="mb-4 text-center fw-bold text-primary">
            Tìm chuyến xe ngay
        </h4>

        <form action="{{ route('client.searchTrips') }}" method="GET" class="row g-3 align-items-end">
            {{-- Điểm đi --}}
            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-semibold mb-1">Điểm đi</label>
                <div class="futa-input">
                    <input type="text"
                           name="from"
                           class="form-control border-0 shadow-none p-0"
                           placeholder="VD: Hà Nội"
                           value="{{ request('from') }}"
                           required>
                </div>
            </div>

            {{-- Điểm đến --}}
            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-semibold mb-1">Điểm đến</label>
                <div class="futa-input">
                    <input type="text"
                           name="to"
                           class="form-control border-0 shadow-none p-0"
                           placeholder="VD: Đà Nẵng"
                           value="{{ request('to') }}"
                           required>
                </div>
            </div>

            {{-- Ngày đi --}}
            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-semibold mb-1">Ngày đi</label>
                <div class="futa-input">
                    <input type="date"
                           name="date"
                           class="form-control border-0 shadow-none p-0"
                           value="{{ request('date', date('d-m-Y')) }}"
                           required>
                </div>
            </div>

            {{-- Số vé --}}
            <div class="col-lg-2 col-md-4">
                <label class="form-label fw-semibold mb-1">Số vé</label>
                <div class="futa-input">
                    <select name="seats" class="form-select border-0 shadow-none p-0">
                        @for($i = 1; $i <= 10; $i++)
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

    .search-form-container {
        margin-top: 10px;
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
</style>
