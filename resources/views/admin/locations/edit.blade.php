<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
           class="text-blue-600 hover:underline">{{ __('Dashboard') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('admin.locations.index') }}"
           class="text-blue-600 hover:underline">{{ __('Locations') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500">{{ __('Edit Location') }}</span>
    </div>

    <!-- Page Title -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ __('Edit Location') }}</h1>
        <p class="text-gray-600 mt-1">
            {{ __('Update location details and settings') }}
        </p>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <form method="POST" action="{{ route('admin.locations.update', $location) }}">
                    @csrf
                    @method('PUT')

                    <!-- Location Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Location Name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $location->name) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter location name...">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="mb-6">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Address') }} <span class="text-red-500">*</span>
                        </label>
                        <textarea id="address" name="address" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Enter complete address...">{{ old('address', $location->address) }}</textarea>
                        @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Coordinates Section -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('Coordinates') }}
                            </label>
                            <button type="button" id="getCurrentLocation"
                                    class="text-sm text-blue-600 hover:text-blue-800">
                                {{ __('Use Current Location') }}
                            </button>
                        </div>

                        <!-- Interactive Map -->
                        <div class="mb-4">
                            <div id="map" class="h-64 w-full border border-gray-300 rounded-lg"></div>
                            <p class="mt-2 text-sm text-gray-500">
                                {{ __('Click on the map to set location coordinates') }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Latitude') }}
                                </label>
                                <input type="number" id="latitude" name="latitude" value="{{ old('latitude', $location->latitude) }}"
                                       step="any" min="-90" max="90"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="e.g., -6.200000">
                                @error('latitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Longitude') }}
                                </label>
                                <input type="number" id="longitude" name="longitude" value="{{ old('longitude', $location->longitude) }}"
                                       step="any" min="-180" max="180"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="e.g., 106.816666">
                                @error('longitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <p class="mt-2 text-sm text-gray-500">
                            {{ __('Leave coordinates empty to skip location validation') }}
                        </p>
                    </div>

                    <!-- Radius -->
                    <div class="mb-6">
                        <label for="radius" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Allowed Radius (meters)') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="radius" name="radius" value="{{ old('radius', $location->radius) }}"
                               min="1" max="10000" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-sm text-gray-500">
                            {{ __('Users must be within this radius to check in/out (1-10000 meters)') }}
                        </p>
                        @error('radius')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $location->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm font-medium text-gray-700">
                                {{ __('Active Location') }}
                            </span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ __('Only active locations are available for attendance') }}
                        </p>
                    </div>

                    <!-- Location Status Display -->
                    <div id="locationStatus" class="hidden mb-6">
                        <div id="locationInfo" class="p-4 border rounded-lg">
                            <!-- Will be populated with location info -->
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('admin.locations.index') }}"
                           class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('Update Location') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>

    <script>
        let map;
        let marker;
        let radiusCircle;

        document.addEventListener('DOMContentLoaded', function() {
            const getCurrentLocationBtn = document.getElementById('getCurrentLocation');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');
            const radiusInput = document.getElementById('radius');

            // Initialize map
            initializeMap();

            getCurrentLocationBtn.addEventListener('click', getCurrentLocation);

            // Watch for coordinate changes
            latitudeInput.addEventListener('input', updateFromInputs);
            longitudeInput.addEventListener('input', updateFromInputs);
            radiusInput.addEventListener('input', updateRadius);
        });

        function initializeMap() {
            // Get existing coordinates or use default (Jakarta, Indonesia)
            const existingLat = document.getElementById('latitude').value;
            const existingLng = document.getElementById('longitude').value;
            const defaultLat = existingLat || -6.2;
            const defaultLng = existingLng || 106.816666;

            map = L.map('map').setView([defaultLat, defaultLng], existingLat && existingLng ? 15 : 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Add click event to map
            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                updateCoordinates(lat, lng);
            });

            // Initialize with existing coordinates if any
            if (existingLat && existingLng) {
                updateCoordinates(parseFloat(existingLat), parseFloat(existingLng));
            }
        }

        function updateCoordinates(lat, lng) {
            // Update input fields
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);

            // Update map marker
            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker([lat, lng]).addTo(map)
                .bindPopup(`Coordinates: ${lat.toFixed(6)}, ${lng.toFixed(6)}`)
                .openPopup();

            // Update radius circle
            updateRadius();
            updateLocationStatus();
        }

        function updateRadius() {
            const radiusValue = document.getElementById('radius').value || 100;
            const lat = document.getElementById('latitude').value;
            const lng = document.getElementById('longitude').value;

            if (lat && lng) {
                // Remove existing circle
                if (radiusCircle) {
                    map.removeLayer(radiusCircle);
                }

                // Add new circle
                radiusCircle = L.circle([lat, lng], {
                    color: 'blue',
                    fillColor: '#30f',
                    fillOpacity: 0.1,
                    radius: parseInt(radiusValue)
                }).addTo(map);

                updateLocationStatus();
            }
        }

        function updateFromInputs() {
            const lat = document.getElementById('latitude').value;
            const lng = document.getElementById('longitude').value;

            if (lat && lng && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                updateCoordinates(parseFloat(lat), parseFloat(lng));
                map.setView([lat, lng], 15);
            } else {
                updateLocationStatus();
            }
        }

        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        updateCoordinates(lat, lng);
                        map.setView([lat, lng], 15);
                    },
                    (error) => {
                        alert('{{ __("Unable to get your location. Please enter coordinates manually.") }}');
                        console.error('Geolocation error:', error);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 60000
                    }
                );
            } else {
                alert('{{ __("Geolocation is not supported by this browser") }}');
            }
        }

        function updateLocationStatus() {
            const latitude = document.getElementById('latitude').value;
            const longitude = document.getElementById('longitude').value;
            const radius = document.getElementById('radius').value || 100;

            if (latitude && longitude) {
                const statusDiv = document.getElementById('locationStatus');
                const infoDiv = document.getElementById('locationInfo');

                statusDiv.classList.remove('hidden');
                infoDiv.className = 'p-4 border rounded-lg bg-blue-50 border-blue-200 text-blue-800';
                infoDiv.innerHTML = `
                    <div class="flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        Location coordinates set: ${parseFloat(latitude).toFixed(6)}, ${parseFloat(longitude).toFixed(6)} (${radius}m radius)
                    </div>
                `;
            } else {
                document.getElementById('locationStatus').classList.add('hidden');
            }
        }
    </script>
</x-layouts.app>
