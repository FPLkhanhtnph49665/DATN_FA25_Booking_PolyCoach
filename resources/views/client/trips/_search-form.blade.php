{{-- resources/views/client/trips/_search-form.blade.php --}}

<div class="pc-search-form-wrapper">
    <form action="{{ route('client.searchTrips') }}" method="GET" class="pc-search-form">
        {{-- Nơi xuất phát --}}
        <div class="pc-field">
            <span class="pc-field-label">Nơi xuất phát</span>
            <span class="pc-field-icon">
                <i class="fas fa-location-dot"></i>
            </span>
            <input type="text"
                   name="from"
                   class="pc-field-input"
                   placeholder="VD: Hà Nội"
                   value="{{ request('from') }}"
                   required>
        </div>

        {{-- Nơi đến --}}
        <div class="pc-field">
            <span class="pc-field-label">Nơi đến</span>
            <span class="pc-field-icon">
                <i class="fas fa-map-marker-alt"></i>
            </span>
            <input type="text"
                   name="to"
                   class="pc-field-input"
                   placeholder="VD: Đà Nẵng"
                   value="{{ request('to') }}"
                   required>
        </div>

        {{-- Ngày đi --}}
        <div class="pc-field">
            <span class="pc-field-label">Ngày đi</span>
            <span class="pc-field-icon">
                <i class="fas fa-calendar-alt"></i>
            </span>
            <input type="text"
                   name="date"
                   id="pc_departure_date"
                   class="pc-field-input"
                   placeholder="Chọn ngày đi"
                   value="{{ request('date') }}"
                   required>
        </div>

        {{-- Thêm ngày về --}}
        <div class="pc-return-wrapper">
            <button type="button" class="pc-btn-return" id="pc_btn_add_return">
                <span class="pc-return-plus">+</span>
                <span class="pc-return-text">
                    {{ request('return_date') ? 'Ngày về: ' . \Carbon\Carbon::parse(request('return_date'))->format('d/m') : 'Thêm ngày về' }}
                </span>
            </button>

            {{-- input ẩn để submit giá trị ngày về --}}
            <input type="text"
                   name="return_date"
                   id="pc_return_date"
                   value="{{ request('return_date') }}"
                   style="position:absolute; left:-9999px; opacity:0;">
        </div>

        {{-- Nút tìm kiếm --}}
        <button type="submit" class="pc-btn-search">
            Tìm kiếm
        </button>
    </form>
</div>

{{-- ================== CSS ================== --}}
<style>
.pc-search-form-wrapper { width: 100%; }
.pc-search-form {
    display: flex;
    flex-wrap: nowrap;
    align-items: stretch;
    gap: 12px;
}

/* Ô chung */
.pc-field {
    position: relative;
    flex: 1 1 0;
    min-width: 190px;
    background: #ffffff;
    border-radius: 18px;
    border: 1.5px solid #ececec;
    padding: 10px 16px 10px 54px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    transition: all 0.22s cubic-bezier(.4,0,.2,1);
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
}
.pc-field:hover {
    border-color: #ff595e;
    box-shadow: 0 4px 16px rgba(255,89,94,0.20);
}
.pc-field:focus-within {
    border-color: #ff595e;
    box-shadow: 0 6px 20px rgba(255,89,94,0.28);
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

.pc-field-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 32px;
    height: 32px;
    border-radius: 999px;
    background: linear-gradient(135deg, #ff7b81, #ff595e);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    color: #fff;
    box-shadow: 0 3px 10px rgba(255,89,94,0.45);
}

/* Thêm ngày về */
.pc-return-wrapper {
    flex: 0 0 auto;
    display: flex;
    align-items: stretch;
}
.pc-btn-return {
    height: 100%;
    border-radius: 18px;
    border: 1.6px dashed #ff595e;
    background: #fff5f6;
    padding: 0 22px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.95rem;
    font-weight: 700;
    color: #ff595e;
    white-space: nowrap;
    cursor: pointer;
    transition: all .2s;
}
.pc-return-plus {
    width: 22px;
    height: 22px;
    border-radius: 999px;
    border: 2px solid #ff595e;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 0.9rem;
}
.pc-btn-return:hover {
    background: #ffe0e1;
    box-shadow: 0 4px 14px rgba(255,89,94,0.25);
}

/* Nút tìm kiếm */
.pc-btn-search {
    align-self: stretch;
    border-radius: 18px;
    padding: 0 32px;
    border: none;
    background: linear-gradient(135deg, #ff7b81, #ff595e);
    font-weight: 800;
    font-size: 1rem;
    color: #fff;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all .2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 16px rgba(255,89,94,0.35);
    white-space: nowrap;
}
.pc-btn-search:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 26px rgba(255,89,94,0.55);
    filter: brightness(1.04);
}

/* Mobile */
@media (max-width: 768px) {
    .pc-search-form { flex-wrap: wrap; }
    .pc-btn-search {
        width: 100%;
        margin-top: 6px;
    }
}
</style>

{{-- ========== Flatpickr (nếu chưa include ở layout thì giữ 2 dòng này) ========== --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const departureInput = document.getElementById('pc_departure_date');
    const returnInput    = document.getElementById('pc_return_date');
    const btnReturn      = document.getElementById('pc_btn_add_return');
    const btnReturnText  = btnReturn.querySelector('.pc-return-text');
    const defaultText    = btnReturnText.textContent;

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
        onChange: function (selectedDates) {
            if (selectedDates.length) {
                const d  = selectedDates[0];
                const dd = String(d.getDate()).padStart(2,'0');
                const mm = String(d.getMonth()+1).padStart(2,'0');
                btnReturnText.textContent = `Ngày về: ${dd}/${mm}`;
            } else {
                btnReturnText.textContent = defaultText;
            }
        }
    });

    // Bấm "Thêm ngày về" → mở lịch
    btnReturn.addEventListener('click', function () {
        returnPicker.open();
    });
});
</script>
