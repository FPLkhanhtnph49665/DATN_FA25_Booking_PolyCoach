<div class="vx-wrapper">
    {{-- GIỮ LẠI FORM NÀY VÀ ĐỔI CLASS THÀNH vx-form --}}
    <form action="{{ route('client.searchTrips') }}" method="GET" class="vx-form">

        {{-- Điểm đi --}}
        <div class="vx-field">
            <i class="fas fa-map-marker-alt vx-icon"></i>
            <select name="from_city_id" class="vx-input" required>
                <option value="">Điểm đi</option>
                @foreach ($allFrom as $fromCity)
                    <option value="{{ $fromCity->id }}" {{ request('from_city_id') == $fromCity->id ? 'selected' : '' }}>
                        {{ $fromCity->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Điểm đến --}}
        <div class="vx-field">
            <i class="fas fa-map-pin vx-icon"></i>
            <select name="to_city_id" class="vx-input" required>
                <option value="">Điểm đến</option>
                @foreach ($allTo as $toCity)
                    <option value="{{ $toCity->id }}" {{ request('to_city_id') == $toCity->id ? 'selected' : '' }}>
                        {{ $toCity->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Ngày đi --}}
        <div class="vx-field">
            <i class="fas fa-calendar-alt vx-icon"></i>
            <input type="date" name="departure_date" class="vx-input"
                value="{{ request('departure_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
        </div>

        {{-- Ngày về (Giữ nguyên cấu trúc vx-return-btn để dùng JS/CSS) --}}
        <div class="vx-field vx-return-btn" id="vx_btn_return">
            <i class="fas fa-plus vx-icon"></i>
            <span id="vx_return_text">
                {{ request('return_date') ? 'Ngày về: ' . \Carbon\Carbon::parse(request('return_date'))->format('d/m') : 'Thêm ngày về' }}
            </span>
            <input type="text" name="return_date" id="vx_return_date" value="{{ request('return_date') }}"
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
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.06);
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
        box-shadow: 0 0 8px rgba(37, 99, 235, 0.25);
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
        box-shadow: 0 4px 12px rgba(255, 191, 0, 0.35);
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

    /* ===== LOADING BUTTON ===== */
    /* Hiệu ứng xoay tròn */
    @keyframes vx-spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .vx-spinner {
        width: 20px;
        height: 20px;
        border: 3px solid #000;
        /* Màu viền spinner */
        border-top: 3px solid transparent;
        /* Tạo khoảng hở để thấy nó xoay */
        border-radius: 50%;
        animation: vx-spin 0.8s linear infinite;
        display: inline-block;
        /* 0.8s là xoay nhanh, 2.0s là xoay chậm rãi */
        animation: vx-spin 2.0s linear infinite;
    }

    /* Class trạng thái loading cho nút */
    .vx-btn-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        /* Khoảng cách giữa spinner và chữ */
        opacity: 0.8;
        pointer-events: none;
        /* Chặn không cho bấm liên tục */
        cursor: wait;
    }
</style>

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
    });
    document.addEventListener('DOMContentLoaded', function() {
        const fromSelect = document.querySelector('select[name="from_city_id"]');
        const toSelect = document.querySelector('select[name="to_city_id"]');
        const form = document.querySelector('.vx-form');
        const searchBtn = document.querySelector('.vx-btn-search'); // Lấy nút tìm kiếm

        // --- PHẦN 1: LOGIC CHẶN TRÙNG ĐIỂM (GIỮ NGUYÊN) ---
        function updateOptions(changedSelect, targetSelect) {
            const selectedValue = changedSelect.value;
            const targetOptions = targetSelect.querySelectorAll('option');
            targetOptions.forEach(option => {
                option.disabled = false;
                if (option.value === selectedValue && selectedValue !== "") {
                    option.disabled = true;
                }
            });
            if (targetSelect.value === selectedValue && selectedValue !== "") {
                targetSelect.value = "";
            }
        }

        fromSelect.addEventListener('change', () => updateOptions(fromSelect, toSelect));
        toSelect.addEventListener('change', () => updateOptions(toSelect, fromSelect));
        updateOptions(fromSelect, toSelect);
        updateOptions(toSelect, fromSelect);


        // --- PHẦN 2: HIỆU ỨNG LOADING KHI SUBMIT ---
        form.addEventListener('submit', function(e) {
            // Nếu form đã đang ở trạng thái loading thì không làm gì thêm
            if (searchBtn.classList.contains('vx-btn-loading')) return;

            // Kiểm tra tính hợp lệ của form
            if (!form.checkValidity()) return;

            // Chặn form gửi đi ngay lập tức
            e.preventDefault();

            // Hiện hiệu ứng quay quay
            searchBtn.innerHTML = '<span class="vx-spinner"></span> Đang tìm...';
            searchBtn.classList.add('vx-btn-loading');

            // Thiết lập thời gian chờ (Ví dụ: 2000ms = 2 giây)
            setTimeout(() => {
                form.submit(); // Sau 2 giây mới thực sự gửi form đi
            }, 1000);
        });
    });
</script>
