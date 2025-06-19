<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
        <x-fas-chevron-right class="h-4 w-4 mx-2 text-gray-400" />
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline">Kelola Akun</a>
        <x-fas-chevron-right class="h-4 w-4 mx-2 text-gray-400" />
        <span class="text-gray-500">Buat Akun</span>
    </div>

    <!-- Page Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ __('Create New User') }}</h1>
            <p class="text-gray-600 mt-1">{{ __('Add a new user to the system') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                {{ __('Back to Users') }}
            </a>
        </div>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="card">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <x-forms.input
                        name="name"
                        label="{{ __('Full Name') }}"
                        :value="old('name')"
                        required />
                </div>

                <!-- Username -->
                <div>
                    <x-forms.input
                        name="username"
                        label="{{ __('Username') }}"
                        :value="old('username')"
                        required />
                </div>

                <!-- Email -->
                <div>
                    <x-forms.input
                        name="email"
                        type="email"
                        label="{{ __('Email Address') }}"
                        :value="old('email')"
                        required />
                </div>

                <!-- Employee ID -->
                <div>
                    <x-forms.input
                        name="employee_id"
                        label="{{ __('Employee ID') }}"
                        :value="old('employee_id')"
                        placeholder="{{ __('Optional - will use user ID if not provided') }}" />
                </div>

                <!-- Phone -->
                <div>
                    <x-forms.input
                        name="phone"
                        label="{{ __('Phone Number') }}"
                        :value="old('phone')"
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
                        :selected="old('role')"
                        placeholder="{{ __('Select a role') }}"
                        required />
                </div>

                <!-- Location Assignment (only for users) -->
                <div x-data="{ role: '{{ old('role') }}' }">
                    <div x-show="role === 'user'" x-transition>
                        <x-forms.select
                            name="location_id"
                            label="{{ __('Assigned Location') }}"
                            :options="collect($locations ?? [])->pluck('name', 'id')->prepend(__('No location assigned'), '')"
                            :selected="old('location_id')"
                            placeholder="{{ __('Select a location') }}" />
                        <p class="text-xs text-gray-500 mt-1">
                            {{ __('Only users with assigned locations can perform attendance check-in') }}
                        </p>
                    </div>
                    
                    <!-- Check-in and Check-out Time (only for users) -->
                    <div x-show="role === 'user'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <x-forms.input
                                name="check_in_time"
                                type="time"
                                label="{{ __('Check-in Time') }}"
                                :value="old('check_in_time')"
                                placeholder="{{ __('Optional - e.g., 08:00') }}" />
                            <p class="text-xs text-gray-500 mt-1">
                                {{ __('Allowed check-in time for this user') }}
                            </p>
                        </div>
                        <div>
                            <x-forms.input
                                name="check_out_time"
                                type="time"
                                label="{{ __('Check-out Time') }}"
                                :value="old('check_out_time')"
                                placeholder="{{ __('Optional - e.g., 17:00') }}" />
                            <p class="text-xs text-gray-500 mt-1">
                                {{ __('Allowed check-out time for this user') }}
                            </p>
                        </div>
                    </div>
                    
                    <script>
                        document.querySelector('select[name="role"]').addEventListener('change', function() {
                            Alpine.store('role', this.value);
                        });
                    </script>
                </div>

                <!-- Password -->
                <div>
                    <x-forms.input
                        name="password"
                        type="password"
                        label="{{ __('Password') }}"
                        required />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-forms.input
                        name="password_confirmation"
                        type="password"
                        label="{{ __('Confirm Password') }}"
                        required />
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50/20 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">{{ __('User Setup Information') }}</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>{{ __('Users will need to enroll their face after first login') }}</li>
                                    <li>{{ __('Employee ID is used for face recognition - if not provided, user ID will be used') }}</li>
                                    <li>{{ __('Administrators have full access to manage users and locations') }}</li>
                                    <li>{{ __('Regular users can only manage their own attendance and profile') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <x-button
                        tag="a"
                        href="{{ route('admin.users.index') }}"
                        type="secondary">
                        {{ __('Cancel') }}
                    </x-button>
                    <x-button
                        type="primary"
                        buttonType="submit">
                        {{ __('Create User') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
