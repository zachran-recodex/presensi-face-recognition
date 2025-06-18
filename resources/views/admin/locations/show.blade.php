<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">{{ __('Dashboard') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('admin.locations.index') }}" class="text-blue-600 hover:underline">{{ __('Locations') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500">{{ $location->name }}</span>
    </div>

    <!-- Page Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $location->name }}</h1>
            <p class="text-gray-600 mt-1">{{ __('Location Details and Recent Activity') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.locations.edit', $location) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                {{ __('Edit Location') }}
            </a>
            <a href="{{ route('admin.locations.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
                {{ __('Back to Locations') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Location Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Location Information') }}</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                            <p class="mt-1 text-gray-900">{{ $location->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Address') }}</label>
                            <p class="mt-1 text-gray-900">{{ $location->address }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('Latitude') }}</label>
                                <p class="mt-1 text-gray-900">
                                    {{ $location->latitude ?? __('Not set') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('Longitude') }}</label>
                                <p class="mt-1 text-gray-900">
                                    {{ $location->longitude ?? __('Not set') }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Radius') }}</label>
                            <p class="mt-1 text-gray-900">{{ $location->radius }} {{ __('meters') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                            <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $location->is_active ? 'bg-green-100 text-green-800/20' : 'bg-red-100 text-red-800' }}">
                                {{ $location->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('Created') }}</label>
                                <p class="mt-1 text-gray-900">{{ $location->created_at->format('M j, Y g:i A') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('Last Updated') }}</label>
                                <p class="mt-1 text-gray-900">{{ $location->updated_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="space-y-6">
            <!-- Location Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Quick Actions') }}</h3>

                    <div class="space-y-3">
                        <form action="{{ route('admin.locations.toggle-status', $location) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit"
                                    class="w-full {{ $location->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-lg font-medium">
                                {{ $location->is_active ? __('Deactivate Location') : __('Activate Location') }}
                            </button>
                        </form>

                        @if($location->attendances()->count() === 0)
                        <form action="{{ route('admin.locations.destroy', $location) }}" method="POST"
                              onsubmit="return confirm('{{ __('Are you sure you want to delete this location?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                                {{ __('Delete Location') }}
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Attendance Stats -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Attendance Statistics') }}</h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ __('Total Attendances') }}</span>
                            <span class="font-semibold text-gray-900">
                                {{ number_format($location->attendances()->count()) }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ __('This Month') }}</span>
                            <span class="font-semibold text-gray-900">
                                {{ number_format($location->attendances()->whereMonth('created_at', now()->month)->count()) }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ __('Today') }}</span>
                            <span class="font-semibold text-gray-900">
                                {{ number_format($location->attendances()->whereDate('created_at', today())->count()) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Attendances -->
    @if($location->attendances->count() > 0)
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">{{ __('Recent Attendances') }}</h2>
                    <a href="{{ route('admin.attendance.history', ['location_id' => $location->id]) }}"
                       class="text-blue-600 hover:underline text-sm">
                        {{ __('View All') }}
                    </a>
                </div>

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
                                    {{ __('Time') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Status') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($location->attendances as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-medium">
                                            {{ $attendance->user->initials() }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $attendance->user->name }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $attendance->type === 'check_in' ? 'bg-green-100 text-green-800/20' : 'bg-blue-100 text-blue-800/20' }}">
                                        {{ $attendance->type === 'check_in' ? __('Check In') : __('Check Out') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance->attendance_time->format('M j, Y g:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $attendance->is_verified ? 'bg-green-100 text-green-800/20' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $attendance->is_verified ? __('Verified') : __('Pending') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 text-center">
                <div class="text-gray-500">
                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No Attendances Yet') }}</h3>
                    <p class="text-gray-600">{{ __('No attendance records have been created for this location.') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</x-layouts.app>
