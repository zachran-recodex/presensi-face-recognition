<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
           class="text-blue-600 hover:underline">{{ __('Dashboard') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500">{{ __('Face Enrollment') }}</span>
    </div>

    <!-- Page Title -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ __('Face Enrollment') }}</h1>
        <p class="text-gray-600 mt-1">
            {{ __('Enroll your face to enable face recognition for attendance') }}
        </p>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">

                <!-- Instructions -->
                <div class="mb-6 p-4 bg-blue-50/20 border border-blue-200 rounded-lg">
                    <h3 class="text-lg font-semibold text-blue-800 mb-2">{{ __('Instructions') }}</h3>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• {{ __('Ensure good lighting and face the camera directly') }}</li>
                        <li>• {{ __('Remove any face covering (mask, glasses if possible)') }}</li>
                        <li>• {{ __('Keep your face centered in the camera frame') }}</li>
                        <li>• {{ __('Avoid shadows on your face') }}</li>
                        <li>• {{ __('Click capture when you are ready') }}</li>
                    </ul>
                </div>

                <!-- Camera Section -->
                <div class="text-center mb-6">
                    <div class="relative inline-block">
                        <video id="video" autoplay playsinline class="w-80 h-60 bg-gray-200 rounded-lg border-2 border-gray-300"></video>
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
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ __('Captured Image') }}</h3>
                    <img id="capturedImage" class="w-80 h-60 bg-gray-200 rounded-lg border-2 border-gray-300 mx-auto object-cover">
                </div>

                <!-- Enrollment Form -->
                <form id="enrollmentForm" class="hidden">
                    @csrf
                    <input type="hidden" id="faceImageInput" name="face_image">

                    <div class="text-center">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">
                            {{ __('Enroll Face') }}
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
                        {{ __('Processing...') }}
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
            const enrollmentForm = document.getElementById('enrollmentForm');

            startCameraBtn.addEventListener('click', startCamera);
            capturePhotoBtn.addEventListener('click', capturePhoto);
            retakePhotoBtn.addEventListener('click', retakePhoto);
            enrollmentForm.addEventListener('submit', enrollFace);
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
            // Set reasonable dimensions for face recognition (max 640x480)
            const maxWidth = 640;
            const maxHeight = 480;

            let { videoWidth, videoHeight } = video;

            // Calculate scaling to fit within max dimensions
            const scale = Math.min(maxWidth / videoWidth, maxHeight / videoHeight, 1);

            canvas.width = videoWidth * scale;
            canvas.height = videoHeight * scale;

            // Draw scaled image
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Use lower quality to reduce file size (0.4 = 40% quality)
            capturedImageData = canvas.toDataURL('image/jpeg', 0.4);

            // Log image size for debugging
            const imageSizeKB = Math.round(capturedImageData.length * 0.75 / 1024); // Approximate KB
            console.log(`Captured image size: ~${imageSizeKB}KB`);

            // Show preview
            document.getElementById('capturedImage').src = capturedImageData;
            document.getElementById('previewSection').classList.remove('hidden');
            document.getElementById('enrollmentForm').classList.remove('hidden');

            // Hide capture button, show retake
            document.getElementById('capturePhoto').classList.add('hidden');
            document.getElementById('retakePhoto').classList.remove('hidden');

            // Set form data
            document.getElementById('faceImageInput').value = capturedImageData;

            // Stop camera
            const stream = video.srcObject;
            const tracks = stream.getTracks();
            tracks.forEach(track => track.stop());
        }

        function retakePhoto() {
            // Hide preview and form
            document.getElementById('previewSection').classList.add('hidden');
            document.getElementById('enrollmentForm').classList.add('hidden');

            // Show capture button, hide retake
            document.getElementById('capturePhoto').classList.remove('hidden');
            document.getElementById('retakePhoto').classList.add('hidden');

            // Reset buttons
            document.getElementById('startCamera').disabled = false;
            document.getElementById('capturePhoto').disabled = true;

            capturedImageData = null;
        }

        async function enrollFace(e) {
            e.preventDefault();

            if (!capturedImageData) {
                showMessage('Please capture a photo first', 'error');
                return;
            }

            const loadingState = document.getElementById('loadingState');
            const submitBtn = e.target.querySelector('button[type="submit"]');

            loadingState.classList.remove('hidden');
            submitBtn.disabled = true;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    showMessage('CSRF token not found. Please refresh the page.', 'error');
                    return;
                }

                const response = await fetch('{{ route("face.enroll.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        face_image: capturedImageData
                    })
                });

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('HTTP Error:', response.status, errorText);
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();

                if (data.success) {
                    showMessage(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route("dashboard") }}';
                    }, 2000);
                } else {
                    showMessage(data.message || 'Face enrollment failed', 'error');
                }
            } catch (error) {
                console.error('Enrollment error:', error);
                if (error.message.includes('HTTP')) {
                    showMessage(`Server error: ${error.message}`, 'error');
                } else if (error.name === 'TypeError' && error.message.includes('fetch')) {
                    showMessage('Network connection error. Please check your internet connection.', 'error');
                } else {
                    showMessage(`Error: ${error.message}`, 'error');
                }
            } finally {
                loadingState.classList.add('hidden');
                submitBtn.disabled = false;
            }
        }

        function showMessage(message, type) {
            const container = document.getElementById('messageContainer');
            const alertClass = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800';

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
