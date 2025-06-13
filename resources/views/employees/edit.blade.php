<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        @svg('fas-chevron-right', 'h-4 w-4 mx-2 text-gray-400')
        <a href="{{ route('employees.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Employees') }}</a>
        @svg('fas-chevron-right', 'h-4 w-4 mx-2 text-gray-400')
        <span class="text-gray-500 dark:text-gray-400">{{ __('Edit Employee') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Employee') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Update employee information') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <form action="{{ route('employees.update', $employee) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

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
                                value="{{ old('employee_id', $employee->employee_id) }}"
                                required />

                            <x-forms.input
                                label="Full Name"
                                name="name"
                                type="text"
                                value="{{ old('name', $employee->name) }}"
                                required />

                            <x-forms.input
                                label="Email"
                                name="email"
                                type="email"
                                value="{{ old('email', $employee->email) }}"
                                required />

                            <x-forms.input
                                label="Phone Number"
                                name="phone"
                                type="text"
                                value="{{ old('phone', $employee->phone) }}" />
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Job Information') }}</h3>

                        <div class="space-y-4">
                            <x-forms.input
                                label="Department"
                                name="department"
                                type="text"
                                value="{{ old('department', $employee->department) }}" />

                            <x-forms.input
                                label="Position"
                                name="position"
                                type="text"
                                value="{{ old('position', $employee->position) }}" />

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Status') }}
                                </label>
                                <div class="flex items-center">
                                    <input type="hidden" name="is_active" value="0">
                                    <input
                                        type="checkbox"
                                        id="is_active"
                                        name="is_active"
                                        value="1"
                                        {{ old('is_active', $employee->is_active) ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                        {{ __('Active Employee') }}
                                    </label>
                                </div>
                            </div>
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
                                        @php
                                            $isAssigned = $employee->locations->contains($location->id);
                                            $oldLocations = old('locations', $employee->locations->pluck('id')->toArray());
                                        @endphp
                                        <div class="flex items-center">
                                            <input
                                                type="checkbox"
                                                id="location_{{ $location->id }}"
                                                name="locations[]"
                                                value="{{ $location->id }}"
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                {{ in_array($location->id, $oldLocations) ? 'checked' : '' }}>
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
                                    @php
                                        $primaryLocation = $employee->locations->where('pivot.is_primary', true)->first();
                                        $oldPrimary = old('primary_location', $primaryLocation?->id);
                                    @endphp
                                    <select
                                        name="primary_location"
                                        id="primary_location"
                                        class="w-full px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">{{ __('Select primary location') }}</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ $oldPrimary == $location->id ? 'selected' : '' }}>
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
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Face Registration') }}</h3>

                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            @if($employee->face_registered)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        @svg('fas-check-circle', 'w-5 h-5 text-green-500 mr-2')
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Face is registered') }}</span>
                                    </div>
                                    <form action="{{ route('employees.delete-face', $employee) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete face registration?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-sm">
                                            {{ __('Delete Face Registration') }}
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        @svg('fas-times-circle', 'w-5 h-5 text-red-500 mr-2')
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Face not registered') }}</span>
                                    </div>
                                    <button type="button"
                                            onclick="openFaceRegistration({{ $employee->id }}, '{{ $employee->name }}')"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                                        {{ __('Register Face') }}
                                    </button>
                                </div>
                            @endif
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
                    {{ __('Update Employee') }}
                </x-button>
            </div>
        </form>
    </div>

    <!-- Face Registration Modal -->
    <div id="face-registration-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100" id="face-modal-title">{{ __('Register Face') }}</h3>
                    <button id="close-face-modal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        @svg('fas-times', 'w-6 h-6')
                    </button>
                </div>

                <div class="text-center">
                    <video id="face-video" width="320" height="240" autoplay class="mx-auto rounded-lg border"></video>
                    <canvas id="face-canvas" width="320" height="240" class="hidden"></canvas>

                    <div class="mt-4 space-y-3">
                        <button id="face-capture-btn" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg w-full">
                            @svg('fas-camera', 'w-5 h-5 mr-2 inline')
                            {{ __('Capture Photo') }}
                        </button>

                        <button id="face-submit-btn" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg w-full hidden">
                            @svg('fas-check', 'w-5 h-5 mr-2 inline')
                            {{ __('Register Face') }}
                        </button>

                        <button id="face-retake-btn" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg w-full hidden">
                            @svg('fas-redo', 'w-5 h-5 mr-2 inline')
                            {{ __('Retake') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let faceVideo, faceCanvas, faceContext;
        let currentEmployeeId = null;
        let capturedFaceData = '';

        document.addEventListener('DOMContentLoaded', function() {
            const locationCheckboxes = document.querySelectorAll('input[name="locations[]"]');
            const primaryLocationSelect = document.getElementById('primary_location');

            faceVideo = document.getElementById('face-video');
            faceCanvas = document.getElementById('face-canvas');
            faceContext = faceCanvas.getContext('2d');

            function updatePrimaryLocationOptions() {
                const selectedLocations = Array.from(locationCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                Array.from(primaryLocationSelect.options).forEach(option => {
                    if (option.value) {
                        option.style.display = selectedLocations.includes(option.value) ? 'block' : 'none';
                        if (!selectedLocations.includes(option.value) && option.selected) {
                            option.selected = false;
                        }
                    }
                });

                if (selectedLocations.length === 1) {
                    primaryLocationSelect.value = selectedLocations[0];
                }
            }

            locationCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updatePrimaryLocationOptions);
            });

            updatePrimaryLocationOptions();

            // Face registration modal events
            document.getElementById('close-face-modal').addEventListener('click', closeFaceModal);
            document.getElementById('face-capture-btn').addEventListener('click', captureFacePhoto);
            document.getElementById('face-submit-btn').addEventListener('click', submitFaceRegistration);
            document.getElementById('face-retake-btn').addEventListener('click', retakeFacePhoto);
        });

        function openFaceRegistration(employeeId, employeeName) {
            currentEmployeeId = employeeId;
            document.getElementById('face-modal-title').textContent = `Register Face - ${employeeName}`;
            document.getElementById('face-registration-modal').classList.remove('hidden');
            startFaceCamera();
        }

        function closeFaceModal() {
            document.getElementById('face-registration-modal').classList.add('hidden');
            stopFaceCamera();
            resetFaceUI();
        }

        function startFaceCamera() {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then((stream) => {
                    faceVideo.srcObject = stream;
                })
                .catch((error) => {
                    console.error('Error accessing camera:', error);
                    alert('Unable to access camera. Please check permissions.');
                });
        }

        function stopFaceCamera() {
            if (faceVideo.srcObject) {
                faceVideo.srcObject.getTracks().forEach(track => track.stop());
            }
        }

        function captureFacePhoto() {
            faceContext.drawImage(faceVideo, 0, 0, 320, 240);
            capturedFaceData = faceCanvas.toDataURL('image/jpeg', 0.8);

            faceVideo.style.display = 'none';
            faceCanvas.style.display = 'block';

            document.getElementById('face-capture-btn').classList.add('hidden');
            document.getElementById('face-submit-btn').classList.remove('hidden');
            document.getElementById('face-retake-btn').classList.remove('hidden');
        }

        function retakeFacePhoto() {
            faceVideo.style.display = 'block';
            faceCanvas.style.display = 'none';

            document.getElementById('face-capture-btn').classList.remove('hidden');
            document.getElementById('face-submit-btn').classList.add('hidden');
            document.getElementById('face-retake-btn').classList.add('hidden');

            capturedFaceData = '';
        }

        function resetFaceUI() {
            faceVideo.style.display = 'block';
            faceCanvas.style.display = 'none';

            document.getElementById('face-capture-btn').classList.remove('hidden');
            document.getElementById('face-submit-btn').classList.add('hidden');
            document.getElementById('face-retake-btn').classList.add('hidden');

            capturedFaceData = '';
            currentEmployeeId = null;
        }

        function submitFaceRegistration() {
            if (!capturedFaceData || !currentEmployeeId) {
                alert('Please capture a photo first.');
                return;
            }

            const submitButton = document.getElementById('face-submit-btn');
            submitButton.disabled = true;
            submitButton.textContent = 'Processing...';

            fetch(`/employees/${currentEmployeeId}/register-face`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    face_image: capturedFaceData.split(',')[1]
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Face registered successfully!');
                    closeFaceModal();
                    location.reload();
                } else {
                    alert(data.message || 'Face registration failed. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error. Please check your connection and try again.');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = 'Register Face';
            });
        }
    </script>
    @endpush
</x-layouts.app>
