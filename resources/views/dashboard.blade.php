<x-layouts.app>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-600 mt-1">
            @if(auth()->user()->isAdmin())
                Selamat datang di {{ Auth::user()->name }} (Admin)
            @else
                Selamat datang {{ Auth::user()->name }}
            @endif
        </p>
    </div>

    @if(auth()->user()->isAdmin())
        <!-- Admin Dashboard -->
        @php
            $todayAttendances = \App\Models\Attendance::with(['user', 'location'])->today()->get();
            $totalUsers = \App\Models\User::where('role', 'user')->count();
            $totalLocations = \App\Models\Location::count();
            $enrolledUsers = \App\Models\User::where('is_face_enrolled', true)->count();
        @endphp

        <!-- Face Enrollment Warning for Admin -->
        @if(!auth()->user()->is_face_enrolled)
            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Wajah Admin Belum Terdaftar
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Sebagai admin, Anda juga perlu mendaftarkan wajah untuk dapat melakukan presensi dan menggunakan fitur pengenalan wajah.</p>
                        </div>
                        <div class="mt-4">
                            <div class="-mx-2 -my-1.5 flex">
                                <a href="{{ route('face.enroll') }}" class="bg-yellow-50 px-2 py-1.5 rounded-md text-sm font-medium text-yellow-800 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-yellow-50 focus:ring-yellow-600">
                                    Daftar Wajah Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Users -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Karyawan</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalUsers }}</p>
                        <p class="text-xs text-gray-500 flex items-center mt-1">
                            <x-icon name="face" class="h-4 w-4 mr-1" />
                            {{ $enrolledUsers }} wajah terdaftar
                        </p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <x-icon name="users" class="h-6 w-6 text-blue-500" />
                    </div>
                </div>
            </div>

            <!-- Total Locations -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Lokasi</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalLocations }}</p>
                        <p class="text-xs text-gray-500 flex items-center mt-1">
                            <x-icon name="location-dot" class="h-4 w-4 mr-1" />
                            {{ \App\Models\Location::where('is_active', true)->count() }} aktif
                        </p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <x-icon name="location-dot" class="h-6 w-6 text-green-500" />
                    </div>
                </div>
            </div>

            <!-- Today Check-ins -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Absen Masuk Hari ini</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $todayAttendances->where('type', 'check_in')->count() }}</p>
                        <p class="text-xs text-gray-500 flex items-center mt-1">
                            <x-icon name="login" class="h-4 w-4 mr-1" />
                            {{ $todayAttendances->where('type', 'check_in')->where('is_verified', true)->count() }} berhasil
                        </p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <x-icon name="login" class="h-6 w-6 text-purple-500" />
                    </div>
                </div>
            </div>

            <!-- Today Check-outs -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Absen Keluar Hari ini</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $todayAttendances->where('type', 'check_out')->count() }}</p>
                        <p class="text-xs text-gray-500 flex items-center mt-1">
                            <x-icon name="logout" class="h-4 w-4 mr-1" />
                            {{ $todayAttendances->where('type', 'check_out')->where('is_verified', true)->count() }} berhasil
                        </p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <x-icon name="logout" class="h-6 w-6 text-orange-500" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions for Admin -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.locations.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <x-icon name="location-dot" class="w-5 h-5 text-blue-600" />
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Kelola Lokasi</h3>
                            <p class="text-sm text-gray-500">Menambah dan mengonfigurasi lokasi absen</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.attendance.history') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-lg mr-3">
                            <x-icon name="login" class="w-5 h-5 text-green-600" />
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Lihat Semua Presensi </h3>
                            <p class="text-sm text-gray-500">Memantau semua riwayat presensi karyawan</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('settings.profile.edit') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-2 rounded-lg mr-3">
                            <x-icon name="setting" class="w-5 h-5 text-purple-600" />
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Pengaturan</h3>
                            <p class="text-sm text-gray-500">Mengelola pengaturan akun Anda</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    @else
        <!-- User Dashboard -->
        @php
            $user = auth()->user();
            $todayAttendances = $user->getTodayAttendances();
            $hasCheckedIn = $user->hasCheckedInToday();
            $hasCheckedOut = $user->hasCheckedOutToday();
            $thisMonthAttendances = $user->attendances()->thisMonth()->count();
        @endphp

        <!-- Face Enrollment Warning for Users -->
        @if(!$user->is_face_enrolled)
            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Wajah Belum Terdaftar
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Anda perlu mendaftarkan wajah terlebih dahulu sebelum dapat melakukan presensi.</p>
                        </div>
                        <div class="mt-4">
                            <div class="-mx-2 -my-1.5 flex">
                                <a href="{{ route('face.enroll') }}" class="bg-yellow-50 px-2 py-1.5 rounded-md text-sm font-medium text-yellow-800 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-yellow-50 focus:ring-yellow-600">
                                    Daftar Wajah Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Face Enrollment Status -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pengenalan Wajah</p>
                        <p class="text-lg font-bold text-gray-800 mt-1">
                            @if($user->is_face_enrolled)
                                Terdaftar
                            @else
                                Tidak Terdaftar
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($user->is_face_enrolled)
                                Siap untuk presensi
                            @else
                                Harus mendaftarkan wajah
                            @endif
                        </p>
                    </div>
                    <div class="bg-{{ $user->is_face_enrolled ? 'green' : 'red' }}-100 p-3 rounded-full">
                        @if($user->is_face_enrolled)
                            <x-icon name="face" class="h-6 w-6 text-green-500" />
                        @else
                            <x-icon name="face" class="h-6 w-6 text-red-500 />
                        @endif
                    </div>
                </div>
            </div>

            <!-- Today's Status -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Status Absen Hari ini</p>
                        <p class="text-lg font-bold text-gray-800 mt-1">
                            @if($hasCheckedOut)
                                Selesai
                            @elseif($hasCheckedIn)
                                Check In
                            @else
                                Belum Dimulai
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($hasCheckedIn)
                                {{ $user->getTodayCheckIn()->attendance_time->translatedFormat('H:i') }}
                            @else
                                Belum Check In
                            @endif
                        </p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <x-icon name="calendar" class="h-6 w-6 text-blue-500" />
                    </div>
                </div>
            </div>

            <!-- This Month Attendances -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Bulan {{ now()->translatedFormat('F') }}</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $thisMonthAttendances }}</p>
                        <p class="text-xs text-gray-500 flex items-center mt-1">
                            <x-icon name="chart-up" class="h-4 w-4 mr-1" />
                            Total Akvitas
                        </p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <x-icon name="chart-up" class="h-6 w-6 text-purple-500" />
                    </div>
                </div>
            </div>

            <!-- Quick Action -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="text-center">
                    @if(!$user->is_face_enrolled)
                        <a href="{{ route('face.enroll') }}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Daftar Wajah
                        </a>
                    @elseif(!$hasCheckedIn)
                        <a href="{{ route('attendance.check-in') }}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Check In
                        </a>
                    @elseif(!$hasCheckedOut)
                        <a href="{{ route('attendance.check-out') }}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Check Out
                        </a>
                    @else
                        <div class="text-center py-4">
                            <div class="text-green-600 font-medium">
                                ✓ Presensi Selesai
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Sampai jumpa besok!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions for User -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('attendance.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Presensi</h3>
                            <p class="text-sm text-gray-500">Check in/out dan melihat status</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('attendance.history') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Riwayat</h3>
                            <p class="text-sm text-gray-500">Melihat riwayat presensi Anda</p>
                        </div>
                    </div>
                </a>

                <a href="{{ $user->is_face_enrolled ? route('face.edit') : route('face.enroll') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">
                                @if($user->is_face_enrolled)
                                    {{ __('Update Face') }}
                                @else
                                    {{ __('Enroll Face') }}
                                @endif
                            </h3>
                            <p class="text-sm text-gray-500">{{ __('Manage face recognition') }}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @endif

</x-layouts.app>
