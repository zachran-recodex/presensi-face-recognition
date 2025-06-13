<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        @svg('fas-chevron-right', 'h-4 w-4 mx-2 text-gray-400')
        <a href="{{ route('employees.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Employees') }}</a>
        @svg('fas-chevron-right', 'h-4 w-4 mx-2 text-gray-400')
        <span class="text-gray-500 dark:text-gray-400">{{ $employee->name }}</span>
    </div>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $employee->name }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Employee Details') }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('employees.edit', $employee) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                @svg('fas-edit', 'w-5 h-5 mr-2')
                {{ __('Edit') }}
            </a>
            @if(!$employee->face_registered)
                <button onclick="openFaceRegistration({{ $employee->id }}, '{{ $employee->name }}')"
                        class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                    @svg('fas-camera', 'w-5 h-5 mr-2')
                    {{ __('Register Face') }}
                </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Employee Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Basic Information') }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Employee ID') }}</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $employee->employee_id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email') }}</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $employee->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Phone') }}</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $employee->phone ?: '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Department') }}</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $employee->department ?: '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Position') }}</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $employee->position ?: '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</label>
                            <div class="mt-1">
                                @if($employee->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        @svg('fas-check-circle', 'w-3 h-3 mr-1')
                                        {{ __('Active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                        @svg('fas-times-circle', 'w-3 h-3 mr-1')
                                        {{ __('Inactive') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assigned Locations -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Assigned Locations') }}</h3>

                    @if($employee->locations->count() > 0)
                        <div class="space-y-3">
                            @foreach($employee->locations as $location)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center">
                                        @svg('fas-map-marker-alt', 'w-5 h-5 text-blue-500 mr-3')
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $location->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $location->address }}</p>
                                        </div>
                                    </div>
                                    @if($location->pivot->is_primary)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            @svg('fas-star', 'w-3 h-3 mr-1')
                                            {{ __('Primary') }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            @svg('fas-map-marker-alt', 'w-8 h-8 text-gray-400 mx-auto mb-2')
                            <p class="text-gray-500 dark:text-gray-400">{{ __('No locations assigned') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Attendance -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Recent Attendance') }}</h3>

                    @if($employee->attendances->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Date') }}</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Check In') }}</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Check Out') }}</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Location') }}</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($employee->attendances as $attendance)
                                        <tr>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $attendance->date->format('d M Y') }}
                                            </td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $attendance->check_in ? $attendance->check_in->format('H:i') : '-' }}
                                            </td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $attendance->check_out ? $attendance->check_out->format('H:i') : '-' }}
                                            </td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $attendance->location->name }}
                                            </td>
                                            <td class="px-3 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                    @if($attendance->status === 'present') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($attendance->status === 'late') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                                    @elseif($attendance->status === 'half_day') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            @svg('fas-calendar-times', 'w-8 h-8 text-gray-400 mx-auto mb-2')
                            <p class="text-gray-500 dark:text-gray-400">{{ __('No attendance records') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Face Registration Status -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Face Registration') }}</h3>

                    @if($employee->face_registered)
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 mb-3">
                                @svg('fas-check-circle', 'h-6 w-6 text-green-600 dark:text-green-400')
                            </div>
                            <p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ __('Face Registered') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Employee can use attendance system') }}</p>

                            <form action="{{ route('employees.delete-face', $employee) }}"
                                  method="POST"
                                  class="mt-4"
                                  onsubmit="return confirm('Are you sure you want to delete face registration?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-sm">
                                    {{ __('Delete Registration') }}
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-3">
                                @svg('fas-times-circle', 'h-6 w-6 text-red-600 dark:text-red-400')
                            </div>
                            <p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ __('Face Not Registered') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Cannot use attendance system') }}</p>

                            <button onclick="openFaceRegistration({{ $employee->id }}, '{{ $employee->name }}')"
                                    class="mt-4 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg">
                                {{ __('Register Face') }}
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('This Month') }}</h3>

                    @php
                        $monthlyAttendances = $employee->monthlyAttendances()->get();
                        $presentDays = $monthlyAttendances->where('status', 'present')->count();
                        $lateDays = $monthlyAttendances->where('status', 'late')->count();
                        $totalDays = $monthlyAttendances->count();
                    @endphp

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Present Days') }}</span>
                            <span class="text-sm font-medium text-green-600 dark:text-green-400">{{ $presentDays }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Late Days') }}</span>
                            <span class="text-sm font-medium text-orange-600 dark:text-orange-400">{{ $lateDays }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Days') }}</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $totalDays }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
            faceVideo = document.getElementById('face-video');
            faceCanvas = document.getElementById('face-canvas');
            faceContext = faceCanvas.getContext('2d');

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
