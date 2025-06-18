<x-layouts.app>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
        <p class="text-gray-600 mt-1">
            Selamat datang {{ Auth::user()->name }} (Admin)
        </p>
    </div>

    <!-- Face Enrollment Warning for Admin -->
    @if(!auth()->user()->is_face_enrolled)
        <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <x-fas-triangle-exclamation class="h-5 w-5 text-yellow-400" />
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
                        <x-fas-face-grin class="h-4 w-4 mr-1" />
                        {{ $enrolledUsers }} wajah terdaftar
                    </p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <x-fas-users class="h-6 w-6 text-blue-500" />
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
                        <x-fas-location-dot class="h-4 w-4 mr-1" />
                        {{ \App\Models\Location::where('is_active', true)->count() }} aktif
                    </p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <x-fas-location-dot class="h-6 w-6 text-green-500" />
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
                        <x-fas-right-to-bracket class="h-4 w-4 mr-1" />
                        {{ $todayAttendances->where('type', 'check_in')->where('is_verified', true)->count() }} berhasil
                    </p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <x-fas-right-to-bracket class="h-6 w-6 text-purple-500" />
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
                        <x-fas-right-from-bracket class="h-4 w-4 mr-1" />
                        {{ $todayAttendances->where('type', 'check_out')->where('is_verified', true)->count() }} berhasil
                    </p>
                </div>
                <div class="bg-orange-100 p-3 rounded-full">
                    <x-fas-right-from-bracket class="h-6 w-6 text-orange-500" />
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Attendance Overview -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Presensi Hari Ini</h2>
            
            @if($todayAttendances->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Karyawan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipe
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waktu
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Lokasi
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($todayAttendances->take(10) as $attendance)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $attendance->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $attendance->user->employee_id ?: $attendance->user->id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($attendance->type === 'check_in')
                                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                                    <x-fas-right-to-bracket class="w-4 h-4 text-green-600" />
                                                </div>
                                                <span class="text-sm font-medium text-green-800">Check In</span>
                                            @else
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                    <x-fas-right-from-bracket class="w-4 h-4 text-blue-600" />
                                                </div>
                                                <span class="text-sm font-medium text-blue-800">Check Out</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $attendance->attendance_time->format('H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $attendance->location->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($attendance->is_verified)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ✓ Verified
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                ✗ Not Verified
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($todayAttendances->count() > 10)
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.attendance.history') }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                            Lihat Semua ({{ $todayAttendances->count() }} total)
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <x-fas-clock class="mx-auto h-12 w-12 text-gray-400" />
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Aktivitas</h3>
                    <p class="mt-1 text-sm text-gray-500">Belum ada presensi yang tercatat hari ini.</p>
                </div>
            @endif
        </div>
    </div>

</x-layouts.app>