<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        @svg('fas-chevron-right', 'h-4 w-4 mx-2 text-gray-400')
        <a href="{{ route('locations.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Locations') }}</a>
        @svg('fas-chevron-right', 'h-4 w-4 mx-2 text-gray-400')
        <span class="text-gray-500 dark:text-gray-400">{{ $location->name }}</span>
    </div>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $location->name }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Location Details') }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('locations.edit', $location) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                @svg('fas-edit', 'w-5 h-5 mr-2')
                {{ __('Edit') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Location Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Location Information') }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Address') }}</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $location->address }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Working Hours') }}</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $location->work_start_time->format('H:i') }} - {{ $location->work_end_time->format('H:i') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Attendance Radius') }}</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $location->radius }} meters</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('GPS Coordinates') }}</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                @if($location->latitude && $location->longitude)
                                    {{ $location->latitude }}, {{ $location->longitude }}
                                @else
                                    {{ __('Not set') }}
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</label>
                            <div class="mt-1">
                                @if($location->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        @svg('fas-check-circle', 'w-3 h-3 mr-1')
                                        {{ __('Active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                        @svg('fas-times-circle', 'w-3 h-3 mr-1')
                                        {{ __('Inactive') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assigned Employees -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Assigned Employees') }}</h3>

                    @if($location->employees->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($location->employees as $employee)
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ $employee->initials }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $employee->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $employee->employee_id }}</p>
                                    </div>
                                    @if($employee->pivot->is_primary)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            @svg('fas-star', 'w-3 h-3 mr-1')
                                            {{ __('Primary') }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            @svg('fas-users', 'w-12 h-12 text-gray-400 mx-auto mb-4')
                            <p class="text-gray-500 dark:text-gray-400">{{ __('No employees assigned to this location') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Today's Attendance -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Today\'s Attendance') }}</h3>

                    @if($location->attendances->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Employee') }}</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Check In') }}</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Check Out') }}</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($location->attendances as $attendance)
                                        <tr>
                                            <td class="px-3 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8">
                                                        <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                                                {{ $attendance->employee->initials }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-2">
                                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $attendance->employee->name }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $attendance->check_in ? $attendance->check_in->format('H:i') : '-' }}
                                            </td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $attendance->check_out ? $attendance->check_out->format('H:i') : '-' }}
                                            </td>
                                            <td class="px-3 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                    @if($attendance->status === 'present') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($attendance->status === 'late') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                                    @elseif($attendance->status === 'half_day') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            @svg('fas-calendar-times', 'w-12 h-12 text-gray-400 mx-auto mb-4')
                            <p class="text-gray-500 dark:text-gray-400">{{ __('No attendance records for today') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Statistics') }}</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Employees') }}</span>
                            <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $location->employees->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Present Today') }}</span>
                            <span class="text-lg font-semibold text-green-600 dark:text-green-400">{{ $location->attendances->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Checked Out') }}</span>
                            <span class="text-lg font-semibold text-blue-600 dark:text-blue-400">{{ $location->attendances->whereNotNull('check_out')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Test -->
            @if($location->latitude && $location->longitude)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Location Test') }}</h3>

                        <button id="test-location-btn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center mb-4">
                            @svg('fas-map-marker-alt', 'w-5 h-5 mr-2')
                            {{ __('Test Current Location') }}
                        </button>

                        <div id="location-result" class="hidden">
                            <div class="p-3 rounded-lg">
                                <p class="text-sm font-medium" id="location-status"></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" id="location-distance"></p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- GPS Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Location Details') }}</h3>

                    @if($location->latitude && $location->longitude)
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Latitude') }}</label>
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $location->latitude }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Longitude') }}</label>
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $location->longitude }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Radius') }}</label>
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $location->radius }}m</p>
                            </div>
                        </div>
                    @else
                        <div class="text-center">
                            @svg('fas-map-marker-alt', 'w-8 h-8 text-gray-400 mx-auto mb-2')
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('GPS coordinates not set') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($location->latitude && $location->longitude)
        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const testLocationBtn = document.getElementById('test-location-btn');
                const locationResult = document.getElementById('location-result');
                const locationStatus = document.getElementById('location-status');
                const locationDistance = document.getElementById('location-distance');

                testLocationBtn.addEventListener('click', function() {
                    if (navigator.geolocation) {
                        testLocationBtn.disabled = true;
                        testLocationBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Testing...';

                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                // Test location against API
                                fetch(`/locations/{{ $location->id }}/check-radius`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        latitude: position.coords.latitude,
                                        longitude: position.coords.longitude
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    locationResult.classList.remove('hidden');

                                    if (data.within_radius) {
                                        locationResult.firstElementChild.className = 'p-3 rounded-lg bg-green-100 dark:bg-green-900';
                                        locationStatus.textContent = 'Within attendance radius';
                                        locationStatus.className = 'text-sm font-medium text-green-800 dark:text-green-200';
                                    } else {
                                        locationResult.firstElementChild.className = 'p-3 rounded-lg bg-red-100 dark:bg-red-900';
                                        locationStatus.textContent = 'Outside attendance radius';
                                        locationStatus.className = 'text-sm font-medium text-red-800 dark:text-red-200';
                                    }

                                    locationDistance.textContent = `Current location: ${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}`;
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('Failed to test location');
                                })
                                .finally(() => {
                                    testLocationBtn.disabled = false;
                                    testLocationBtn.innerHTML = '@svg("fas-map-marker-alt", "w-5 h-5 mr-2") Test Current Location';
                                });
                            },
                            function(error) {
                                alert('Unable to get your location: ' + error.message);
                                testLocationBtn.disabled = false;
                                testLocationBtn.innerHTML = '@svg("fas-map-marker-alt", "w-5 h-5 mr-2") Test Current Location';
                            }
                        );
                    } else {
                        alert('Geolocation is not supported by this browser.');
                    }
                });
            });
        </script>
        @endpush
    @endif
</x-layouts.app>
