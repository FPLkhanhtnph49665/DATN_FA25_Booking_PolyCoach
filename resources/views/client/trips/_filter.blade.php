<div class="card p-3 shadow-sm">
    <h6 class="fw-bold mb-3">BỘ LỌC TÌM KIẾM</h6>

    <form method="GET" action="{{ route('client.trips') }}">
        {{-- Giữ lại các tham số tìm kiếm chính --}}
        <input type="hidden" name="from"  value="{{ request('from') }}">
        <input type="hidden" name="to"    value="{{ request('to') }}">
        <input type="hidden" name="date"  value="{{ request('date') }}">
        <input type="hidden" name="seats" value="{{ request('seats', 1) }}">

        {{-- Giờ đi --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Giờ đi</label>
            <div class="d-flex flex-column gap-1 small">
                <label>
                    <input type="checkbox" name="time[]" value="sang"
                           {{ in_array('sang', (array)request('time')) ? 'checked' : '' }}>
                    Sáng sớm 00:00 - 06:00
                </label>
                <label>
                    <input type="checkbox" name="time[]" value="sang2"
                           {{ in_array('sang2', (array)request('time')) ? 'checked' : '' }}>
                    Buổi sáng 06:00 - 12:00
                </label>
                <label>
                    <input type="checkbox" name="time[]" value="chieu"
                           {{ in_array('chieu', (array)request('time')) ? 'checked' : '' }}>
                    Buổi chiều 12:00 - 18:00
                </label>
                <label>
                    <input type="checkbox" name="time[]" value="toi"
                           {{ in_array('toi', (array)request('time')) ? 'checked' : '' }}>
                    Buổi tối 18:00 - 24:00
                </label>
            </div>
        </div>

        {{-- Loại xe --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Loại xe</label>
            <div class="d-flex flex-wrap gap-2">
                <input type="checkbox" class="btn-check" id="type-ghe" name="bus_type[]" value="ghe"
                       autocomplete="off"
                       {{ in_array('ghe', (array)request('bus_type')) ? 'checked' : '' }}>
                <label class="btn btn-outline-secondary btn-sm" for="type-ghe">Ghế</label>

                <input type="checkbox" class="btn-check" id="type-giuong" name="bus_type[]" value="giuong"
                       autocomplete="off"
                       {{ in_array('giuong', (array)request('bus_type')) ? 'checked' : '' }}>
                <label class="btn btn-outline-secondary btn-sm" for="type-giuong">Giường</label>

                <input type="checkbox" class="btn-check" id="type-limo" name="bus_type[]" value="limousine"
                       autocomplete="off"
                       {{ in_array('limousine', (array)request('bus_type')) ? 'checked' : '' }}>
                <label class="btn btn-outline-secondary btn-sm" for="type-limo">Limousine</label>
            </div>
        </div>

        {{-- Hàng ghế --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Hàng ghế</label>
            <div class="d-flex flex-wrap gap-2">
                <input type="checkbox" class="btn-check" id="row-front" name="row[]" value="front"
                       autocomplete="off"
                       {{ in_array('front', (array)request('row')) ? 'checked' : '' }}>
                <label class="btn btn-outline-secondary btn-sm" for="row-front">Hàng đầu</label>

                <input type="checkbox" class="btn-check" id="row-middle" name="row[]" value="middle"
                       autocomplete="off"
                       {{ in_array('middle', (array)request('row')) ? 'checked' : '' }}>
                <label class="btn btn-outline-secondary btn-sm" for="row-middle">Hàng giữa</label>

                <input type="checkbox" class="btn-check" id="row-back" name="row[]" value="back"
                       autocomplete="off"
                       {{ in_array('back', (array)request('row')) ? 'checked' : '' }}>
                <label class="btn btn-outline-secondary btn-sm" for="row-back">Hàng cuối</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Áp dụng</button>
    </form>
</div>
