<x-layouts.app>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-600 mt-1">
            Selamat datang {{ Auth::user()->name }}
        </p>
    </div>

    <!-- Face Enrollment Warning for Users -->
    @if(!$user->is_face_enrolled)
        <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <x-fas-triangle-exclamation class="h-5 w-5 text-yellow-400" />
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-yellow-800">
                        Wajah Belum Terdaftar
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Anda perlu mendaftarkan wajah terlebih dahulu sebelum dapat melakukan presensi.</p>
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
                        <x-fas-face-grin class="h-6 w-6 text-green-500" />
                    @else
                        <x-fas-face-frown class="h-6 w-6 text-red-500" />
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
                            @if($user->getTodayCheckIn()->is_late)
                                <span class="text-red-600 font-medium"> - Terlambat {{ $user->getTodayCheckIn()->late_minutes }}m</span>
                            @endif
                        @else
                            Belum Check In
                        @endif
                    </p>
                    @if($hasCheckedOut && $user->getTodayCheckOut()->is_late)
                        <p class="text-xs text-red-600 font-medium mt-1">
                            Check Out: {{ $user->getTodayCheckOut()->attendance_time->translatedFormat('H:i') }} - Pulang Awal {{ $user->getTodayCheckOut()->late_minutes }}m
                        </p>
                    @endif
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <x-fas-calendar-days class="h-6 w-6 text-blue-500" />
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
                        <x-fas-chart-simple class="h-4 w-4 mr-1" />
                        Total Aktivitas
                    </p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <x-fas-chart-simple class="h-6 w-6 text-purple-500" />
                </div>
            </div>
        </div>

        <!-- Quick Action -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="text-center">
                @if(!$user->is_face_enrolled)
                    <a href="{{ route('face.enroll') }}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <x-fas-user class="w-4 h-4 mr-2" />
                        Daftar Wajah
                    </a>
                @elseif(!$hasCheckedIn)
                    <a href="{{ route('attendance.check-in') }}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <x-fas-right-to-bracket class="w-4 h-4 mr-2" />
                        Check In
                    </a>
                @elseif(!$hasCheckedOut)
                    <a href="{{ route('attendance.check-out') }}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <x-fas-right-from-bracket class="w-4 h-4 mr-2" />
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

    <!-- Attendance History Section -->
    <div class="mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">{{ __('Attendance History') }}</h2>
                </div>

                <!-- Filters -->
                <div class="mb-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Start Date') }}
                            </label>
                            <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('End Date') }}
                            </label>
                            <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Type') }}
                            </label>
                            <select id="type" name="type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">{{ __('All Types') }}</option>
                                <option value="check_in" {{ request('type') === 'check_in' ? 'selected' : '' }}>{{ __('Check In') }}</option>
                                <option value="check_out" {{ request('type') === 'check_out' ? 'selected' : '' }}>{{ __('Check Out') }}</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                {{ __('Filter') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Attendance List -->
                @if(isset($attendances) && $attendances->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Type') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Date & Time') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Location') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Status') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Late Status') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($attendances as $attendance)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($attendance->type === 'check_in')
                                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                                    <x-fas-right-to-bracket class="w-4 h-4 text-green-600" />
                                                </div>
                                                <span class="text-sm font-medium text-green-800">{{ __('Check In') }}</span>
                                            @else
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                    <x-fas-right-from-bracket class="w-4 h-4 text-blue-600" />
                                                </div>
                                                <span class="text-sm font-medium text-blue-800">{{ __('Check Out') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $attendance->attendance_time->format('M d, Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $attendance->attendance_time->format('H:i:s') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $attendance->location->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($attendance->is_verified)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ✓ {{ __('Verified') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                ✗ {{ __('Not Verified') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($attendance->is_late)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                ⏰ 
                                                @if($attendance->type === 'check_in')
                                                    Terlambat ({{ $attendance->late_minutes }}m)
                                                @else
                                                    Pulang Awal ({{ $attendance->late_minutes }}m)
                                                @endif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ✓ Tepat Waktu
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <a href="{{ route('attendance.show', $attendance) }}"
                                           class="text-blue-600 hover:text-blue-900">
                                            {{ __('View Details') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(isset($attendances) && method_exists($attendances, 'links'))
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $attendances->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <x-fas-clock class="mx-auto h-12 w-12 text-gray-400" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No attendance records found') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ __('Try adjusting your filter criteria or check in for the first time.') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

</x-layouts.app>
