<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Face Recognition API Testing</h1>
        <p class="text-gray-600 mt-1">Test dan kelola semua fitur Face Recognition API</p>
    </div>

    <!-- API Configuration Info -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Konfigurasi API</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-500">Base URL</label>
                <p class="text-sm text-gray-800 break-all">{{ $config['base_url'] }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Access Token</label>
                <p class="text-sm text-gray-800">{{ $config['access_token'] }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Gallery ID</label>
                <p class="text-sm text-gray-800">{{ $config['gallery_id'] }}</p>
            </div>
        </div>
    </div>

    <!-- API Test Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Connection & System Tests -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <x-icon name="fas-link" class="h-5 w-5 mr-2 text-blue-600" />
                Koneksi & Sistem
            </h3>
            
            <div class="space-y-4">
                <!-- Test Connection -->
                <div>
                    <button onclick="testConnection()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <x-icon name="fas-wifi" class="h-4 w-4 mr-2" />
                        Test Koneksi API
                    </button>
                </div>

                <!-- Get Counters -->
                <div>
                    <button onclick="getCounters()" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <x-icon name="fas-chart-bar" class="h-4 w-4 mr-2" />
                        Cek Kuota API
                    </button>
                </div>

                <!-- Create Gallery -->
                <div>
                    <button onclick="createGallery()" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <x-icon name="fas-folder-plus" class="h-4 w-4 mr-2" />
                        Buat/Cek Gallery
                    </button>
                </div>

                <!-- My Galleries -->
                <div>
                    <button onclick="getMyGalleries()" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <x-icon name="fas-folder" class="h-4 w-4 mr-2" />
                        Gallery Saya
                    </button>
                </div>

                <!-- List Faces -->
                <div>
                    <button onclick="listFaces()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <x-icon name="fas-users" class="h-4 w-4 mr-2" />
                        Daftar Wajah Terdaftar
                    </button>
                </div>
            </div>
        </div>

        <!-- Face Operations -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <x-icon name="fas-user" class="h-5 w-5 mr-2 text-green-600" />
                Operasi Wajah
            </h3>
            
            <!-- Camera Section -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kamera</label>
                <div class="relative">
                    <video id="video" width="100%" height="240" autoplay class="rounded-lg border bg-gray-100"></video>
                    <canvas id="canvas" style="display: none;"></canvas>
                </div>
                <div class="flex gap-2 mt-2">
                    <button onclick="startCamera()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors flex items-center justify-center">
                        <x-icon name="fas-video" class="h-4 w-4 mr-1" />
                        Start Kamera
                    </button>
                    <button onclick="captureImage()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors flex items-center justify-center">
                        <x-icon name="fas-camera" class="h-4 w-4 mr-1" />
                        Ambil Foto
                    </button>
                </div>
            </div>

            <!-- Test User Selection -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">User untuk Testing</label>
                <select id="testUserId" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Pilih user...</option>
                    @foreach($enrolledUsers as $user)
                        <option value="{{ $user->employee_id ?: $user->id }}" data-name="{{ $user->name }}">
                            {{ $user->name }} ({{ $user->employee_id ?: 'ID: ' . $user->id }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Face Operations Buttons -->
            <div class="space-y-2">
                <button onclick="testEnrollment()" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                    <x-icon name="fas-user-plus" class="h-4 w-4 mr-2" />
                    Test Enrollment
                </button>
                <button onclick="testVerification()" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                    <x-icon name="fas-user-check" class="h-4 w-4 mr-2" />
                    Test Verification (1:1)
                </button>
                <button onclick="testIdentification()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                    <x-icon name="fas-search" class="h-4 w-4 mr-2" />
                    Test Identification (1:N)
                </button>
                <button onclick="testDeletion()" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                    <x-icon name="fas-user-times" class="h-4 w-4 mr-2" />
                    Test Deletion
                </button>
            </div>
        </div>
    </div>

    <!-- Face Comparison Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <x-icon name="fas-exchange-alt" class="h-5 w-5 mr-2 text-orange-600" />
            Perbandingan Wajah
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Source Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Sumber</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                    <input type="file" id="sourceImage" accept="image/*" class="hidden" onchange="previewImage('sourceImage', 'sourcePreview')">
                    <div id="sourcePreview" class="mb-2"></div>
                    <button onclick="document.getElementById('sourceImage').click()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <x-icon name="fas-image" class="h-4 w-4 mr-2" />
                        Pilih Gambar
                    </button>
                </div>
            </div>

            <!-- Target Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Target</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                    <input type="file" id="targetImage" accept="image/*" class="hidden" onchange="previewImage('targetImage', 'targetPreview')">
                    <div id="targetPreview" class="mb-2"></div>
                    <button onclick="document.getElementById('targetImage').click()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <x-icon name="fas-image" class="h-4 w-4 mr-2" />
                        Pilih Gambar
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button onclick="testComparison()" class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                <x-icon name="fas-balance-scale" class="h-4 w-4 mr-2" />
                Bandingkan Wajah
            </button>
        </div>
    </div>

    <!-- Results Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <x-icon name="fas-clipboard-list" class="h-5 w-5 mr-2 text-gray-600" />
            Hasil Testing
        </h3>
        <div id="results" class="bg-gray-50 rounded-lg p-4 min-h-32">
            <p class="text-gray-500 text-sm">Hasil API testing akan ditampilkan di sini...</p>
        </div>
    </div>

    <script>
        let currentImageData = null;
        
        // Camera functions
        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                document.getElementById('video').srcObject = stream;
                showResult('success', 'Kamera berhasil diaktifkan');
            } catch (error) {
                showResult('error', 'Gagal mengaktifkan kamera: ' + error.message);
            }
        }

        function captureImage() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const ctx = canvas.getContext('2d');
            
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0);
            
            currentImageData = canvas.toDataURL('image/jpeg', 0.4);
            showResult('success', 'Foto berhasil diambil');
        }

        // Image preview function
        function previewImage(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="max-w-full h-32 object-cover rounded">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // File to base64 conversion
        function fileToBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
        }

        // API Test Functions
        async function testConnection() {
            showLoading('Testing API connection...');
            try {
                const response = await fetch('{{ route("admin.face-api-test.connection") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const result = await response.json();
                showResult(result.success ? 'success' : 'error', result.message, result.data);
            } catch (error) {
                showResult('error', 'Connection test failed: ' + error.message);
            }
        }

        async function getCounters() {
            showLoading('Getting API counters...');
            try {
                const response = await fetch('{{ route("admin.face-api-test.counters") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const result = await response.json();
                showResult(result.success ? 'success' : 'error', result.message, result.data);
            } catch (error) {
                showResult('error', 'Failed to get counters: ' + error.message);
            }
        }

        async function createGallery() {
            showLoading('Creating/checking gallery...');
            try {
                const response = await fetch('{{ route("admin.face-api-test.create-gallery") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const result = await response.json();
                showResult(result.success ? 'success' : 'error', result.message, result.data);
            } catch (error) {
                showResult('error', 'Gallery creation failed: ' + error.message);
            }
        }

        async function getMyGalleries() {
            showLoading('Getting my galleries...');
            try {
                const response = await fetch('{{ route("admin.face-api-test.my-galleries") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const result = await response.json();
                showResult(result.success ? 'success' : 'error', result.message, result.data);
            } catch (error) {
                showResult('error', 'Failed to get my galleries: ' + error.message);
            }
        }

        async function listFaces() {
            showLoading('Listing enrolled faces...');
            try {
                const response = await fetch('{{ route("admin.face-api-test.list-faces") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const result = await response.json();
                showResult(result.success ? 'success' : 'error', result.message, result.data);
            } catch (error) {
                showResult('error', 'Failed to list faces: ' + error.message);
            }
        }

        async function testEnrollment() {
            if (!currentImageData) {
                showResult('error', 'Silakan ambil foto terlebih dahulu');
                return;
            }

            const userId = prompt('Masukkan User ID untuk enrollment test:');
            const userName = prompt('Masukkan User Name untuk enrollment test:');
            
            if (!userId || !userName) {
                showResult('error', 'User ID dan Name harus diisi');
                return;
            }

            showLoading('Testing face enrollment...');
            try {
                const response = await fetch('{{ route("admin.face-api-test.test-enrollment") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        user_name: userName,
                        face_image: currentImageData
                    })
                });
                const result = await response.json();
                showResult(result.success ? 'success' : 'error', result.message, result.data);
            } catch (error) {
                showResult('error', 'Enrollment test failed: ' + error.message);
            }
        }

        async function testVerification() {
            if (!currentImageData) {
                showResult('error', 'Silakan ambil foto terlebih dahulu');
                return;
            }

            const select = document.getElementById('testUserId');
            const userId = select.value;
            
            if (!userId) {
                showResult('error', 'Silakan pilih user untuk testing');
                return;
            }

            showLoading('Testing face verification...');
            try {
                const response = await fetch('{{ route("admin.face-api-test.test-verification") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        face_image: currentImageData
                    })
                });
                const result = await response.json();
                showResult(result.success ? 'success' : 'error', result.message, result.data);
            } catch (error) {
                showResult('error', 'Verification test failed: ' + error.message);
            }
        }

        async function testIdentification() {
            if (!currentImageData) {
                showResult('error', 'Silakan ambil foto terlebih dahulu');
                return;
            }

            showLoading('Testing face identification...');
            try {
                const response = await fetch('{{ route("admin.face-api-test.test-identification") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        face_image: currentImageData
                    })
                });
                const result = await response.json();
                showResult(result.success ? 'success' : 'error', result.message, result.data);
            } catch (error) {
                showResult('error', 'Identification test failed: ' + error.message);
            }
        }

        async function testComparison() {
            const sourceFile = document.getElementById('sourceImage').files[0];
            const targetFile = document.getElementById('targetImage').files[0];

            if (!sourceFile || !targetFile) {
                showResult('error', 'Silakan pilih kedua gambar untuk perbandingan');
                return;
            }

            showLoading('Testing face comparison...');
            try {
                const sourceImage = await fileToBase64(sourceFile);
                const targetImage = await fileToBase64(targetFile);

                const response = await fetch('{{ route("admin.face-api-test.test-comparison") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        source_image: sourceImage,
                        target_image: targetImage
                    })
                });
                const result = await response.json();
                showResult(result.success ? 'success' : 'error', result.message, result.data);
            } catch (error) {
                showResult('error', 'Comparison test failed: ' + error.message);
            }
        }

        async function testDeletion() {
            const select = document.getElementById('testUserId');
            const userId = select.value;
            
            if (!userId) {
                showResult('error', 'Silakan pilih user untuk testing');
                return;
            }

            if (!confirm('Yakin ingin menghapus wajah user ini dari API? (untuk testing)')) {
                return;
            }

            showLoading('Testing face deletion...');
            try {
                const response = await fetch('{{ route("admin.face-api-test.test-deletion") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_id: userId
                    })
                });
                const result = await response.json();
                showResult(result.success ? 'success' : 'error', result.message, result.data);
            } catch (error) {
                showResult('error', 'Deletion test failed: ' + error.message);
            }
        }

        // Utility functions
        function showLoading(message) {
            const results = document.getElementById('results');
            results.innerHTML = `
                <div class="flex items-center text-blue-600">
                    <i class="fas fa-spinner fa-spin mr-3 text-blue-600"></i>
                    <span class="text-sm font-medium">${message}</span>
                </div>
            `;
        }

        function showResult(type, message, data = null) {
            const results = document.getElementById('results');
            const bgColor = type === 'success' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200';
            const textColor = type === 'success' ? 'text-green-800' : 'text-red-800';
            const iconColor = type === 'success' ? 'text-green-600' : 'text-red-600';
            const icon = type === 'success' ? '<i class="fas fa-check-circle text-green-600"></i>' : '<i class="fas fa-times-circle text-red-600"></i>';
            
            let dataHtml = '';
            if (data) {
                dataHtml = `
                    <div class="mt-3 p-3 bg-white rounded border">
                        <h4 class="text-sm font-medium text-gray-800 mb-2">Response Data:</h4>
                        <pre class="text-xs text-gray-600 overflow-auto max-h-64">${JSON.stringify(data, null, 2)}</pre>
                    </div>
                `;
            }
            
            results.innerHTML = `
                <div class="border rounded-lg p-4 ${bgColor}">
                    <div class="flex items-start">
                        <span class="text-lg mr-2">${icon}</span>
                        <div class="flex-1">
                            <p class="text-sm font-medium ${textColor}">${message}</p>
                            <p class="text-xs text-gray-500 mt-1">${new Date().toLocaleString()}</p>
                            ${dataHtml}
                        </div>
                    </div>
                </div>
            `;
        }

        // Start camera automatically
        window.addEventListener('load', function() {
            startCamera();
        });
    </script>
</x-layouts.app>