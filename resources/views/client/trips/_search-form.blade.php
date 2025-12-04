<div class="vx-wrapper">
    <form action="{{ route('client.searchTrips') }}" method="GET" class="vx-form">

        {{-- Nơi xuất phát --}}
        <div class="vx-field">
            <i class="fas fa-location-dot vx-icon"></i>
            <input type="text" name="from" class="vx-input" placeholder="Nơi xuất phát"
                   value="{{ request('from') }}" required>
        </div>

        {{-- Nơi đến --}}
        <div class="vx-field">
            <i class="fas fa-map-marker-alt vx-icon"></i>
            <input type="text" name="to" class="vx-input" placeholder="Nơi đến"
                   value="{{ request('to') }}" required>
        </div>

        {{-- Ngày đi --}}
        <div class="vx-field">
            <i class="fas fa-calendar-alt vx-icon"></i>
            <input type="text" name="date" id="vx_departure_date" class="vx-input"
                   placeholder="Ngày đi" value="{{ request('date') }}" required>
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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const departInput = document.getElementById('vx_departure_date');
    const returnInput = document.getElementById('vx_return_date');
    const btnReturn = document.getElementById('vx_btn_return');
    const returnText = document.getElementById('vx_return_text');
    const defaultText = returnText.textContent;

    /* Laravel dùng Y-m-d => Flatpickr phải dùng format này */
    flatpickr(departInput, {
        dateFormat: "Y-m-d",
        minDate: "today",
        disableMobile: true
    });

    const returnPicker = flatpickr(returnInput, {
        dateFormat: "Y-m-d",
        minDate: "today",
        disableMobile: true,
        onChange(selectedDates) {
            if (selectedDates.length) {
                let d = selectedDates[0];
                returnText.textContent =
                    `Ngày về: ${String(d.getDate()).padStart(2,'0')}/${String(d.getMonth()+1).padStart(2,'0')}`;
            } else {
                returnText.textContent = defaultText;
            }
        }
    });

    btnReturn.addEventListener('click', () => returnPicker.open());
});
</script>
