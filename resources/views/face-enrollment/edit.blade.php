<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Update Face Recognition') }}</span>
    </div>

    <!-- Page Title -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Update Face Recognition') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            {{ __('Update your enrolled face for better recognition accuracy') }}
        </p>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">

                <!-- Current Status -->
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg">
                    <h3 class="text-lg font-semibold text-green-800 dark:text-green-200 mb-2">{{ __('Current Status') }}</h3>
                    <div class="text-sm text-green-700 dark:text-green-300 space-y-1">
                        <p>✓ {{ __('Face is currently enrolled and active') }}</p>
                        <p>{{ __('You can update your face image below if needed') }}</p>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg">
                    <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-2">{{ __('Update Instructions') }}</h3>
                    <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                        <li>• {{ __('Ensure good lighting and face the camera directly') }}</li>
                        <li>• {{ __('Remove any face covering (mask, glasses if possible)') }}</li>
                        <li>• {{ __('Keep your face centered in the camera frame') }}</li>
                        <li>• {{ __('Your old face data will be replaced with the new image') }}</li>
                        <li>• {{ __('Click capture when you are ready') }}</li>
                    </ul>
                </div>

                <!-- Test Current Face -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Test Current Face Recognition') }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        {{ __('Test your current enrolled face before updating to ensure it\'s working properly.') }}
                    </p>

                    <div class="text-center mb-4">
                        <video id="testVideo" autoplay playsinline class="w-80 h-60 bg-gray-200 dark:bg-gray-700 rounded-lg border-2 border-gray-300 dark:border-gray-600 hidden"></video>
                        <canvas id="testCanvas" class="hidden"></canvas>
                    </div>

                    <div class="text-center mb-4 space-x-4">
                        <button id="startTestCamera" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                            {{ __('Start Test Camera') }}
                        </button>
                        <button id="testFace" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg hidden">
                            {{ __('Test Face Recognition') }}
                        </button>
                    </div>

                    <div id="testResult" class="hidden mb-4"></div>
                </div>

                <hr class="border-gray-200 dark:border-gray-700 mb-6">

                <!-- Camera Section for Update -->
                <div class="text-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Capture New Face Image') }}</h3>

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
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">{{ __('New Captured Image') }}</h3>
                    <img id="capturedImage" class="w-80 h-60 bg-gray-200 dark:bg-gray-700 rounded-lg border-2 border-gray-300 dark:border-gray-600 mx-auto object-cover">
                </div>

                <!-- Update Form -->
                <form id="updateForm" class="hidden">
                    @csrf
                    <input type="hidden" id="faceImageInput" name="face_image">

                    <div class="text-center space-x-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">
                            {{ __('Update Face Recognition') }}
                        </button>
                        <button type="button" id="cancelUpdate" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>

                <!-- Delete Face Enrollment -->
                <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-2">
                        {{ __('Delete Face Enrollment') }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        {{ __('Remove your face enrollment completely. You will need to re-enroll to use face recognition again.') }}
                    </p>
                    <form action="{{ route('face.delete') }}" method="POST"
                          onsubmit="return confirm('{{ __('Are you sure you want to delete your face enrollment? This cannot be undone.') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                            {{ __('Delete Face Enrollment') }}
                        </button>
                    </form>
                </div>

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
        let testVideo, testCanvas, testCtx;

        document.addEventListener('DOMContentLoaded', function() {
            video = document.getElementById('video');
            canvas = document.getElementById('canvas');
            ctx = canvas.getContext('2d');

            testVideo = document.getElementById('testVideo');
            testCanvas = document.getElementById('testCanvas');
            testCtx = testCanvas.getContext('2d');

            const startCameraBtn = document.getElementById('startCamera');
            const capturePhotoBtn = document.getElementById('capturePhoto');
            const retakePhotoBtn = document.getElementById('retakePhoto');
            const updateForm = document.getElementById('updateForm');
            const cancelUpdateBtn = document.getElementById('cancelUpdate');

            const startTestCameraBtn = document.getElementById('startTestCamera');
            const testFaceBtn = document.getElementById('testFace');

            startCameraBtn.addEventListener('click', startCamera);
            capturePhotoBtn.addEventListener('click', capturePhoto);
            retakePhotoBtn.addEventListener('click', retakePhoto);
            updateForm.addEventListener('submit', updateFace);
            cancelUpdateBtn.addEventListener('click', cancelUpdate);

            startTestCameraBtn.addEventListener('click', startTestCamera);
            testFaceBtn.addEventListener('click', testFaceRecognition);
        });

        async function startTestCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: 320,
                        height: 240,
                        facingMode: 'user'
                    }
                });
                testVideo.srcObject = stream;
                testVideo.classList.remove('hidden');

                document.getElementById('startTestCamera').classList.add('hidden');
                document.getElementById('testFace').classList.remove('hidden');
            } catch (err) {
                showMessage('Camera access denied or not available', 'error');
            }
        }

        function testFaceRecognition() {
            testCanvas.width = testVideo.videoWidth;
            testCanvas.height = testVideo.videoHeight;
            testCtx.drawImage(testVideo, 0, 0);

            const imageData = testCanvas.toDataURL('image/jpeg', 0.8);

            // Test face recognition
            testCurrentFace(imageData);

            // Stop test camera
            const stream = testVideo.srcObject;
            const tracks = stream.getTracks();
            tracks.forEach(track => track.stop());
            testVideo.classList.add('hidden');
            document.getElementById('startTestCamera').classList.remove('hidden');
            document.getElementById('testFace').classList.add('hidden');
        }

        async function testCurrentFace(imageData) {
            try {
                const response = await fetch('{{ route("face.test") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        face_image: imageData
                    })
                });

                const data = await response.json();
                const resultDiv = document.getElementById('testResult');
                resultDiv.classList.remove('hidden');

                if (data.success) {
                    const verified = data.data.verified;
                    const similarity = (data.data.similarity * 100).toFixed(1);

                    if (verified) {
                        resultDiv.innerHTML = `
                            <div class="p-4 border rounded-lg bg-green-50 border-green-200 text-green-800">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Test Successful! Face verified with ${similarity}% confidence
                                </div>
                            </div>
                        `;
                    } else {
                        resultDiv.innerHTML = `
                            <div class="p-4 border rounded-lg bg-yellow-50 border-yellow-200 text-yellow-800">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Test Warning: Face not verified (${similarity}% confidence). Consider updating your face enrollment.
                                </div>
                            </div>
                        `;
                    }
                } else {
                    resultDiv.innerHTML = `
                        <div class="p-4 border rounded-lg bg-red-50 border-red-200 text-red-800">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Test Failed: ${data.message}
                            </div>
                        </div>
                    `;
                }
            } catch (error) {
                showMessage('Test failed. Please try again.', 'error');
            }
        }

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
            document.getElementById('updateForm').classList.remove('hidden');

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
            document.getElementById('updateForm').classList.add('hidden');

            // Show capture button, hide retake
            document.getElementById('capturePhoto').classList.remove('hidden');
            document.getElementById('retakePhoto').classList.add('hidden');

            // Reset buttons
            document.getElementById('startCamera').disabled = false;
            document.getElementById('capturePhoto').disabled = true;

            capturedImageData = null;
        }

        function cancelUpdate() {
            retakePhoto();
        }

        async function updateFace(e) {
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
                const response = await fetch('{{ route("face.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        face_image: capturedImageData
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showMessage(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route("dashboard") }}';
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
