<x-layouts.app>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Employees') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Manage employee data and face registration') }}</p>
        </div>
        <a href="{{ route('employees.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
            @svg('fas-plus', 'w-5 h-5 mr-2')
            {{ __('Add Employee') }}
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Employees') }}</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $employees->total() }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                    @svg('fas-users', 'h-6 w-6 text-blue-500 dark:text-blue-300')
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Face Registered') }}</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $employees->where('face_registered', true)->count() }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                    @svg('fas-user-check', 'h-6 w-6 text-green-500 dark:text-green-300')
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Present Today') }}</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $employees->whereHas('attendances', function($q) { $q->whereDate('date', today())->whereNotNull('check_in'); })->count() }}</p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                    @svg('fas-calendar-check', 'h-6 w-6 text-purple-500 dark:text-purple-300')
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Active Employees') }}</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $employees->where('is_active', true)->count() }}</p>
                </div>
                <div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-full">
                    @svg('fas-user-check', 'h-6 w-6 text-orange-500 dark:text-orange-300')
                </div>
            </div>
        </div>
    </div>

    <!-- Employees Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ __('Employee List') }}</h2>

                <!-- Search and Filter -->
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <input type="text"
                               placeholder="{{ __('Search employees...') }}"
                               class="w-64 px-4 py-2 pl-10 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @svg('fas-search', 'absolute left-3 top-3 h-4 w-4 text-gray-400')
                    </div>
                </div>
            </div>

            @if($employees->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Employee') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Contact') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Department') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Face Status') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Today Attendance') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Status') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($employees as $employee)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        {{ $employee->initials }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $employee->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $employee->employee_id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $employee->email }}</div>
                                        @if($employee->phone)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $employee->phone }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $employee->department ?: '-' }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $employee->position ?: '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($employee->face_registered)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                @svg('fas-check-circle', 'w-3 h-3 mr-1')
                                                {{ __('Registered') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                @svg('fas-times-circle', 'w-3 h-3 mr-1')
                                                {{ __('Not Registered') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $todayAttendance = $employee->attendances->where('date', today()->toDateString())->first();
                                        @endphp

                                        @if($todayAttendance)
                                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                                @if($todayAttendance->check_in)
                                                    @svg('fas-sign-in-alt', 'w-3 h-3 text-green-500 mr-1 inline')
                                                    {{ $todayAttendance->check_in->format('H:i') }}
                                                @endif

                                                @if($todayAttendance->check_out)
                                                    @svg('fas-sign-out-alt', 'w-3 h-3 text-blue-500 mr-1 ml-2 inline')
                                                    {{ $todayAttendance->check_out->format('H:i') }}
                                                @endif
                                            </div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                @if($todayAttendance->status === 'present') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($todayAttendance->status === 'late') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                                @elseif($todayAttendance->status === 'half_day') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $todayAttendance->status)) }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('No attendance') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($employee->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                {{ __('Active') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                {{ __('Inactive') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('employees.show', $employee) }}"
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                @svg('fas-eye', 'w-4 h-4')
                                            </a>
                                            <a href="{{ route('employees.edit', $employee) }}"
                                               class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                @svg('fas-edit', 'w-4 h-4')
                                            </a>

                                            @if(!$employee->face_registered)
                                                <button onclick="openFaceRegistration({{ $employee->id }}, '{{ $employee->name }}')"
                                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                    @svg('fas-camera', 'w-4 h-4')
                                                </button>
                                            @else
                                                <form action="{{ route('employees.delete-face', $employee) }}"
                                                      method="POST"
                                                      class="inline"
                                                      onsubmit="return confirm('Are you sure you want to delete face registration?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-orange-600 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300">
                                                        @svg('fas-user-times', 'w-4 h-4')
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('employees.destroy', $employee) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    @svg('fas-trash', 'w-4 h-4')
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $employees->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    @svg('fas-users', 'w-12 h-12 text-gray-400 mx-auto mb-4')
                    <p class="text-gray-500 dark:text-gray-400 mb-4">{{ __('No employees found') }}</p>
                    <a href="{{ route('employees.create') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center">
                        @svg('fas-plus', 'w-5 h-5 mr-2')
                        {{ __('Add First Employee') }}
                    </a>
                </div>
            @endif
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
