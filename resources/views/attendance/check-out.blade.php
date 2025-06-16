<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('attendance.index') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Attendance') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Check Out') }}</span>
    </div>

    <!-- Page Title -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Check Out') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            {{ __('Complete your check-out with face recognition') }}
        </p>
    </div>

    <div class="max-w-2xl mx-auto">
        <!-- Check-in Info -->
        <div class="mb-6">
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-2">{{ __('Today\'s Check-in Information') }}</h3>
                <div class="space-y-2 text-sm text-blue-700 dark:text-blue-300">
                    <p><strong>{{ __('Check-in Time') }}:</strong> {{ $checkInRecord->attendance_time->format('H:i:s') }}</p>
                    <p><strong>{{ __('Location') }}:</strong> {{ $checkInRecord->location->name ?? 'N/A' }}</p>
                    <p><strong>{{ __('Status') }}:</strong>
                        @if($checkInRecord->is_verified)
                            <span class="text-green-600">✓ {{ __('Verified') }}</span>
                        @else
                            <span class="text-red-600">✗ {{ __('Not Verified') }}</span>
                        @endif
                    </p>
                    @if($checkInRecord->notes)
                        <p><strong>{{ __('Notes') }}:</strong> {{ $checkInRecord->notes }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">

                <!-- Camera Section -->
                <div class="text-center mb-6">
                    <div class="relative inline-block">
                        <video id="video" autoplay playsinline class="w-80 h-60 bg-gray-200 dark:bg-gray-700 rounded-lg border-2 border-gray-300 dark:border-gray-600"></video>
                        <canvas id="canvas" class="hidden"></canvas>
                    </div>

                    <div class="mt-4 space-x-4">
                        <button id="startCamera" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            {{ __('Start Camera') }}
                        </button>
                        <button id="capturePhoto" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg" disabled>
                            {{ __('Capture Photo') }}
                        </button>
                        <button id="retakePhoto" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg hidden">
                            {{ __('Retake Photo') }}
                        </button>
                    </div>
                </div>

                <!-- Captured Image Preview -->
                <div id="previewSection" class="hidden text-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">{{ __('Captured Image') }}</h3>
                    <img id="capturedImage" class="w-80 h-60 bg-gray-200 dark:bg-gray-700 rounded-lg border-2 border-gray-300 dark:border-gray-600 mx-auto object-cover">
                </div>

                <!-- Notes Section -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Notes (Optional)') }}
                    </label>
                    <textarea id="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" placeholder="Add any additional notes..."></textarea>
                </div>

                <!-- Check-out Form -->
                <form id="checkoutForm" class="hidden">
                    @csrf
                    <input type="hidden" id="faceImageInput" name="face_image">
                    <input type="hidden" id="notesInput" name="notes">
                    <input type="hidden" name="type" value="check_out">

                    <div class="text-center">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">
                            {{ __('Check Out') }}
                        </button>
                    </div>
                </form>

                <!-- Loading State -->
                <div id="loadingState" class="hidden text-center py-4">
                    <div class="inline-flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('Processing check-out...') }}
                    </div>
                </div>

                <!-- Error/Success Messages -->
                <div id="messageContainer" class="mt-4"></div>
            </div>
        </div>
    </div>

    <script>
        let video, canvas, ctx, capturedImageData;

        document.addEventListener('DOMContentLoaded', function() {
            video = document.getElementById('video');
            canvas = document.getElementById('canvas');
            ctx = canvas.getContext('2d');

            const startCameraBtn = document.getElementById('startCamera');
            const capturePhotoBtn = document.getElementById('capturePhoto');
            const retakePhotoBtn = document.getElementById('retakePhoto');
            const checkoutForm = document.getElementById('checkoutForm');

            startCameraBtn.addEventListener('click', startCamera);
            capturePhotoBtn.addEventListener('click', capturePhoto);
            retakePhotoBtn.addEventListener('click', retakePhoto);
            checkoutForm.addEventListener('submit', processCheckout);
        });

        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: 320,
                        height: 240,
                        facingMode: 'user'
                    }
                });
                video.srcObject = stream;

                document.getElementById('startCamera').disabled = true;
                document.getElementById('capturePhoto').disabled = false;
            } catch (err) {
                showMessage('Camera access denied or not available', 'error');
            }
        }

        function capturePhoto() {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0);

            capturedImageData = canvas.toDataURL('image/jpeg', 0.8);

            // Show preview
            document.getElementById('capturedImage').src = capturedImageData;
            document.getElementById('previewSection').classList.remove('hidden');
            document.getElementById('checkoutForm').classList.remove('hidden');

            // Hide capture button, show retake
            document.getElementById('capturePhoto').classList.add('hidden');
            document.getElementById('retakePhoto').classList.remove('hidden');

            // Stop camera
            const stream = video.srcObject;
            const tracks = stream.getTracks();
            tracks.forEach(track => track.stop());
        }

        function retakePhoto() {
            // Hide preview and form
            document.getElementById('previewSection').classList.add('hidden');
            document.getElementById('checkoutForm').classList.add('hidden');

            // Show capture button, hide retake
            document.getElementById('capturePhoto').classList.remove('hidden');
            document.getElementById('retakePhoto').classList.add('hidden');

            // Reset buttons
            document.getElementById('startCamera').disabled = false;
            document.getElementById('capturePhoto').disabled = true;

            capturedImageData = null;
        }

        async function processCheckout(e) {
            e.preventDefault();

            if (!capturedImageData) {
                showMessage('Please capture a photo first', 'error');
                return;
            }

            const loadingState = document.getElementById('loadingState');
            const submitBtn = e.target.querySelector('button[type="submit"]');

            loadingState.classList.remove('hidden');
            submitBtn.disabled = true;

            // Prepare form data
            const formData = {
                type: 'check_out',
                face_image: capturedImageData,
                notes: document.getElementById('notes').value
            };

            try {
                const response = await fetch('{{ route("attendance.process") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    showMessage(`Check-out successful! Confidence: ${(data.data.confidence_level * 100).toFixed(1)}%`, 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route("attendance.index") }}';
                    }, 2000);
                } else {
                    showMessage(data.message, 'error');
                }
            } catch (error) {
                showMessage('Network error. Please try again.', 'error');
            } finally {
                loadingState.classList.add('hidden');
                submitBtn.disabled = false;
            }
        }

        function showMessage(message, type) {
            const container = document.getElementById('messageContainer');
            let alertClass;

            switch(type) {
                case 'success':
                    alertClass = 'bg-green-50 border-green-200 text-green-800';
                    break;
                case 'error':
                    alertClass = 'bg-red-50 border-red-200 text-red-800';
                    break;
                case 'warning':
                    alertClass = 'bg-yellow-50 border-yellow-200 text-yellow-800';
                    break;
                default:
                    alertClass = 'bg-blue-50 border-blue-200 text-blue-800';
            }

            container.innerHTML = `
                <div class="p-4 border rounded-lg ${alertClass}">
                    ${message}
                </div>
            `;

            // Auto hide after 5 seconds
            setTimeout(() => {
                container.innerHTML = '';
            }, 5000);
        }
    </script>
</x-layouts.app>
