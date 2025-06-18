<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">{{ __('Dashboard') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500">{{ __('Attendance Dashboard') }}</span>
    </div>

    <!-- Page Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ __('Attendance Dashboard') }}</h1>
            <p class="text-gray-600 mt-1">{{ __('Monitor today\'s attendance activity') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.attendance.history') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                {{ __('View History') }}
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Today -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Total Today') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_today']) }}</p>
                </div>
            </div>
        </div>

        <!-- Check-ins Today -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Check-ins') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['check_in_today']) }}</p>
                </div>
            </div>
        </div>

        <!-- Check-outs Today -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Check-outs') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['check_out_today']) }}</p>
                </div>
            </div>
        </div>

        <!-- Verified Today -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Verified') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['verified_today']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Attendances -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-800">{{ __('Today\'s Attendances') }}</h2>
                <div class="flex space-x-2">
                    <span class="text-sm text-gray-600">
                        {{ __('Last updated') }}: {{ now()->format('g:i A') }}
                    </span>
                    <button onclick="window.location.reload()"
                            class="text-blue-600 hover:text-blue-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                </div>
            </div>

            @if($todayAttendances->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Employee') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Type') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Location') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Time') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Confidence') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($todayAttendances as $attendance)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-medium">
                                        {{ $attendance->user->initials() }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $attendance->user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $attendance->user->employee_id ?? 'ID: ' . $attendance->user->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $attendance->type === 'check_in' ? 'bg-green-100 text-green-800/20' : 'bg-blue-100 text-blue-800/20' }}">
                                    {{ $attendance->type === 'check_in' ? __('Check In') : __('Check Out') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $attendance->location->name }}</div>
                                <div class="text-sm text-gray-500">{{ $attendance->location->address }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attendance->attendance_time->format('g:i A') }}
                                <div class="text-xs text-gray-500">
                                    {{ $attendance->attendance_time->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $attendance->is_verified ? 'bg-green-100 text-green-800/20' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $attendance->is_verified ? __('Verified') : __('Pending') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($attendance->confidence_level)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                        {{ $attendance->confidence_level >= 0.8 ? 'bg-green-100 text-green-700/20' :
                                           ($attendance->confidence_level >= 0.6 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ number_format($attendance->confidence_level * 100, 1) }}%
                                    </span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('attendance.show', $attendance) }}"
                                   class="text-blue-600 hover:text-blue-700">
                                    {{ __('View') }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No attendance records') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('No attendance records have been created today.') }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Auto-refresh script -->
    <script>
        // Auto-refresh every 5 minutes
        setTimeout(function() {
            window.location.reload();
        }, 300000);
    </script>
</x-layouts.app>
