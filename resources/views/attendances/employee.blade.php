<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('My Attendance') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Check-in and check-out for today') }}</p>
    </div>

    <!-- Today's Status Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ __('Today') }} - {{ now()->format('d M Y') }}</h2>
                <div class="flex items-center space-x-2">
                    @svg('fas-calendar-day', 'w-5 h-5 text-blue-500')
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ now()->format('l') }}</span>
                </div>
            </div>

            @if(!$employee->face_registered)
                <div class="bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-500 p-4 rounded-md mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            @svg('fas-exclamation-triangle', 'h-5 w-5 text-yellow-500')
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                {{ __('Your face is not registered yet. Please contact HR to register your face before doing attendance.') }}
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div id="attendance-section">
                    @if($todayAttendance)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- Check In Status -->
                            <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                                <div class="flex items-center">
                                    @svg('fas-sign-in-alt', 'w-6 h-6 text-green-500 mr-3')
                                    <div>
                                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ __('Check In') }}</p>
                                        <p class="text-lg font-bold text-green-600 dark:text-green-400">
                                            {{ $todayAttendance->check_in ? $todayAttendance->check_in->format('H:i') : '-' }}
                                        </p>
                                        @if($todayAttendance->location)
                                            <p class="text-xs text-green-600 dark:text-green-400">{{ $todayAttendance->location->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Check Out Status -->
                            <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                                <div class="flex items-center">
                                    @svg('fas-sign-out-alt', 'w-6 h-6 text-blue-500 mr-3')
                                    <div>
                                        <p class="text-sm font-medium text-blue-800 dark:text-blue-200">{{ __('Check Out') }}</p>
                                        <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                            {{ $todayAttendance->check_out ? $todayAttendance->check_out->format('H:i') : '-' }}
                                        </p>
                                        @if($todayAttendance->working_hours)
                                            <p class="text-xs text-blue-600 dark:text-blue-400">{{ number_format($todayAttendance->working_hours, 1) }} hours</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($todayAttendance->status === 'present') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($todayAttendance->status === 'late') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                @elseif($todayAttendance->status === 'half_day') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                {{ ucfirst(str_replace('_', ' ', $todayAttendance->status)) }}
                            </span>
                        </div>
                    @endif

                    <!-- Attendance Buttons -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if(!$todayAttendance || !$todayAttendance->check_in)
                            <button id="check-in-btn" class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg flex items-center justify-center">
                                @svg('fas-sign-in-alt', 'w-5 h-5 mr-2')
                                {{ __('Check In') }}
                            </button>
                        @else
                            <button disabled class="bg-gray-400 text-white font-medium py-3 px-6 rounded-lg flex items-center justify-center cursor-not-allowed">
                                @svg('fas-check', 'w-5 h-5 mr-2')
                                {{ __('Checked In') }}
                            </button>
                        @endif

                        @if($todayAttendance && $todayAttendance->check_in && !$todayAttendance->check_out)
                            <button id="check-out-btn" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg flex items-center justify-center">
                                @svg('fas-sign-out-alt', 'w-5 h-5 mr-2')
                                {{ __('Check Out') }}
                            </button>
                        @elseif($todayAttendance && $todayAttendance->check_out)
                            <button disabled class="bg-gray-400 text-white font-medium py-3 px-6 rounded-lg flex items-center justify-center cursor-not-allowed">
                                @svg('fas-check', 'w-5 h-5 mr-2')
                                {{ __('Checked Out') }}
                            </button>
                        @else
                            <button disabled class="bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 font-medium py-3 px-6 rounded-lg flex items-center justify-center cursor-not-allowed">
                                @svg('fas-sign-out-alt', 'w-5 h-5 mr-2')
                                {{ __('Check Out') }}
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Camera Modal -->
    <div id="camera-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100" id="modal-title">{{ __('Take Photo') }}</h3>
                    <button id="close-modal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        @svg('fas-times', 'w-6 h-6')
                    </button>
                </div>

                <div class="text-center">
                    <video id="video" width="320" height="240" autoplay class="mx-auto rounded-lg border"></video>
                    <canvas id="canvas" width="320" height="240" class="hidden"></canvas>

                    <div class="mt-4 space-y-3">
                        <button id="capture-btn" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg w-full">
                            @svg('fas-camera', 'w-5 h-5 mr-2 inline')
                            {{ __('Capture Photo') }}
                        </button>

                        <button id="submit-attendance" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg w-full hidden">
                            @svg('fas-check', 'w-5 h-5 mr-2 inline')
                            {{ __('Submit') }}
                        </button>

                        <button id="retake-btn" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg w-full hidden">
                            @svg('fas-redo', 'w-5 h-5 mr-2 inline')
                            {{ __('Retake') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Attendance History -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Recent Attendance') }}</h2>

            @if($recentAttendances->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Date') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Check In') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Check Out') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Location') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentAttendances as $attendance)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $attendance->date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $attendance->check_in ? $attendance->check_in->format('H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $attendance->check_out ? $attendance->check_out->format('H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $attendance->location->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
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
                <div class="text-center py-8">
                    @svg('fas-calendar-times', 'w-12 h-12 text-gray-400 mx-auto mb-4')
                    <p class="text-gray-500 dark:text-gray-400">{{ __('No attendance records found') }}</p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        let video, canvas, context;
        let currentAttendanceType = '';
        let capturedImageData = '';
        let currentPosition = null;

        // Get available locations for the employee
        const employeeLocations = @json($employee->locations);

        document.addEventListener('DOMContentLoaded', function() {
            const checkInBtn = document.getElementById('check-in-btn');
            const checkOutBtn = document.getElementById('check-out-btn');
            const modal = document.getElementById('camera-modal');
            const closeModal = document.getElementById('close-modal');
            const captureBtn = document.getElementById('capture-btn');
            const submitBtn = document.getElementById('submit-attendance');
            const retakeBtn = document.getElementById('retake-btn');

            video = document.getElementById('video');
            canvas = document.getElementById('canvas');
            context = canvas.getContext('2d');

            // Event listeners
            if (checkInBtn) {
                checkInBtn.addEventListener('click', () => startAttendance('check-in'));
            }

            if (checkOutBtn) {
                checkOutBtn.addEventListener('click', () => startAttendance('check-out'));
            }

            closeModal.addEventListener('click', closeAttendanceModal);
            captureBtn.addEventListener('click', capturePhoto);
            submitBtn.addEventListener('click', submitAttendance);
            retakeBtn.addEventListener('click', retakePhoto);
        });

        function startAttendance(type) {
            currentAttendanceType = type;

            // Get current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        currentPosition = {
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        };

                        // Check if within location radius
                        checkLocationRadius();
                    },
                    (error) => {
                        alert('Unable to get your location. Please enable location services.');
                    }
                );
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }

        function checkLocationRadius() {
            // For now, we'll open the camera modal
            // In production, you might want to validate location first
            openCameraModal();
        }

        function openCameraModal() {
            const modal = document.getElementById('camera-modal');
            const modalTitle = document.getElementById('modal-title');

            modalTitle.textContent = currentAttendanceType === 'check-in' ? 'Check In - Take Photo' : 'Check Out - Take Photo';
            modal.classList.remove('hidden');

            startCamera();
        }

        function closeAttendanceModal() {
            const modal = document.getElementById('camera-modal');
            modal.classList.add('hidden');
            stopCamera();
            resetCameraUI();
        }

        function startCamera() {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then((stream) => {
                    video.srcObject = stream;
                })
                .catch((error) => {
                    console.error('Error accessing camera:', error);
                    alert('Unable to access camera. Please check permissions.');
                });
        }

        function stopCamera() {
            if (video.srcObject) {
                video.srcObject.getTracks().forEach(track => track.stop());
            }
        }

        function capturePhoto() {
            context.drawImage(video, 0, 0, 320, 240);
            capturedImageData = canvas.toDataURL('image/jpeg', 0.8);

            // Show captured image instead of video
            video.style.display = 'none';
            canvas.style.display = 'block';

            // Update UI
            document.getElementById('capture-btn').classList.add('hidden');
            document.getElementById('submit-attendance').classList.remove('hidden');
            document.getElementById('retake-btn').classList.remove('hidden');
        }

        function retakePhoto() {
            video.style.display = 'block';
            canvas.style.display = 'none';

            document.getElementById('capture-btn').classList.remove('hidden');
            document.getElementById('submit-attendance').classList.add('hidden');
            document.getElementById('retake-btn').classList.add('hidden');

            capturedImageData = '';
        }

        function resetCameraUI() {
            video.style.display = 'block';
            canvas.style.display = 'none';

            document.getElementById('capture-btn').classList.remove('hidden');
            document.getElementById('submit-attendance').classList.add('hidden');
            document.getElementById('retake-btn').classList.add('hidden');

            capturedImageData = '';
        }

        function submitAttendance() {
            if (!capturedImageData) {
                alert('Please capture a photo first.');
                return;
            }

            if (!currentPosition) {
                alert('Location not available. Please try again.');
                return;
            }

            const submitButton = document.getElementById('submit-attendance');
            submitButton.disabled = true;
            submitButton.textContent = 'Processing...';

            // Find primary location or first available location
            const primaryLocation = employeeLocations.find(loc => loc.pivot.is_primary) || employeeLocations[0];

            if (!primaryLocation) {
                alert('No assigned location found. Please contact HR.');
                return;
            }

            const endpoint = currentAttendanceType === 'check-in' ?
                '{{ route("attendance.check-in") }}' :
                '{{ route("attendance.check-out") }}';

            const requestData = {
                latitude: currentPosition.latitude,
                longitude: currentPosition.longitude,
                face_image: capturedImageData.split(',')[1], // Remove data:image/jpeg;base64, prefix
                _token: '{{ csrf_token() }}'
            };

            if (currentAttendanceType === 'check-in') {
                requestData.location_id = primaryLocation.id;
            }

            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closeAttendanceModal();
                    location.reload(); // Refresh page to show updated status
                } else {
                    alert(data.message || 'Attendance failed. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error. Please check your connection and try again.');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = 'Submit';
            });
        }
    </script>
    @endpush
</x-layouts.app>
