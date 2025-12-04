{{-- resources/views/client/trips/_search-form.blade.php --}}

<div class="pc-search-form-wrapper">

    <form action="{{ route('client.searchTrips') }}" method="GET" class="row g-3 align-items-end">
        {{-- Điểm đi --}}
        <div class="col-lg-3 col-md-6">
            <label class="form-label fw-semibold mb-1">Điểm đi</label>
            <div class="futa-input">
                {{-- Đã thay đổi name thành 'from_city_id' và value là ID của City --}}
                <select name="from_city_id" class="form-select border-0 shadow-none p-0" required>
                    <option value="">-- Chọn điểm đi --</option>
                    {{-- Giả định $allFrom là Collection các đối tượng City --}}
                    @foreach ($allFrom as $fromCity)
                        <option value="{{ $fromCity->id }}" 
                            {{ request('from_city_id') == $fromCity->id ? 'selected' : '' }}>
                            {{ $fromCity->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Điểm đến --}}
        <div class="col-lg-3 col-md-6">
            <label class="form-label fw-semibold mb-1">Điểm đến</label>
            <div class="futa-input">
                {{-- Đã thay đổi name thành 'to_city_id' và value là ID của City --}}
                <select name="to_city_id" class="form-select border-0 shadow-none p-0" required>
                    <option value="">-- Chọn điểm đến --</option>
                    {{-- Giả định $allTo là Collection các đối tượng City --}}
                    @foreach ($allTo as $toCity)
                        <option value="{{ $toCity->id }}" 
                            {{ request('to_city_id') == $toCity->id ? 'selected' : '' }}>
                            {{ $toCity->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>


        {{-- Ngày đi --}}
        <div class="col-lg-3 col-md-6">
            <label class="form-label fw-semibold mb-1">Ngày đi</label>
            <div class="futa-input">
                <input type="date" name="departure_date" class="form-control border-0 shadow-none p-0"
                    {{-- Cập nhật để nhận request('departure_date') hoặc ngày hiện tại Y-m-d --}}
                    value="{{ request('departure_date', date('Y-m-d')) }}" 
                    min="{{ date('Y-m-d') }}" {{-- Thêm minDate để hạn chế chọn ngày cũ --}}
                    required>
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

{{-- ================== CSS (Giữ nguyên) ================== --}}
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

{{-- ========== Script (Đã xóa phần Flatpickr không cần thiết) ========== --}}
{{-- Giữ nguyên script nếu bạn vẫn muốn dùng flatpickr cho trường ngày về (nếu có) --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lấy input date
        const departureInput = document.querySelector('input[name="departure_date"]');
        
        // Thiết lập Flatpickr cho input date (chỉ dùng để định dạng và giới hạn ngày)
        flatpickr(departureInput, {
            dateFormat: "Y-m-d",
            minDate: "today",
            disableMobile: true
        });

        // Xóa các script không liên quan đến ngày về (pc_return_date, pc_btn_add_return)
        // Nếu bạn có tính năng khứ hồi, hãy giữ lại phần script Flatpickr cho ngày về.
    });
</script>