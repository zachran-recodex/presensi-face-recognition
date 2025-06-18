<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
           class="text-blue-600 hover:underline">{{ __('Dashboard') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.attendance.history') }}"
               class="text-blue-600 hover:underline">{{ __('All Attendance Records') }}</a>
        @else
            <a href="{{ route('attendance.history') }}"
               class="text-blue-600 hover:underline">{{ __('Attendance History') }}</a>
        @endif
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500">{{ __('Attendance Details') }}</span>
    </div>

    <!-- Page Title -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ __('Attendance Details') }}</h1>
        <p class="text-gray-600 mt-1">
            {{ __('Detailed information about this attendance record') }}
        </p>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Basic Information') }}</h2>

                    <div class="space-y-4">
                        <!-- User Info -->
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-lg font-medium text-gray-700">
                                        {{ $attendance->user->initials() }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">{{ $attendance->user->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $attendance->user->employee_id ?: $attendance->user->email }}</p>
                            </div>
                        </div>

                        <!-- Type -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">{{ __('Type') }}</span>
                            <div class="flex items-center">
                                @if($attendance->type === 'check_in')
                                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-2">
                                        <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-green-800">{{ __('Check In') }}</span>
                                @else
                                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-blue-800">{{ __('Check Out') }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Date & Time -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">{{ __('Date & Time') }}</span>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $attendance->attendance_time->format('M d, Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $attendance->attendance_time->format('H:i:s') }}
                                </div>
                            </div>
                        </div>

                        <!-- Verification Status -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">{{ __('Verification Status') }}</span>
                            @if($attendance->is_verified)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ✓ {{ __('Verified') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    ✗ {{ __('Not Verified') }}
                                </span>
                            @endif
                        </div>

                        <!-- Confidence Level -->
                        @if($attendance->confidence_level)
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">{{ __('Confidence Level') }}</span>
                                <div class="flex items-center">
                                    <div class="w-20 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $attendance->confidence_level * 100 }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ number_format($attendance->confidence_level * 100, 1) }}%
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Location Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Location Information') }}</h2>

                    @if($attendance->location)
                        <div class="space-y-4">
                            <!-- Location Name -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">{{ __('Location') }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ $attendance->location->name }}</span>
                            </div>

                            <!-- Address -->
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('Address') }}</span>
                                <p class="text-sm text-gray-900 mt-1">{{ $attendance->location->address }}</p>
                            </div>

                            <!-- Location Coordinates -->
                            @if($attendance->location->latitude && $attendance->location->longitude)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-500">{{ __('Location Coordinates') }}</span>
                                    <span class="text-sm text-gray-900">
                                        {{ number_format($attendance->location->latitude, 6) }}, {{ number_format($attendance->location->longitude, 6) }}
                                    </span>
                                </div>
                            @endif

                            <!-- User Coordinates -->
                            @if($attendance->latitude && $attendance->longitude)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-500">{{ __('User Coordinates') }}</span>
                                    <span class="text-sm text-gray-900">
                                        {{ number_format($attendance->latitude, 6) }}, {{ number_format($attendance->longitude, 6) }}
                                    </span>
                                </div>

                                <!-- Distance from Location -->
                                @if($attendance->location->latitude && $attendance->location->longitude)
                                    @php
                                        $distance = $attendance->location->isWithinRadius($attendance->latitude, $attendance->longitude) ?
                                            calculateDistance($attendance->location->latitude, $attendance->location->longitude, $attendance->latitude, $attendance->longitude) :
                                            'N/A';
                                    @endphp
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-500">{{ __('Distance from Location') }}</span>
                                        <span class="text-sm text-gray-900">
                                            @if(is_numeric($distance))
                                                {{ number_format($distance) }}m
                                                @if($distance <= $attendance->location->radius)
                                                    <span class="text-green-600 ml-1">✓</span>
                                                @else
                                                    <span class="text-red-600 ml-1">✗</span>
                                                @endif
                                            @else
                                                {{ $distance }}
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            @endif

                            <!-- Allowed Radius -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">{{ __('Allowed Radius') }}</span>
                                <span class="text-sm text-gray-900">{{ $attendance->location->radius }}m</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No location data') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('Location information is not available') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Face Image and Notes -->
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Face Image -->
            @if($attendance->face_image)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Captured Face Image') }}</h2>
                        <div class="text-center">
                            <img src="data:image/jpeg;base64,{{ $attendance->face_image }}"
                                 alt="Face capture"
                                 class="max-w-full h-auto rounded-lg border border-gray-300 mx-auto"
                                 style="max-height: 300px;">
                            <p class="text-sm text-gray-500 mt-2">
                                {{ __('Face image captured during attendance') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Notes and Additional Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Additional Information') }}</h2>

                    <div class="space-y-4">
                        <!-- Notes -->
                        <div>
                            <span class="text-sm font-medium text-gray-500">{{ __('Notes') }}</span>
                            @if($attendance->notes)
                                <p class="text-sm text-gray-900 mt-1 p-3 bg-gray-50 rounded-lg">
                                    {{ $attendance->notes }}
                                </p>
                            @else
                                <p class="text-sm text-gray-500 mt-1">{{ __('No notes provided') }}</p>
                            @endif
                        </div>

                        <!-- Created At -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">{{ __('Record Created') }}</span>
                            <span class="text-sm text-gray-900">
                                {{ $attendance->created_at->format('M d, Y H:i:s') }}
                            </span>
                        </div>

                        <!-- Updated At -->
                        @if($attendance->updated_at != $attendance->created_at)
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">{{ __('Last Updated') }}</span>
                                <span class="text-sm text-gray-900">
                                    {{ $attendance->updated_at->format('M d, Y H:i:s') }}
                                </span>
                            </div>
                        @endif

                        <!-- Record ID -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">{{ __('Record ID') }}</span>
                            <span class="text-sm text-gray-900 font-mono">{{ $attendance->id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-end space-x-4">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.attendance.history') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                    {{ __('Back to All Records') }}
                </a>
            @else
                <a href="{{ route('attendance.history') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                    {{ __('Back to History') }}
                </a>
            @endif

            <button onclick="window.print()"
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                {{ __('Print Details') }}
            </button>
        </div>
    </div>

    @php
        function calculateDistance($lat1, $lon1, $lat2, $lon2) {
            $earthRadius = 6371000; // Earth radius in meters
            $latFrom = deg2rad($lat1);
            $lonFrom = deg2rad($lon1);
            $latTo = deg2rad($lat2);
            $lonTo = deg2rad($lon2);
            $latDelta = $latTo - $latFrom;
            $lonDelta = $lonTo - $lonFrom;
            $a = sin($latDelta / 2) * sin($latDelta / 2) +
                cos($latFrom) * cos($latTo) *
                sin($lonDelta / 2) * sin($lonDelta / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            return $earthRadius * $c;
        }
    @endphp
</x-layouts.app>
