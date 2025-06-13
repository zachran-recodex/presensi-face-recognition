<x-layouts.app>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Attendance Records') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('View and manage attendance data') }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('attendances.report') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                @svg('fas-chart-bar', 'w-5 h-5 mr-2')
                {{ __('Reports') }}
            </a>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('attendances.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div>
                    <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Employee') }}</label>
                    <select name="employee_id" id="employee_id" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <option value="">{{ __('All Employees') }}</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="location_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Location') }}</label>
                    <select name="location_id" id="location_id" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <option value="">{{ __('All Locations') }}</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('From Date') }}</label>
                    <input type="date"
                           name="date_from"
                           id="date_from"
                           value="{{ request('date_from') }}"
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                </div>

                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('To Date') }}</label>
                    <input type="date"
                           name="date_to"
                           id="date_to"
                           value="{{ request('date_to') }}"
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Status') }}</label>
                    <select name="status" id="status" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <option value="">{{ __('All Status') }}</option>
                        <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>{{ __('Present') }}</option>
                        <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>{{ __('Late') }}</option>
                        <option value="half_day" {{ request('status') === 'half_day' ? 'selected' : '' }}>{{ __('Half Day') }}</option>
                        <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>{{ __('Absent') }}</option>
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <x-button type="primary">
                        @svg('fas-search', 'w-5 h-5 mr-2')
                        {{ __('Filter') }}
                    </x-button>
                    <a href="{{ route('attendances.index') }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-lg">
                        {{ __('Reset') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ __('Attendance Records') }}</h2>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Showing') }} {{ $attendances->firstItem() ?? 0 }} {{ __('to') }} {{ $attendances->lastItem() ?? 0 }} {{ __('of') }} {{ $attendances->total() }} {{ __('results') }}
                </div>
            </div>

            @if($attendances->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Employee') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Date') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Check In') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Check Out') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Location') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Working Hours') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Status') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($attendances as $attendance)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        {{ $attendance->employee->initials }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $attendance->employee->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $attendance->employee->employee_id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $attendance->date->format('d M Y') }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $attendance->date->format('l') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($attendance->check_in)
                                            <div class="flex items-center">
                                                @svg('fas-sign-in-alt', 'w-4 h-4 text-green-500 mr-2')
                                                <span class="text-sm text-gray-900 dark:text-gray-100">{{ $attendance->check_in->format('H:i') }}</span>
                                            </div>
                                            @if($attendance->face_similarity_in)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ __('Similarity') }}: {{ number_format($attendance->face_similarity_in * 100, 1) }}%
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($attendance->check_out)
                                            <div class="flex items-center">
                                                @svg('fas-sign-out-alt', 'w-4 h-4 text-blue-500 mr-2')
                                                <span class="text-sm text-gray-900 dark:text-gray-100">{{ $attendance->check_out->format('H:i') }}</span>
                                            </div>
                                            @if($attendance->face_similarity_out)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ __('Similarity') }}: {{ number_format($attendance->face_similarity_out * 100, 1) }}%
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @svg('fas-map-marker-alt', 'w-4 h-4 text-gray-400 mr-2')
                                            <span class="text-sm text-gray-900 dark:text-gray-100">{{ $attendance->location->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        @if($attendance->working_hours)
                                            {{ number_format($attendance->working_hours, 1) }} {{ __('hours') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($attendance->status === 'present') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($attendance->status === 'late') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                            @elseif($attendance->status === 'half_day') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                            @if($attendance->status === 'present')
                                                @svg('fas-check-circle', 'w-3 h-3 mr-1')
                                            @elseif($attendance->status === 'late')
                                                @svg('fas-clock', 'w-3 h-3 mr-1')
                                            @elseif($attendance->status === 'half_day')
                                                @svg('fas-clock', 'w-3 h-3 mr-1')
                                            @endif
                                            {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button onclick="showAttendanceDetail({{ $attendance->id }})"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            @svg('fas-eye', 'w-4 h-4')
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $attendances->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    @svg('fas-calendar-times', 'w-12 h-12 text-gray-400 mx-auto mb-4')
                    <p class="text-gray-500 dark:text-gray-400 mb-4">{{ __('No attendance records found') }}</p>
                    @if(request()->hasAny(['employee_id', 'location_id', 'date_from', 'date_to', 'status']))
                        <a href="{{ route('attendances.index') }}"
                           class="text-blue-600 dark:text-blue-400 hover:underline">
                            {{ __('Clear filters to see all records') }}
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Attendance Detail Modal -->
    <div id="attendance-detail-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Attendance Detail') }}</h3>
                    <button id="close-attendance-modal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        @svg('fas-times', 'w-6 h-6')
                    </button>
                </div>

                <div id="attendance-detail-content">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const attendanceData = @json($attendances->map(function($attendance) {
            return [
                'id' => $attendance->id,
                'employee' => [
                    'name' => $attendance->employee->name,
                    'employee_id' => $attendance->employee->employee_id,
                    'department' => $attendance->employee->department
                ],
                'date' => $attendance->date->format('d M Y'),
                'check_in' => $attendance->check_in ? $attendance->check_in->format('H:i:s') : null,
                'check_out' => $attendance->check_out ? $attendance->check_out->format('H:i:s') : null,
                'location' => $attendance->location->name,
                'status' => $attendance->status,
                'working_hours' => $attendance->working_hours,
                'face_similarity_in' => $attendance->face_similarity_in,
                'face_similarity_out' => $attendance->face_similarity_out,
                'check_in_latitude' => $attendance->check_in_latitude,
                'check_in_longitude' => $attendance->check_in_longitude,
                'check_out_latitude' => $attendance->check_out_latitude,
                'check_out_longitude' => $attendance->check_out_longitude,
                'notes' => $attendance->notes
            ];
        }));

        function showAttendanceDetail(attendanceId) {
            const attendance = attendanceData.find(att => att.id === attendanceId);

            if (!attendance) return;

            const statusClass = {
                'present': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                'late': 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                'half_day': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
            }[attendance.status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';

            const content = `
                <div class="space-y-6">
                    <!-- Employee Info -->
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Employee Information</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Name:</span>
                                <span class="text-gray-900 dark:text-gray-100 ml-2">${attendance.employee.name}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">ID:</span>
                                <span class="text-gray-900 dark:text-gray-100 ml-2">${attendance.employee.employee_id}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Department:</span>
                                <span class="text-gray-900 dark:text-gray-100 ml-2">${attendance.employee.department || '-'}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Location:</span>
                                <span class="text-gray-900 dark:text-gray-100 ml-2">${attendance.location}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Info -->
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Attendance Details</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Date:</span>
                                <span class="text-gray-900 dark:text-gray-100 ml-2">${attendance.date}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Status:</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${statusClass} ml-2">
                                    ${attendance.status.replace('_', ' ')}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Check In:</span>
                                <span class="text-gray-900 dark:text-gray-100 ml-2">${attendance.check_in || '-'}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Check Out:</span>
                                <span class="text-gray-900 dark:text-gray-100 ml-2">${attendance.check_out || '-'}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Working Hours:</span>
                                <span class="text-gray-900 dark:text-gray-100 ml-2">${attendance.working_hours ? attendance.working_hours.toFixed(1) + ' hours' : '-'}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Face Recognition -->
                    ${attendance.face_similarity_in || attendance.face_similarity_out ? `
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Face Recognition</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Check In Similarity:</span>
                                <span class="text-gray-900 dark:text-gray-100 ml-2">${attendance.face_similarity_in ? (attendance.face_similarity_in * 100).toFixed(1) + '%' : '-'}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Check Out Similarity:</span>
                                <span class="text-gray-900 dark:text-gray-100 ml-2">${attendance.face_similarity_out ? (attendance.face_similarity_out * 100).toFixed(1) + '%' : '-'}</span>
                            </div>
                        </div>
                    </div>
                    ` : ''}

                    <!-- GPS Coordinates -->
                    ${attendance.check_in_latitude || attendance.check_out_latitude ? `
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">GPS Coordinates</h4>
                        <div class="grid grid-cols-1 gap-4 text-sm">
                            ${attendance.check_in_latitude ? `
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Check In Location:</span>
                                <span class="text-gray-900 dark:text-gray-100 ml-2">${attendance.check_in_latitude}, ${attendance.check_in_longitude}</span>
                            </div>
                            ` : ''}
                            ${attendance.check_out_latitude ? `
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Check Out Location:</span>
                                <span class="text-gray-900 dark:text-gray-100 ml-2">${attendance.check_out_latitude}, ${attendance.check_out_longitude}</span>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                    ` : ''}

                    ${attendance.notes ? `
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Notes</h4>
                        <p class="text-sm text-gray-900 dark:text-gray-100">${attendance.notes}</p>
                    </div>
                    ` : ''}
                </div>
            `;

            document.getElementById('attendance-detail-content').innerHTML = content;
            document.getElementById('attendance-detail-modal').classList.remove('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('close-attendance-modal').addEventListener('click', function() {
                document.getElementById('attendance-detail-modal').classList.add('hidden');
            });
        });
    </script>
    @endpush
</x-layouts.app>
