<x-layouts.app>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Dashboard')}}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            @if(auth()->user()->isAdmin())
                {{ __('Welcome to the admin dashboard') }}
            @else
                {{ __('Welcome to your attendance dashboard') }}
            @endif
        </p>
    </div>

    @if(auth()->user()->isAdmin())
        <!-- Admin Dashboard -->
        @php
            $todayAttendances = \App\Models\Attendance::with(['user', 'location'])->today()->get();
            $totalUsers = \App\Models\User::where('role', 'user')->count();
            $totalLocations = \App\Models\Location::count();
            $enrolledUsers = \App\Models\User::where('is_face_enrolled', true)->count();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Users -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Users') }}</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $totalUsers }}</p>
                        <p class="text-xs text-gray-500 flex items-center mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            {{ $enrolledUsers }} enrolled
                        </p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 dark:text-blue-300"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Locations -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Locations') }}</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $totalLocations }}</p>
                        <p class="text-xs text-gray-500 flex items-center mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            {{ \App\Models\Location::where('is_active', true)->count() }} active
                        </p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 dark:text-green-300"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Today Check-ins -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Today Check-ins') }}</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $todayAttendances->where('type', 'check_in')->count() }}</p>
                        <p class="text-xs text-gray-500 flex items-center mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $todayAttendances->where('type', 'check_in')->where('is_verified', true)->count() }} verified
                        </p>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500 dark:text-purple-300"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Today Check-outs -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Today Check-outs') }}</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $todayAttendances->where('type', 'check_out')->count() }}</p>
                        <p class="text-xs text-gray-500 flex items-center mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $todayAttendances->where('type', 'check_out')->where('is_verified', true)->count() }} verified
                        </p>
                    </div>
                    <div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-500 dark:text-orange-300"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions for Admin -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Quick Actions') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.locations.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800 dark:text-gray-100">{{ __('Manage Locations') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Add and configure attendance locations') }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.attendance.history') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-green-100 dark:bg-green-900 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800 dark:text-gray-100">{{ __('View All Attendances') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Monitor all user attendance records') }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('settings.profile.edit') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-purple-100 dark:bg-purple-900 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800 dark:text-gray-100">{{ __('Settings') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your account settings') }}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    @else
        <!-- User Dashboard -->
        @php
            $user = auth()->user();
            $todayAttendances = $user->getTodayAttendances();
            $hasCheckedIn = $user->hasCheckedInToday();
            $hasCheckedOut = $user->hasCheckedOutToday();
            $thisMonthAttendances = $user->attendances()->thisMonth()->count();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Face Enrollment Status -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Face Recognition') }}</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-gray-100 mt-1">
                            @if($user->is_face_enrolled)
                                {{ __('Enrolled') }}
                            @else
                                {{ __('Not Enrolled') }}
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($user->is_face_enrolled)
                                {{ __('Ready for attendance') }}
                            @else
                                {{ __('Enrollment required') }}
                            @endif
                        </p>
                    </div>
                    <div class="bg-{{ $user->is_face_enrolled ? 'green' : 'red' }}-100 dark:bg-{{ $user->is_face_enrolled ? 'green' : 'red' }}-900 p-3 rounded-full">
                        @if($user->is_face_enrolled)
                            <svg class="h-6 w-6 text-green-500 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @else
                            <svg class="h-6 w-6 text-red-500 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Today's Status -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Today\'s Status') }}</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-gray-100 mt-1">
                            @if($hasCheckedOut)
                                {{ __('Completed') }}
                            @elseif($hasCheckedIn)
                                {{ __('Checked In') }}
                            @else
                                {{ __('Not Started') }}
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($hasCheckedIn)
                                {{ $user->getTodayCheckIn()->attendance_time->format('H:i') }}
                            @else
                                {{ __('No check-in yet') }}
                            @endif
                        </p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 dark:text-blue-300"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- This Month Attendances -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('This Month') }}</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $thisMonthAttendances }}</p>
                        <p class="text-xs text-gray-500 flex items-center mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            {{ __('Total activities') }}
                        </p>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500 dark:text-purple-300"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Quick Action -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="text-center">
                    @if(!$user->is_face_enrolled)
                        <a href="{{ route('face.enroll') }}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ __('Enroll Face') }}
                        </a>
                    @elseif(!$hasCheckedIn)
                        <a href="{{ route('attendance.check-in') }}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            {{ __('Check In') }}
                        </a>
                    @elseif(!$hasCheckedOut)
                        <a href="{{ route('attendance.check-out') }}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            {{ __('Check Out') }}
                        </a>
                    @else
                        <div class="text-center py-4">
                            <div class="text-green-600 dark:text-green-400 font-medium">
                                ✓ {{ __('Attendance Complete') }}
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('See you tomorrow!') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions for User -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Quick Actions') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('attendance.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800 dark:text-gray-100">{{ __('Attendance') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Check in/out and view status') }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('attendance.history') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-green-100 dark:bg-green-900 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800 dark:text-gray-100">{{ __('History') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('View your attendance records') }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ $user->is_face_enrolled ? route('face.edit') : route('face.enroll') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-purple-100 dark:bg-purple-900 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800 dark:text-gray-100">
                                @if($user->is_face_enrolled)
                                    {{ __('Update Face') }}
                                @else
                                    {{ __('Enroll Face') }}
                                @endif
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Manage face recognition') }}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @endif

</x-layouts.app>
