<div class="vx-wrapper">
    <form action="{{ route('client.searchTrips') }}" method="GET" class="vx-form">

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

        {{-- Ngày về --}}
        <div class="vx-field vx-return-btn" id="vx_btn_return">
            <i class="fas fa-plus vx-icon"></i>
            <span id="vx_return_text">
                {{ request('return_date') ? 'Ngày về: ' . \Carbon\Carbon::parse(request('return_date'))->format('d/m') : 'Thêm ngày về' }}
            </span>

            <input type="text" name="return_date" id="vx_return_date"
                   value="{{ request('return_date') }}"
                   style="opacity:0; position:absolute; left:-9999px;">
        </div>

        {{-- Nút tìm kiếm --}}
        <button type="submit" class="vx-btn-search">Tìm kiếm</button>

    </form>
</div>


<style>
/* ===== VEXERE UI CHUẨN ===== */

.vx-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
}

.vx-form {
    width: 100%;
    max-width: 900px;
    background: #ffffff;
    border-radius: 18px;
    padding: 18px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 4px 25px rgba(0,0,0,0.06);
}

.vx-field {
    flex: 1;
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e5e7eb;
    padding: 12px 12px 12px 44px;
    position: relative;
    display: flex;
    align-items: center;
    height: 54px;
    transition: .2s;
}

.vx-field:hover,
.vx-field:focus-within {
    border-color: #2563eb;
    box-shadow: 0 0 8px rgba(37,99,235,0.25);
}

.vx-icon {
    position: absolute;
    top: 50%;
    left: 14px;
    transform: translateY(-50%);
    font-size: 18px;
    color: #2563eb;
}

.vx-input {
    width: 100%;
    border: none;
    outline: none;
    font-size: 15px;
    font-weight: 600;
    color: #111;
}

.vx-input::placeholder {
    color: #9ca3af;
}

.vx-return-btn {
    flex: 1;
    cursor: pointer;
    user-select: none;
}

.vx-btn-search {
    background: #ffbf00;
    border: none;
    border-radius: 14px;
    padding: 0 30px;
    height: 54px;
    font-size: 16px;
    font-weight: 700;
    color: #000;
    cursor: pointer;
    white-space: nowrap;
    box-shadow: 0 4px 12px rgba(255,191,0,0.35);
    transition: .2s;
}

.vx-btn-search:hover {
    background: #ffd21f;
    transform: translateY(-2px);
}

@media(max-width: 768px) {
    .vx-form {
        flex-wrap: wrap;
    }
    .vx-btn-search {
        width: 100%;
    }
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
