@props([
    'categories' => collect(),
    'provinces' => collect(),
    'action' => '',
    'branch' => null,
])

@php
    $selectedProvinceId = old('province_id', $branch->province_id ?? '');
    $selectedDistrictId = old('district_id', $branch->district_id ?? '');
    $initialLatitude = old('latitude', $branch->latitude ?? '');
    $initialLongitude = old('longitude', $branch->longitude ?? '');
    $initialLocation = old('location', $branch->location ?? '');
@endphp

<div class="p-2 text-gray-900">
    @if (session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3">
            <svg class="h-5 w-5 flex-shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <p class="text-sm font-medium text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    <form id="branchForm" class="space-y-8" method="POST" action="{{ $action }}">
        @csrf
        @if ($branch)
            @method('PUT')
        @endif

        <div class="grid gap-8 xl:grid-cols-3">
            <div class="space-y-6 xl:col-span-2">
                <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-3 border-b border-gray-100 pb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Branch Details</h3>
                            <p class="text-sm text-gray-500">Select the category and fill in the branch profile.</p>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-5 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="branch_category_id"
                                class="mb-1.5 block text-sm font-medium text-gray-700">Branch Category <span
                                    class="text-red-500">*</span></label>
                            <select id="branch_category_id" name="branch_category_id"
                                class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 @error('branch_category_id') border-red-500 @enderror">
                                <option value="">Select category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('branch_category_id', $branch->branch_category_id ?? '') == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('branch_category_id')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700">Branch Name
                                <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name"
                                value="{{ old('name', $branch->name ?? '') }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror"
                                placeholder="Enter branch name">
                            @error('name')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="leader_name" class="mb-1.5 block text-sm font-medium text-gray-700">Leader
                                Name</label>
                            <input type="text" id="leader_name" name="leader_name"
                                value="{{ old('leader_name', $branch->leader_name ?? '') }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 @error('leader_name') border-red-500 @enderror"
                                placeholder="Optional">
                            @error('leader_name')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mobile" class="mb-1.5 block text-sm font-medium text-gray-700">Mobile</label>
                            <input type="text" id="mobile" name="mobile"
                                value="{{ old('mobile', $branch->mobile ?? '') }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 @error('mobile') border-red-500 @enderror"
                                placeholder="98XXXXXXXX">
                            @error('mobile')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="location" class="mb-1.5 block text-sm font-medium text-gray-700">Location <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="location" name="location"
                                value="{{ old('location', $initialLocation) }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 @error('location') border-red-500 @enderror"
                                placeholder="Search or pick a point on the map">
                            @error('location')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="province_id" class="mb-1.5 block text-sm font-medium text-gray-700">Province
                                <span class="text-red-500">*</span></label>
                            <select id="province_id" name="province_id"
                                data-selected-district="{{ $selectedDistrictId }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 @error('province_id') border-red-500 @enderror">
                                <option value="">Select province</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}" @selected((string) old('province_id', $selectedProvinceId) === (string) $province->id)>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province_id')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="district_id" class="mb-1.5 block text-sm font-medium text-gray-700">District
                                <span class="text-red-500">*</span></label>
                            <select id="district_id" name="district_id" data-selected-value="{{ $selectedDistrictId }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 @error('district_id') border-red-500 @enderror">
                                <option value="">Select province first</option>
                            </select>
                            @error('district_id')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="latitude"
                                class="mb-1.5 block text-sm font-medium text-gray-700">Latitude</label>
                            <input type="text" id="latitude" name="latitude"
                                value="{{ old('latitude', $initialLatitude) }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 @error('latitude') border-red-500 @enderror"
                                readonly>
                            @error('latitude')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="longitude"
                                class="mb-1.5 block text-sm font-medium text-gray-700">Longitude</label>
                            <input type="text" id="longitude" name="longitude"
                                value="{{ old('longitude', $initialLongitude) }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 @error('longitude') border-red-500 @enderror"
                                readonly>
                            @error('longitude')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>
            </div>

            <div class="space-y-6">
                <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-100 p-6 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Map Picker</h3>
                        <p class="mt-1 text-sm text-gray-500">Search a place or click the map to set the branch
                            location.</p>
                    </div>

                    <div class="space-y-4 p-6">
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-3">
                            <div id="branch-map" class="h-[420px] w-full rounded-xl"></div>
                        </div>
                        <div class="flex items-center justify-end">
                            <button type="button" id="clear-marker-btn"
                                class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50">
                                Clear Marker
                            </button>
                        </div>
                        <p class="text-xs leading-5 text-gray-500">
                            Drag the marker or use the search control to refine the position.
                        </p>
                    </div>
                </section>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-6">
            <a href="{{ route('branches.index') }}"
                class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit"
                class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                Save Branch
            </button>
        </div>
    </form>
</div>

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province_id');
            const districtSelect = document.getElementById('district_id');
            const locationInput = document.getElementById('location');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');
            const mapElement = document.getElementById('branch-map');
            const clearMarkerButton = document.getElementById('clear-marker-btn');

            if (!provinceSelect || !districtSelect || !locationInput || !latitudeInput || !longitudeInput || !
                mapElement || !clearMarkerButton || typeof L === 'undefined') {
                return;
            }

            const districtEndpointTemplate = @json(route('branches.districts', ['province' => '__PROVINCE__']));
            const selectedProvinceId = provinceSelect.value;
            const selectedDistrictId = districtSelect.dataset.selectedValue || '';

            const inputLat = parseFloat(latitudeInput.value);
            const inputLng = parseFloat(longitudeInput.value);
            const hasInitialCoordinates = !Number.isNaN(inputLat) && !Number.isNaN(inputLng);

            const defaultMapLat = 27.7172;
            const defaultMapLng = 85.3240;
            const initialLat = hasInitialCoordinates ? inputLat : defaultMapLat;
            const initialLng = hasInitialCoordinates ? inputLng : defaultMapLng;

            const map = L.map('branch-map').setView([initialLat, initialLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);

            let marker = null;

            function syncClearMarkerButtonState() {
                clearMarkerButton.disabled = !marker;
            }

            function attachMarkerDragListener() {
                if (!marker) {
                    return;
                }

                marker.on('dragend', function(event) {
                    const latLng = event.target.getLatLng();
                    updateLocation(latLng.lat, latLng.lng);
                });
            }

            function setMarker(lat, lng) {
                if (!marker) {
                    marker = L.marker([lat, lng], {
                        draggable: true,
                    }).addTo(map);
                    attachMarkerDragListener();
                    syncClearMarkerButtonState();
                    return;
                }

                marker.setLatLng([lat, lng]);
            }

            function clearSelectedLocation() {
                if (marker) {
                    map.removeLayer(marker);
                    marker = null;
                }
                latitudeInput.value = '';
                longitudeInput.value = '';
                locationInput.value = '';
                syncClearMarkerButtonState();
            }

            if (hasInitialCoordinates) {
                setMarker(initialLat, initialLng);
            }

            syncClearMarkerButtonState();

            const geocoder = L.Control.geocoder({
                    defaultMarkGeocode: false,
                    placeholder: 'Search location...'
                })
                .on('markgeocode', function(event) {
                    const center = event.geocode.center;
                    updateLocation(center.lat, center.lng, event.geocode.name);
                })
                .addTo(map);

            async function reverseGeocode(lat, lng) {
                try {
                    const response = await fetch(
                        `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`);
                    const data = await response.json();
                    if (data && data.display_name) {
                        locationInput.value = data.display_name;
                    }
                } catch (error) {
                    console.warn('Reverse geocoding failed', error);
                }
            }

            function updateLocation(lat, lng, label = '') {
                const nextLat = Number(lat).toFixed(8);
                const nextLng = Number(lng).toFixed(8);
                latitudeInput.value = nextLat;
                longitudeInput.value = nextLng;
                setMarker(nextLat, nextLng);
                map.setView([nextLat, nextLng], 15);
                if (label) {
                    locationInput.value = label;
                }
                reverseGeocode(nextLat, nextLng);
            }

            map.on('click', function(event) {
                updateLocation(event.latlng.lat, event.latlng.lng);
            });

            clearMarkerButton.addEventListener('click', function() {
                clearSelectedLocation();
            });

            async function loadDistricts(provinceId, selectedDistrict = '') {
                districtSelect.innerHTML = '<option value="">Loading districts...</option>';
                districtSelect.disabled = true;

                if (!provinceId) {
                    districtSelect.innerHTML = '<option value="">Select province first</option>';
                    districtSelect.disabled = false;
                    return;
                }

                const url = districtEndpointTemplate.replace('__PROVINCE__', provinceId);
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                const payload = await response.json();
                const districts = payload.data || [];

                districtSelect.innerHTML = '<option value="">Select district</option>';
                districts.forEach(function(district) {
                    const option = document.createElement('option');
                    option.value = district.id;
                    option.textContent = district.name;
                    if (String(selectedDistrict) === String(district.id)) {
                        option.selected = true;
                    }
                    districtSelect.appendChild(option);
                });

                districtSelect.disabled = false;
            }

            provinceSelect.addEventListener('change', function() {
                loadDistricts(this.value);
            });

            if (selectedProvinceId) {
                loadDistricts(selectedProvinceId, selectedDistrictId);
            }
        });
    </script>
@endpush
