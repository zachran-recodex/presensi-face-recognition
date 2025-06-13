<x-layouts.app>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Dashboard')}}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Welcome to the attendance system') }}</p>
    </div>

    @can('see report')
        <!-- Admin Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Employees') }}</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ \App\Models\Employee::count() }}</p>
                        <p class="text-xs text-gray-500 flex items-center mt-1">
                            @svg('fas-user-check', 'h-4 w-4 mr-1')
                            {{ \App\Models\Employee::where('face_registered', true)->count() }} {{ __('face registered') }}
                        </p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                        @svg('fas-users', 'h-6 w-6 text-blue-500 dark:text-blue-300')
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Present Today') }}</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ \App\Models\Attendance::whereDate('date', today())->whereNotNull('check_in')->count() }}</p>
                        <p class="text-xs text-gray-500 flex items-center mt-1">
                            @svg('fas-sign-out-alt', 'h-4 w-4 mr-1')
                            {{ \App\Models\Attendance::whereDate('date', today())->whereNotNull('check_out')->count() }} {{ __('checked out') }}
                        </p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                        @svg('fas-calendar-check', 'h-6 w-6 text-green-500 dark:text-green-300')
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Late Today') }}</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ \App\Models\Attendance::whereDate('date', today())->where('status', 'late')->count() }}</p>
                        <p class="text-xs text-gray-500 flex items-center mt-1">
                            @svg('fas-clock', 'h-4 w-4 mr-1')
                            {{ __('Late arrivals') }}
                        </p>
                    </div>
                    <div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-full">
                        @svg('fas-exclamation-triangle', 'h-6 w-6 text-orange-500 dark:text-orange-300')
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Active Locations') }}</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ \App\Models\Location::where('is_active', true)->count() }}</p>
                        <p class="text-xs text-gray-500 flex items-center mt-1">
                            @svg('fas-map-marker-alt', 'h-4 w-4 mr-1')
                            {{ __('Office locations') }}
                        </p>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                        @svg('fas-building', 'h-6 w-6 text-purple-500 dark:text-purple-300')
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Today's Attendance -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ __('Today\'s Attendance') }}</h3>
                        <a href="{{ route('attendances.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                            {{ __('View All') }}
                        </a>
                    </div>

                    @php
                        $todayAttendances = \App\Models\Attendance::with(['employee', 'location'])
                            ->whereDate('date', today())
                            ->latest('check_in')
                            ->limit(5)
                            ->get();
                    @endphp

                    @if($todayAttendances->count() > 0)
                        <div class="space-y-3">
                            @foreach($todayAttendances as $attendance)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                                    {{ $attendance->employee->initials }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $attendance->employee->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $attendance->location->name }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-900 dark:text-gray-100">
                                            @if($attendance->check_in)
                                                {{ $attendance->check_in->format('H:i') }}
                                            @endif
                                        </p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                            @if($attendance->status === 'present') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($attendance->status === 'late') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            @svg('fas-calendar-times', 'w-8 h-8 text-gray-400 mx-auto mb-2')
                            <p class="text-gray-500 dark:text-gray-400">{{ __('No attendance records today') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Employees Without Face Registration -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ __('Face Registration Needed') }}</h3>
                        <a href="{{ route('employees.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                            {{ __('Manage') }}
                        </a>
                    </div>

                    @php
                        $unregisteredEmployees = \App\Models\Employee::where('face_registered', false)
                            ->where('is_active', true)
                            ->limit(5)
                            ->get();
                    @endphp

                    @if($unregisteredEmployees->count() > 0)
                        <div class="space-y-3">
                            @foreach($unregisteredEmployees as $employee)
                                <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                                    {{ $employee->initials }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $employee->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $employee->employee_id }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="{{ route('employees.show', $employee) }}"
                                           class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300">
                                            @svg('fas-camera', 'w-4 h-4')
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if(\App\Models\Employee::where('face_registered', false)->where('is_active', true)->count() > 5)
                            <div class="mt-3 text-center">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ __('and') }} {{ \App\Models\Employee::where('face_registered', false)->where('is_active', true)->count() - 5 }} {{ __('more employees') }}
                                </p>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            @svg('fas-check-circle', 'w-8 h-8 text-green-400 mx-auto mb-2')
                            <p class="text-green-600 dark:text-green-400">{{ __('All employees have registered faces!') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        <!-- Employee Dashboard -->
        @php
            $employee = \App\Models\Employee::where('employee_id', Auth::user()->username)
                ->orWhere('email', Auth::user()->email)
                ->first();
        @endphp

        @if($employee)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Today's Status -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Today\'s Status') }}</p>
                            @php
                                $todayAttendance = $employee->todayAttendance();
                            @endphp
                            @if($todayAttendance)
                                <p class="text-lg font-bold text-green-600 dark:text-green-400 mt-1">{{ __('Present') }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $todayAttendance->check_in ? $todayAttendance->check_in->format('H:i') : '-' }}
                                </p>
                            @else
                                <p class="text-lg font-bold text-gray-500 dark:text-gray-400 mt-1">{{ __('Not checked in') }}</p>
                            @endif
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                            @svg('fas-calendar-day', 'h-6 w-6 text-blue-500 dark:text-blue-300')
                        </div>
                    </div>
                </div>

                <!-- Face Registration Status -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Face Registration') }}</p>
                            @if($employee->face_registered)
                                <p class="text-lg font-bold text-green-600 dark:text-green-400 mt-1">{{ __('Registered') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ __('Ready for attendance') }}</p>
                            @else
                                <p class="text-lg font-bold text-red-600 dark:text-red-400 mt-1">{{ __('Not Registered') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ __('Contact HR') }}</p>
                            @endif
                        </div>
                        <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                            @svg('fas-user-check', 'h-6 w-6 text-green-500 dark:text-green-300')
                        </div>
                    </div>
                </div>

                <!-- This Month Attendance -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('This Month') }}</p>
                            @php
                                $monthlyAttendances = $employee->monthlyAttendances()->get();
                                $presentDays = $monthlyAttendances->where('status', 'present')->count();
                            @endphp
                            <p class="text-lg font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $presentDays }} {{ __('days') }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ __('Present days') }}</p>
                        </div>
                        <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                            @svg('fas-chart-line', 'h-6 w-6 text-purple-500 dark:text-purple-300')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Quick Actions') }}</h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('attendance.employee') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg flex items-center">
                            @svg('fas-clock', 'w-5 h-5 mr-2')
                            {{ __('Check Attendance') }}
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Employee Not Found -->
            <div class="bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-500 p-6 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        @svg('fas-exclamation-triangle', 'h-6 w-6 text-yellow-500')
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-yellow-800 dark:text-yellow-200">{{ __('Employee Record Not Found') }}</h3>
                        <p class="text-yellow-700 dark:text-yellow-300 mt-2">
                            {{ __('Your user account is not linked to an employee record. Please contact HR to set up your employee profile.') }}
                        </p>
                        <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-2">
                            {{ __('Username') }}: {{ Auth::user()->username }}<br>
                            {{ __('Email') }}: {{ Auth::user()->email }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    @endcan

</x-layouts.app>
