<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
        <x-fas-chevron-right class="h-4 w-4 mx-2 text-gray-400" />
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline">Kelola Akun</a>
        <x-fas-chevron-right class="h-4 w-4 mx-2 text-gray-400" />
        <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:underline">{{ $user->name }}</a>
        <x-fas-chevron-right class="h-4 w-4 mx-2 text-gray-400" />
        <span class="text-gray-500">Edit Akun</span>
    </div>

    <!-- Page Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 not-even:flex items-center">
                <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white text-lg font-medium mr-3">
                    {{ $user->initials() }}
                </div>
                {{ __('Edit User') }}: {{ $user->name }}
            </h1>
            <p class="text-gray-60 mt-1">{{ __('Update user information and settings') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.show', $user) }}"
               class="btn-secondary">
                Kembali
            </a>
        </div>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="card">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <x-forms.input
                        name="name"
                        label="{{ __('Full Name') }}"
                        :value="old('name', $user->name)"
                        required />
                </div>

                <!-- Email -->
                <div>
                    <x-forms.input
                        name="email"
                        type="email"
                        label="{{ __('Email Address') }}"
                        :value="old('email', $user->email)"
                        required />
                </div>

                <!-- Employee ID -->
                <div>
                    <x-forms.input
                        name="employee_id"
                        label="{{ __('Employee ID') }}"
                        :value="old('employee_id', $user->employee_id)"
                        placeholder="{{ __('Optional - will use user ID if not provided') }}" />
                    @if($user->is_face_enrolled)
                    <p class="mt-1 text-sm text-yellow-600 dark:text-yellow-400">
                        {{ __('Warning: Changing Employee ID will require face re-enrollment') }}
                    </p>
                    @endif
                </div>

                <!-- Phone -->
                <div>
                    <x-forms.input
                        name="phone"
                        label="{{ __('Phone Number') }}"
                        :value="old('phone', $user->phone)"
                        placeholder="{{ __('Optional') }}" />
                </div>

                <!-- Role -->
                <div>
                    <x-forms.select
                        name="role"
                        label="{{ __('Role') }}"
                        :options="[
                            'user' => __('User'),
                            'admin' => __('Administrator')
                        ]"
                        :selected="old('role', $user->role)"
                        required />
                    @if($user->role === 'admin' && \App\Models\User::where('role', 'admin')->count() === 1)
                    <p class="mt-1 text-sm text-yellow-600 dark:text-yellow-400">
                        {{ __('Warning: This is the only administrator account') }}
                    </p>
                    @endif
                </div>

                <!-- Password -->
                <div>
                    <x-forms.input
                        name="password"
                        type="password"
                        label="{{ __('New Password') }}"
                        placeholder="{{ __('Leave blank to keep current password') }}" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-forms.input
                        name="password_confirmation"
                        type="password"
                        label="{{ __('Confirm New Password') }}"
                        placeholder="{{ __('Confirm new password if changing') }}" />
                </div>

                <!-- Current Status Info -->
                <div class="bg-gray-50 dark:bg-gray-900/20 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Current Status') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">{{ __('Face Enrollment:') }}</span>
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                {{ $user->is_face_enrolled ? 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-900/20 dark:text-gray-400' }}">
                                {{ $user->is_face_enrolled ? __('Enrolled') : __('Not Enrolled') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">{{ __('Total Attendances:') }}</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ number_format($user->attendances()->count()) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Warning Box for Sensitive Changes -->
                @if($user->is_face_enrolled || $user->attendances()->count() > 0)
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">{{ __('Important Notice') }}</h3>
                            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                <ul class="list-disc list-inside space-y-1">
                                    @if($user->is_face_enrolled)
                                    <li>{{ __('This user has face enrollment data that may be affected by changes') }}</li>
                                    @endif
                                    @if($user->attendances()->count() > 0)
                                    <li>{{ __('This user has attendance records - consider the impact of changes') }}</li>
                                    @endif
                                    <li>{{ __('Changing critical information may require user to re-enroll face recognition') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <x-button
                        tag="a"
                        href="{{ route('admin.users.show', $user) }}"
                        type="secondary">
                        {{ __('Cancel') }}
                    </x-button>
                    <x-button
                        type="primary"
                        buttonType="submit">
                        {{ __('Update User') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
