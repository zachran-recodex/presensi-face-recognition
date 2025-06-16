<x-layouts.app>
    <!-- Page Title -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Attendance') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            {{ __('Manage your daily attendance with face recognition') }}
        </p>
    </div>

    <!-- Today's Status Card -->
    <div class="mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Today\'s Status') }}</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Check In Status -->
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        @if($hasCheckedIn)
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        @else
                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-800 dark:text-gray-100">{{ __('Check In') }}</h3>
                        @if($hasCheckedIn)
                            @php $checkIn = $todayAttendances->where('type', 'check_in')->first(); @endphp
                            <p class="text-sm text-green-600 dark:text-green-400">
                                {{ $checkIn->attendance_time->format('H:i:s') }}
                                @if($checkIn->location)
                                    - {{ $checkIn->location->name }}
                                @endif
                            </p>
                            @if($checkIn->is_verified)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ✓ Verified
                                </span>
                            @endif
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Not checked in yet') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Check Out Status -->
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        @if($hasCheckedOut)
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        @else
                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-800 dark:text-gray-100">{{ __('Check Out') }}</h3>
                        @if($hasCheckedOut)
                            @php $checkOut = $todayAttendances->where('type', 'check_out')->first(); @endphp
                            <p class="text-sm text-blue-600 dark:text-blue-400">
                                {{ $checkOut->attendance_time->format('H:i:s') }}
                            </p>
                            @if($checkOut->is_verified)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    ✓ Verified
                                </span>
                            @endif
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Not checked out yet') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Face Enrollment Status -->
    @if(!auth()->user()->is_face_enrolled)
        <div class="mb-6">
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">{{ __('Face Not Enrolled') }}</h3>
                        <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                            {{ __('You need to enroll your face before you can use face recognition for attendance.') }}
                        </p>
                        <div class="mt-2">
                            <a href="{{ route('face.enroll') }}" class="text-sm bg-yellow-100 hover:bg-yellow-200 text-yellow-800 px-3 py-1 rounded-md font-medium">
                                {{ __('Enroll Face Now') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
            @if(!$hasCheckedIn)
                <a href="{{ route('attendance.check-in') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold text-center transition-colors {{ !auth()->user()->is_face_enrolled ? 'opacity-50 cursor-not-allowed' : '' }}"
                   @if(!auth()->user()->is_face_enrolled) onclick="event.preventDefault(); alert('Please enroll your face first');" @endif>
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    {{ __('Check In') }}
                </a>
            @elseif(!$hasCheckedOut)
                <a href="{{ route('attendance.check-out') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold text-center transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    {{ __('Check Out') }}
                </a>
            @else
                <div class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-6 py-3 rounded-lg font-semibold text-center">
                    {{ __('Attendance completed for today') }}
                </div>
            @endif

            <a href="{{ route('attendance.history') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold text-center transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ __('View History') }}
            </a>
        </div>
    </div>

    <!-- This Month's Attendance Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Attendances -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Recent Attendances') }}</h3>

                @if($thisMonthAttendances->count() > 0)
                    <div class="space-y-4 max-h-80 overflow-y-auto">
                        @foreach($thisMonthAttendances->take(10) as $attendance)
                            <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-0">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        @if($attendance->type === 'check_in')
                                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 dark:text-gray-100">
                                            {{ ucfirst(str_replace('_', ' ', $attendance->type)) }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $attendance->attendance_time->format('M d, Y H:i') }}
                                            @if($attendance->location)
                                                • {{ $attendance->location->name }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($attendance->is_verified)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✓ Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            ✗ Not Verified
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No attendances yet') }}</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Start by checking in today!') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Monthly Statistics -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('This Month Statistics') }}</h3>

                @php
                    $checkIns = $thisMonthAttendances->where('type', 'check_in');
                    $checkOuts = $thisMonthAttendances->where('type', 'check_out');
                    $verifiedAttendances = $thisMonthAttendances->where('is_verified', true);
                @endphp

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $checkIns->count() }}</div>
                        <div class="text-sm text-green-700 dark:text-green-300">{{ __('Check Ins') }}</div>
                    </div>

                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $checkOuts->count() }}</div>
                        <div class="text-sm text-blue-700 dark:text-blue-300">{{ __('Check Outs') }}</div>
                    </div>

                    <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $verifiedAttendances->count() }}</div>
                        <div class="text-sm text-purple-700 dark:text-purple-300">{{ __('Verified') }}</div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ $thisMonthAttendances->count() }}</div>
                        <div class="text-sm text-gray-700 dark:text-gray-300">{{ __('Total') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
