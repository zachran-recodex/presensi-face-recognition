<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
        <x-fas-chevron-right class="h-4 w-4 mx-2 text-gray-400" />
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline">Kelola Akun</a>
        <x-fas-chevron-right class="h-4 w-4 mx-2 text-gray-400" />
        <span class="text-gray-500">{{ $user->name }}</span>
    </div>

    <!-- Page Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white text-lg font-medium mr-3">
                    {{ $user->initials() }}
                </div>
                {{ $user->name }}
            </h1>
            <p class="text-gray-600 mt-1">{{ __('User Details and Activity') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary">
                {{ __('Edit User') }}
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                {{ __('Back to Users') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Information -->
        <div class="lg:col-span-2">
            <div class="card">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('User Information') }}</h2>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Full Name') }}</label>
                            <p class="mt-1 text-gray-900">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Email Address') }}</label>
                            <p class="mt-1 text-gray-900">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Employee ID') }}</label>
                            <p class="mt-1 text-gray-900">
                                {{ $user->employee_id ?? __('Not set') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Phone Number') }}</label>
                            <p class="mt-1 text-gray-900">
                                {{ $user->phone ?? __('Not provided') }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Role') }}</label>
                            <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                {{ $user->role === 'admin' ? __('Administrator') : __('User') }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Face Enrollment') }}</label>
                            <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $user->is_face_enrolled ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800/20' }}">
                                {{ $user->is_face_enrolled ? __('Enrolled') : __('Not Enrolled') }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Member Since') }}</label>
                            <p class="mt-1 text-gray-900">{{ $user->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Last Updated') }}</label>
                            <p class="mt-1 text-gray-900">{{ $user->updated_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>

                    @if($user->email_verified_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Email Verified') }}</label>
                        <p class="mt-1 text-gray-900">{{ $user->email_verified_at->format('M j, Y g:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Stats and Actions -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Quick Actions') }}</h3>

                <div class="space-y-3">
                    <a href="{{ route('admin.users.edit', $user) }}" class="w-full btn-primary text-center block">
                        {{ __('Edit User Details') }}
                    </a>

                    @if($user->is_face_enrolled)
                    <form action="{{ route('admin.users.reset-face', $user) }}" method="POST"
                            onsubmit="return confirm('{{ __('Are you sure you want to reset this user\'s face enrollment? They will need to enroll again.') }}')">
                        @csrf
                        <x-button type="warning" class="w-full">
                            {{ __('Reset Face Enrollment') }}
                        </x-button>
                    </form>
                    @endif

                    <a href="{{ route('dashboard', ['user_id' => $user->id]) }}" class="w-full btn-success text-center block">
                        {{ __('View Attendance History') }}
                    </a>

                    @if($user->id !== auth()->id() && $user->attendances()->count() === 0)
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                            onsubmit="return confirm('{{ __('Are you sure you want to delete this user? This action cannot be undone.') }}')">
                        @csrf
                        @method('DELETE')
                        <x-button type="danger" class="w-full">
                            {{ __('Delete User') }}
                        </x-button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Attendance Statistics -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Attendance Statistics') }}</h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ __('Total Attendances') }}</span>
                        <span class="font-semibold text-gray-900">
                            {{ number_format($stats['total_attendances']) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ __('This Month') }}</span>
                        <span class="font-semibold text-gray-900">
                            {{ number_format($stats['this_month_attendances']) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ __('Check-ins') }}</span>
                        <span class="font-semibold text-gray-900">
                            {{ number_format($stats['check_ins']) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ __('Check-outs') }}</span>
                        <span class="font-semibold text-gray-900">
                            {{ number_format($stats['check_outs']) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Location Assignment Display -->
    @if($user->role === 'user')
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">{{ __('Assigned Location') }}</h2>
                    <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-700 text-sm">
                        {{ __('Edit Location Assignment') }}
                    </a>
                </div>

                @if($user->assignedLocation)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">{{ $user->assignedLocation->name }}</div>
                            <div class="text-sm text-gray-500">{{ $user->assignedLocation->address }}</div>
                            <div class="text-xs text-gray-400 mt-1">
                                Radius: {{ $user->assignedLocation->radius }}m
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No location assigned') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ __('This employee has not been assigned an attendance location yet.') }}
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary">
                                {{ __('Assign Location') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Attendances -->
    @if($user->attendances->count() > 0)
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">{{ __('Recent Attendances') }}</h2>
                    <a href="{{ route('dashboard', ['user_id' => $user->id]) }}"
                       class="text-blue-600 hover:underline text-sm">
                        {{ __('View All') }}
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
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
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($user->attendances as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $attendance->type === 'check_in' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $attendance->type === 'check_in' ? __('Check In') : __('Check Out') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $attendance->location->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $attendance->location->address }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance->attendance_time->format('M j, Y g:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $attendance->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $attendance->is_verified ? __('Verified') : __('Pending') }}
                                    </span>
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
            </div>
        </div>
    </div>
    @else
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 text-center">
                <div class="text-gray-500">
                    <x-fas-clipboard class="mx-auto h-12 w-12 mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No Attendance Records') }}</h3>
                    <p class="text-gray-600">{{ __('This user has not recorded any attendance yet.') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</x-layouts.app>
