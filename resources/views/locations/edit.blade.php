<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        @svg('fas-chevron-right', 'h-4 w-4 mx-2 text-gray-400')
        <a href="{{ route('locations.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Locations') }}</a>
        @svg('fas-chevron-right', 'h-4 w-4 mx-2 text-gray-400')
        <span class="text-gray-500 dark:text-gray-400">{{ __('Edit Location') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Location') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Update location information') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <form action="{{ route('locations.update', $location) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Location Information') }}</h3>

                        <div class="space-y-4">
                            <x-forms.input
                                label="Location Name"
                                name="name"
                                type="text"
                                value="{{ old('name', $location->name) }}"
                                required />

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Address') }} <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    id="address"
                                    name="address"
                                    rows="3"
                                    class="w-full px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>{{ old('address', $location->address) }}</textarea>
                                @error('address')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Status') }}
                                </label>
                                <div class="flex items-center">
                                    <input type="hidden" name="is_active" value="0">
                                    <input
                                        type="checkbox"
                                        id="is_active"
                                        name="is_active"
                                        value="1"
                                        {{ old('is_active', $location->is_active) ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                        {{ __('Active Location') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Working Hours') }}</h3>

                        <div class="grid grid-cols-2 gap-4">
                            <x-forms.input
                                label="Start Time"
                                name="work_start_time"
                                type="time"
                                value="{{ old('work_start_time', $location->work_start_time->format('H:i')) }}"
                                required />

                            <x-forms.input
                                label="End Time"
                                name="work_end_time"
                                type="time"
                                value="{{ old('work_end_time', $location->work_end_time->format('H:i')) }}"
                                required />
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Location Settings') }}</h3>

                        <div class="space-y-4">
                            <div>
                                <label for="radius" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Attendance Radius (meters)') }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="range"
                                    id="radius-slider"
                                    min="50"
                                    max="1000"
                                    step="10"
                                    value="{{ old('radius', $location->radius) }}"
                                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">
                                <input
                                    type="number"
                                    id="radius"
                                    name="radius"
                                    min="50"
                                    max="1000"
                                    value="{{ old('radius', $location->radius) }}"
                                    class="mt-2 w-full px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Employees must be within this radius to clock in/out') }}</p>
                                @error('radius')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('GPS Coordinates') }}</h3>

                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <x-forms.input
                                    label="Latitude"
                                    name="latitude"
                                    type="number"
                                    step="any"
                                    value="{{ old('latitude', $location->latitude) }}" />

                                <x-forms.input
                                    label="Longitude"
                                    name="longitude"
                                    type="number"
                                    step="any"
                                    value="{{ old('longitude', $location->longitude) }}" />
                            </div>

                            <button type="button" id="get-location-btn" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center">
                                @svg('fas-map-marker-alt', 'w-5 h-5 mr-2')
                                {{ __('Update Current Location') }}
                            </button>

                            @if($location->latitude && $location->longitude)
                                <div class="bg-green-50 dark:bg-green-900 border-l-4 border-green-500 p-4 rounded-md">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            @svg('fas-check-circle', 'h-5 w-5 text-green-500')
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-green-700 dark:text-green-200">
                                                {{ __('GPS coordinates are set. Location validation is enabled.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-500 p-4 rounded-md">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            @svg('fas-exclamation-triangle', 'h-5 w-5 text-yellow-500')
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                                {{ __('No GPS coordinates set. Location validation will be skipped.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Employee Assignment') }}</h3>

                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Assigned Employees') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $location->employees()->count() }} {{ __('employees assigned') }}</p>
                                </div>
                                <div class="text-2xl font-bold text-gray-600 dark:text-gray-400">
                                    {{ $location->employees()->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('locations.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-lg">
                    {{ __('Cancel') }}
                </a>
                <x-button type="primary">
                    @svg('fas-save', 'w-5 h-5 mr-2')
                    {{ __('Update Location') }}
                </x-button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radiusSlider = document.getElementById('radius-slider');
            const radiusInput = document.getElementById('radius');
            const getLocationBtn = document.getElementById('get-location-btn');
            const latitudeInput = document.querySelector('input[name="latitude"]');
            const longitudeInput = document.querySelector('input[name="longitude"]');

            // Sync slider and input
            radiusSlider.addEventListener('input', function() {
                radiusInput.value = this.value;
            });

            radiusInput.addEventListener('input', function() {
                radiusSlider.value = this.value;
            });

            // Get current location
            getLocationBtn.addEventListener('click', function() {
                if (navigator.geolocation) {
                    getLocationBtn.disabled = true;
                    getLocationBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Getting Location...';

                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            latitudeInput.value = position.coords.latitude.toFixed(8);
                            longitudeInput.value = position.coords.longitude.toFixed(8);

                            getLocationBtn.disabled = false;
                            getLocationBtn.innerHTML = '@svg("fas-check", "w-5 h-5 mr-2") Location Updated';

                            setTimeout(() => {
                                getLocationBtn.innerHTML = '@svg("fas-map-marker-alt", "w-5 h-5 mr-2") Update Current Location';
                            }, 2000);
                        },
                        function(error) {
                            alert('Unable to get your location: ' + error.message);
                            getLocationBtn.disabled = false;
                            getLocationBtn.innerHTML = '@svg("fas-map-marker-alt", "w-5 h-5 mr-2") Update Current Location';
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        }
                    );
                } else {
                    alert('Geolocation is not supported by this browser.');
                }
            });
        });
    </script>
    @endpush
</x-layouts.app>
