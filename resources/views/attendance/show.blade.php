<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500">{{ __('Attendance Details') }}</span>
    </div>

    <!-- Page Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ __('Attendance Details') }}</h1>
            <p class="text-gray-600 mt-1">{{ __('Detailed information about this attendance record') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('dashboard') }}" class="btn-secondary">
                {{ __('Back to Dashboard') }}
            </a>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Attendance Information -->
            <div class="card">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Attendance Information') }}</h2>
                
                <div class="space-y-4">
                    <!-- User Info -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('User') }}</label>
                        <div class="mt-1 flex items-center">
                            <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-medium mr-3">
                                {{ $attendance->user->initials() }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $attendance->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $attendance->user->employee_id ?: $attendance->user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Type') }}</label>
                        <div class="mt-1 flex items-center">
                            @if($attendance->type === 'check_in')
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-green-800">{{ __('Check In') }}</span>
                            @else
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-blue-800">{{ __('Check Out') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Date & Time -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Date & Time') }}</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $attendance->attendance_time->format('l, F j, Y') }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $attendance->attendance_time->format('H:i:s') }} 
                            ({{ $attendance->attendance_time->diffForHumans() }})
                        </p>
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Location') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $attendance->location->name ?? 'N/A' }}</p>
                        @if($attendance->location)
                            <p class="text-sm text-gray-500">{{ $attendance->location->address }}</p>
                        @endif
                    </div>

                    <!-- GPS Coordinates -->
                    @if($attendance->latitude && $attendance->longitude)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('GPS Coordinates') }}</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ number_format($attendance->latitude, 6) }}, {{ number_format($attendance->longitude, 6) }}
                        </p>
                    </div>
                    @endif

                    <!-- Notes -->
                    @if($attendance->notes)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Notes') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $attendance->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Status & Verification -->
            <div class="card">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Status & Verification') }}</h2>
                
                <div class="space-y-4">
                    <!-- Face Verification Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Face Verification') }}</label>
                        <div class="mt-1">
                            @if($attendance->is_verified)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    ✓ {{ __('Verified') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    ✗ {{ __('Not Verified') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Confidence Level -->
                    @if($attendance->confidence_level)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Confidence Level') }}</label>
                        <div class="mt-1 flex items-center">
                            <div class="w-32 bg-gray-200 rounded-full h-3 mr-3">
                                <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $attendance->confidence_level * 100 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ number_format($attendance->confidence_level * 100, 1) }}%</span>
                        </div>
                    </div>
                    @endif

                    <!-- Late Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Punctuality Status') }}</label>
                        <div class="mt-1">
                            @if($attendance->is_late)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    ⏰ 
                                    @if($attendance->type === 'check_in')
                                        {{ __('Late') }} ({{ $attendance->late_minutes }} {{ __('minutes') }})
                                    @else
                                        {{ __('Early Departure') }} ({{ $attendance->late_minutes }} {{ __('minutes') }})
                                    @endif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    ✓ {{ __('On Time') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- User's Work Schedule -->
                    @if($attendance->user->check_in_time || $attendance->user->check_out_time)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Work Schedule') }}</label>
                        <div class="mt-1 text-sm text-gray-900">
                            @if($attendance->user->check_in_time)
                                <p>{{ __('Check-in') }}: {{ \Carbon\Carbon::createFromFormat('H:i:s', $attendance->user->check_in_time)->format('H:i') }}</p>
                            @endif
                            @if($attendance->user->check_out_time)
                                <p>{{ __('Check-out') }}: {{ \Carbon\Carbon::createFromFormat('H:i:s', $attendance->user->check_out_time)->format('H:i') }}</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Created At -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Record Created') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $attendance->created_at->format('F j, Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Face Image Preview (if available) -->
        @if($attendance->face_image && auth()->user()->isAdmin())
        <div class="mt-6 card">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Face Image') }}</h2>
            <div class="text-center">
                <img src="data:image/jpeg;base64,{{ $attendance->face_image }}" 
                     alt="Face Image" 
                     class="mx-auto rounded-lg shadow-md max-w-xs max-h-64 object-cover">
                <p class="mt-2 text-xs text-gray-500">{{ __('Face image used for verification') }}</p>
            </div>
        </div>
        @endif
    </div>
</x-layouts.app>