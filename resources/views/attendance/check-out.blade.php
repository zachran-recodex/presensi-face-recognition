<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
           class="text-blue-600 hover:underline">{{ __('Dashboard') }}</a>
        <x-fas-chevron-right class="h-4 w-4 mx-2 text-gray-400" />
        <span class="text-gray-500">{{ __('Check Out') }}</span>
    </div>

    <!-- Page Title -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ __('Check Out') }}</h1>
        <p class="text-gray-600 mt-1">
            {{ __('Complete your check-out with face recognition') }}
        </p>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">

                <!-- Check-in Info -->
                <div class="mb-6">
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-800 mb-2">{{ __('Check-in Information') }}</h3>
                        <div class="text-sm text-gray-600">
                            <div><strong>{{ __('Time') }}:</strong> {{ $checkInRecord->attendance_time->format('H:i:s') }}</div>
                            <div><strong>{{ __('Location') }}:</strong> {{ $checkInRecord->location->name }}</div>
                            <div class="text-xs text-gray-400 mt-2">
                                {{ __('You must check-out from the same location (within :radius meters)', ['radius' => $checkInRecord->location->radius]) }}
                            </div>
                        </div>
                        <input type="hidden" id="locationId" value="{{ $checkInRecord->location->id }}" 
                               data-lat="{{ $checkInRecord->location->latitude }}" 
                               data-lng="{{ $checkInRecord->location->longitude }}" 
                               data-radius="{{ $checkInRecord->location->radius }}">
                    </div>
                </div>

                <!-- Location Status -->
                <div id="locationStatus" class="hidden mb-6">
                    <div id="locationInfo" class="p-4 border rounded-lg">
                        <!-- Will be populated with location info -->
                    </div>
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

                <!-- Notes Section -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Notes (Optional)') }}
                    </label>
                    <textarea id="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Add any additional notes..."></textarea>
                </div>

                <!-- Check-out Form -->
                <form id="checkoutForm" class="hidden">
                    @csrf
                    <input type="hidden" id="faceImageInput" name="face_image">
                    <input type="hidden" id="locationIdInput" name="location_id">
                    <input type="hidden" id="latitudeInput" name="latitude">
                    <input type="hidden" id="longitudeInput" name="longitude">
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
                        <x-fas-spinner class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" />
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
        let userPosition = null;

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

            // Get user location and check proximity to check-in location
            getUserLocation();
        });

        function getUserLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        userPosition = {
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        };
                        console.log('User location:', userPosition);
                        
                        // Check proximity to check-in location
                        checkLocationProximity();
                    },
                    (error) => {
                        console.warn('Geolocation error:', error);
                        showMessage('Location access denied. Location validation will be skipped.', 'warning');
                    }
                );
            }
        }

        function checkLocationProximity() {
            const locationElement = document.getElementById('locationId');
            
            if (locationElement && userPosition) {
                const locationLat = parseFloat(locationElement.dataset.lat);
                const locationLng = parseFloat(locationElement.dataset.lng);
                const radius = parseInt(locationElement.dataset.radius);

                if (locationLat && locationLng) {
                    const distance = calculateDistance(
                        userPosition.latitude,
                        userPosition.longitude,
                        locationLat,
                        locationLng
                    );

                    const withinRadius = distance <= radius;
                    const statusDiv = document.getElementById('locationStatus');
                    const infoDiv = document.getElementById('locationInfo');

                    statusDiv.classList.remove('hidden');

                    if (withinRadius) {
                        infoDiv.className = 'p-4 border rounded-lg bg-green-50 border-green-200 text-green-800';
                        infoDiv.innerHTML = `
                            <div class="flex items-center">
                                <x-fas-check-circle class="h-5 w-5 mr-2" />
                                You are within the required location radius (${Math.round(distance)}m away)
                            </div>
                        `;
                    } else {
                        infoDiv.className = 'p-4 border rounded-lg bg-red-50 border-red-200 text-red-800';
                        infoDiv.innerHTML = `
                            <div class="flex items-center">
                                <x-fas-exclamation-circle class="h-5 w-5 mr-2" />
                                You are too far from the check-in location (${Math.round(distance)}m away, maximum ${radius}m required)
                            </div>
                        `;
                    }
                }
            }
        }

        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371000; // Earth's radius in meters
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
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

            const locationElement = document.getElementById('locationId');
            if (!locationElement || !locationElement.value) {
                showMessage('No location data available.', 'error');
                return;
            }

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
                location_id: locationElement.value,
                face_image: capturedImageData,
                notes: document.getElementById('notes').value
            };

            if (userPosition) {
                formData.latitude = userPosition.latitude;
                formData.longitude = userPosition.longitude;
            }

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