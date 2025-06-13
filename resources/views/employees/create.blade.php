<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        @svg('fas-chevron-right', 'h-4 w-4 mx-2 text-gray-400')
        <a href="{{ route('employees.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Employees') }}</a>
        @svg('fas-chevron-right', 'h-4 w-4 mx-2 text-gray-400')
        <span class="text-gray-500 dark:text-gray-400">{{ __('Add Employee') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Add New Employee') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Create a new employee record') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <form action="{{ route('employees.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Basic Information') }}</h3>

                        <div class="space-y-4">
                            <x-forms.input
                                label="Employee ID"
                                name="employee_id"
                                type="text"
                                placeholder="e.g., EMP001"
                                value="{{ old('employee_id') }}"
                                required />

                            <x-forms.input
                                label="Full Name"
                                name="name"
                                type="text"
                                placeholder="John Doe"
                                value="{{ old('name') }}"
                                required />

                            <x-forms.input
                                label="Email"
                                name="email"
                                type="email"
                                placeholder="john@company.com"
                                value="{{ old('email') }}"
                                required />

                            <x-forms.input
                                label="Phone Number"
                                name="phone"
                                type="text"
                                placeholder="+62 812-3456-7890"
                                value="{{ old('phone') }}" />
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Job Information') }}</h3>

                        <div class="space-y-4">
                            <x-forms.input
                                label="Department"
                                name="department"
                                type="text"
                                placeholder="e.g., IT, HR, Finance"
                                value="{{ old('department') }}" />

                            <x-forms.input
                                label="Position"
                                name="position"
                                type="text"
                                placeholder="e.g., Software Developer"
                                value="{{ old('position') }}" />
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Location Assignment') }}</h3>

                        @if($locations->count() > 0)
                            <div class="space-y-3">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Assigned Locations') }} <span class="text-red-500">*</span>
                                </label>

                                <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-lg p-3">
                                    @foreach($locations as $location)
                                        <div class="flex items-center">
                                            <input
                                                type="checkbox"
                                                id="location_{{ $location->id }}"
                                                name="locations[]"
                                                value="{{ $location->id }}"
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                {{ in_array($location->id, old('locations', [])) ? 'checked' : '' }}>
                                            <label for="location_{{ $location->id }}" class="ml-3 block text-sm text-gray-700 dark:text-gray-300">
                                                <span class="font-medium">{{ $location->name }}</span>
                                                <br>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $location->address }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                @error('locations')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror

                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('Primary Location') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        name="primary_location"
                                        id="primary_location"
                                        class="w-full px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">{{ __('Select primary location') }}</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ old('primary_location') == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('primary_location')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <div class="bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-500 p-4 rounded-md">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        @svg('fas-exclamation-triangle', 'h-5 w-5 text-yellow-500')
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                            {{ __('No locations available. Please create locations first.') }}
                                        </p>
                                        <p class="mt-2">
                                            <a href="{{ route('locations.create') }}"
                                               class="text-yellow-700 dark:text-yellow-200 underline hover:text-yellow-600">
                                                {{ __('Create Location') }}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Note') }}</h3>
                        <div class="bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 p-4 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    @svg('fas-info-circle', 'h-5 w-5 text-blue-500')
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700 dark:text-blue-200">
                                        {{ __('Face registration can be done after the employee is created. The employee will need to register their face before they can use the attendance system.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('employees.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-lg">
                    {{ __('Cancel') }}
                </a>
                <x-button type="primary">
                    @svg('fas-save', 'w-5 h-5 mr-2')
                    {{ __('Create Employee') }}
                </x-button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const locationCheckboxes = document.querySelectorAll('input[name="locations[]"]');
            const primaryLocationSelect = document.getElementById('primary_location');

            function updatePrimaryLocationOptions() {
                const selectedLocations = Array.from(locationCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                // Clear and rebuild primary location options
                Array.from(primaryLocationSelect.options).forEach(option => {
                    if (option.value) {
                        option.style.display = selectedLocations.includes(option.value) ? 'block' : 'none';
                        if (!selectedLocations.includes(option.value) && option.selected) {
                            option.selected = false;
                        }
                    }
                });

                // Auto-select if only one location is selected
                if (selectedLocations.length === 1) {
                    primaryLocationSelect.value = selectedLocations[0];
                }
            }

            locationCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updatePrimaryLocationOptions);
            });

            // Initial call
            updatePrimaryLocationOptions();
        });
    </script>
    @endpush
</x-layouts.app>
