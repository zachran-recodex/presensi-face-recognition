<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
        <x-fas-chevron-right class="h-4 w-4 mx-2 text-gray-400" />
        <span class="text-gray-500">Kelola Akun</span>
    </div>

    <!-- Page Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Akun</h1>
            <p class="text-gray-600 mt-1">Mengelola pengguna website dan role mereka</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.create') }}" class="btn-primary">
                Buat Akun
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Users -->
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Seluruh Karyawan</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['total_users']) }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <x-fas-users class="h-6 w-6 text-blue-500" />
                </div>
            </div>
        </div>

        <!-- Admin Users -->
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Admin</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['admin_users']) }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <x-fas-lock class="h-6 w-6 text-green-500" />
                </div>
            </div>
        </div>

        <!-- Regular Users -->
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Karyawan</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['regular_users']) }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <x-fas-user class="h-6 w-6 text-purple-500" />
                </div>
            </div>
        </div>

        <!-- Face Enrolled -->
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Wajah Terdaftar</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['face_enrolled']) }}</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-full">
                    <x-fas-face-smile-beam class="h-6 w-6 text-orange-500" />
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <x-forms.input label="Search" name="search" type="text" id="search" value="{{ request('search') }}" placeholder="Nama, email, atau ID Karyawan..." />
            </div>

            <!-- Role Filter -->
            <div>
                <x-forms.select label="Role" name="role" id="role" :withRequest="true" :allOption="true" :options="[ 'admin' => 'Admin', 'user' => 'Karyawan' ]" />
            </div>

            <!-- Face Enrollment Filter -->
            <div>
                <x-forms.select label="Status Wajah" name="face_enrolled" id="face_enrolled" :withRequest="true" :allOption="true" :options="[ 'yes' => 'Terdaftar', 'no' => 'Tidak Terdaftar' ]" />
            </div>

            <!-- Buttons -->
            <div class="flex items-end space-x-2">
                <x-button type="primary">
                    Filter
                </x-button>
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="card p-0 overflow-hidden">
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Karyawan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status Wajah
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kehadiran
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bergabung
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-blue-500 text-blue-100 flex items-center justify-center text-sm font-medium">
                                            {{ $user->initials() }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $user->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $user->email }}
                                            </div>
                                            @if($user->employee_id)
                                                <div class="text-xs text-gray-500">
                                                    ID: {{ $user->employee_id }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $user->role === 'admin' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $user->role === 'admin' ? 'Admin' : 'Karyawan' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $user->is_face_enrolled ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $user->is_face_enrolled ? 'Terdaftar' : 'Tidak Terdaftar' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($user->attendances_count ?? 0) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <!-- View Button -->
                                        <a href="{{ route('admin.users.show', $user) }}"
                                            class="text-blue-600 hover:text-blue-700 p-1 rounded hover:bg-blue-50"
                                            title="View">
                                            <x-fas-eye class="w-5 h-5" />
                                        </a>

                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="text-indigo-600 hover:text-indigo-700 p-1 rounded hover:bg-indigo-50"
                                            title="Edit">
                                            <x-fas-edit class="w-5 h-5" />
                                        </a>

                                        @if($user->id !== auth()->id())
                                            <!-- Delete Button -->
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="cursor-pointer text-red-600 hover:text-red-700 p-1 rounded hover:bg-red-50"
                                                        title="Delete">
                                                    <x-fas-trash class="w-5 h-5" />
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No users found') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('No users match your current filters.') }}</p>
                <div class="mt-6">
                    <a href="{{ route('admin.users.create') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        {{ __('Add New User') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
