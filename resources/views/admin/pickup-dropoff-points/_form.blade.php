@php
    /** @var \App\Models\PickupDropoffPoint|null $point */
@endphp

<div class="space-y-6">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- Thành phố --}}
    <div>
      <label for="city_id" class="block text-sm font-medium text-gray-700 mb-1">
        Thành phố <span class="text-red-500">*</span>
      </label>
      <select
        name="city_id"
        id="city_id"
        class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
        required
      >
        <option value="">-- Chọn thành phố --</option>
        @foreach ($cities as $city)
          <option value="{{ $city->id }}"
            @selected(old('city_id', optional($point)->city_id) == $city->id)>
            {{ $city->name }}
          </option>
        @endforeach
      </select>
      @error('city_id')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
      @enderror
    </div>

    {{-- Tuyến --}}
    <div>
      <label for="route_id" class="block text-sm font-medium text-gray-700 mb-1">
        Tuyến xe <span class="text-red-500">*</span>
      </label>
      <select
        name="route_id"
        id="route_id"
        class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
        required
      >
        <option value="">-- Chọn tuyến --</option>
        @foreach ($routes as $route)
          <option value="{{ $route->id }}"
            @selected(old('route_id', optional($point)->route_id) == $route->id)>
            {{ $route->name ?? ('Tuyến #' . $route->id) }}
          </option>
        @endforeach
      </select>
      @error('route_id')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
      @enderror
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- Tên điểm --}}
    <div>
      <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
        Tên điểm đón/trả <span class="text-red-500">*</span>
      </label>
      <input
        type="text"
        name="name"
        id="name"
        value="{{ old('name', optional($point)->name) }}"
        class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
        required
      >
      @error('name')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
      @enderror
    </div>

    {{-- Thời gian --}}
    <div>
      <label for="time" class="block text-sm font-medium text-gray-700 mb-1">
        Thời gian (ví dụ: 08:30)
      </label>
      <input
        type="text"
        name="time"
        id="time"
        value="{{ old('time', optional($point)->time) }}"
        class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
        placeholder="08:30"
      >
      @error('time')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
      @enderror
    </div>
  </div>

  {{-- Địa chỉ --}}
  <div>
    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
      Địa chỉ
    </label>
    <input
      type="text"
      name="address"
      id="address"
      value="{{ old('address', optional($point)->address) }}"
      class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
      placeholder="Số nhà, tên đường, phường/xã..."
    >
    @error('address')
      <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- Loại điểm --}}
    <div>
      <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
        Loại điểm <span class="text-red-500">*</span>
      </label>
      <select
        name="type"
        id="type"
        class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
        required
      >
        <option value="">-- Chọn loại --</option>
        <option value="pickup"  @selected(old('type', optional($point)->type) === 'pickup')>
          Điểm đón
        </option>
        <option value="dropoff" @selected(old('type', optional($point)->type) === 'dropoff')>
          Điểm trả
        </option>
      </select>
      @error('type')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
      @enderror
    </div>

    {{-- Trạng thái --}}
    <div class="flex items-center mt-6">
      <input
        type="checkbox"
        name="active"
        id="active"
        value="1"
        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
        @checked(old('active', optional($point)->active ?? 1))
      >
      <label for="active" class="ml-2 text-sm text-gray-700">
        Hoạt động
      </label>
    </div>
  </div>
</div>
