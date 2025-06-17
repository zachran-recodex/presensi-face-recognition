<aside :class="{ 'w-full md:w-64': sidebarOpen, 'w-0 md:w-16 hidden md:block': !sidebarOpen }"
       class="bg-sidebar text-sidebar-foreground border-r border-gray-200 dark:border-gray-700 sidebar-transition overflow-hidden">
    <!-- Sidebar Content -->
    <div class="h-full flex flex-col">
        <!-- Sidebar Menu -->
        <nav class="flex-1 overflow-y-auto custom-scrollbar py-4">
            <ul class="space-y-1 px-2">
                <!-- Dashboard -->
                <x-layouts.sidebar-link href="{{ route('dashboard') }}" icon='fas-house' :active="request()->routeIs('dashboard')">Dashboard</x-layouts.sidebar-link>

                <!-- User Menu Items -->
                @if(auth()->user()->isUser())
                    <!-- Attendance -->
                    <x-layouts.sidebar-link href="{{ route('attendance.index') }}" icon='fas-clock' :active="request()->routeIs('attendance.index')">Overview</x-layouts.sidebar-link>

                    @if(!auth()->user()->hasCheckedInToday())
                        <x-layouts.sidebar-link href="{{ route('attendance.check-in') }}" icon='fas-sign-in-alt' :active="request()->routeIs('attendance.check-in')">Check In</x-layouts.sidebar-link>
                    @elseif(!auth()->user()->hasCheckedOutToday())
                        <x-layouts.sidebar-link href="{{ route('attendance.check-out') }}" icon='fas-sign-out-alt' :active="request()->routeIs('attendance.check-out')">Check Out</x-layouts.sidebar-link>
                    @endif

                    <x-layouts.sidebar-link href="{{ route('attendance.history') }}" icon='fas-history' :active="request()->routeIs('attendance.history') || request()->routeIs('attendance.show')">Riwayat Presensi</x-layouts.sidebar-link>

                    <!-- Face Recognition -->
                    @if(!auth()->user()->is_face_enrolled)
                        <x-layouts.sidebar-link href="{{ route('face.enroll') }}" icon='fas-user-plus' :active="request()->routeIs('face.enroll')">Daftarkan Wajah</x-layouts.sidebar-link>
                    @else
                        <x-layouts.sidebar-link href="{{ route('face.edit') }}" icon='fas-user-edit' :active="request()->routeIs('face.edit')">Perbarui Wajah</x-layouts.sidebar-link>
                    @endif

                <!-- Admin Menu Items -->
                @elseif(auth()->user()->isAdmin())
                    <x-layouts.sidebar-two-level-link-parent title="Admin" icon="fas-lock" :active="request()->routeIs('admin.*')">

                        <!-- User Management -->
                        <x-layouts.sidebar-two-level-link href="{{ route('admin.users.index') }}" icon='fas-users' :active="request()->routeIs('admin.users.*')">Kelola Akun</x-layouts.sidebar-two-level-link>

                        <!-- Location Management -->
                        <x-layouts.sidebar-two-level-link href="{{ route('admin.locations.index') }}" icon='fas-map-marker-alt' :active="request()->routeIs('admin.locations.*')">Kelola Lokasi</x-layouts.sidebar-two-level-link>

                    </x-layouts.sidebar-two-level-link-parent>

                     <!-- Attendance Management -->
                    <x-layouts.sidebar-link href="{{ route('attendance.index') }}" icon='fas-clock' :active="request()->routeIs('attendance.index')">Overview</x-layouts.sidebar-link>

                    @if(!auth()->user()->hasCheckedInToday())
                        <x-layouts.sidebar-link href="{{ route('attendance.check-in') }}" icon='fas-sign-in-alt' :active="request()->routeIs('attendance.check-in')">Check In</x-layouts.sidebar-link>
                    @elseif(!auth()->user()->hasCheckedOutToday())
                        <x-layouts.sidebar-link href="{{ route('attendance.check-out') }}" icon='fas-sign-out-alt' :active="request()->routeIs('attendance.check-out')">Check Out</x-layouts.sidebar-link>
                    @endif

                    <x-layouts.sidebar-link href="{{ route('attendance.history') }}" icon='fas-history' :active="request()->routeIs('attendance.history') || request()->routeIs('attendance.show')">Riwayat Presensi</x-layouts.sidebar-link>

                    <!-- Face Recognition -->
                    @if(!auth()->user()->is_face_enrolled)
                        <x-layouts.sidebar-link href="{{ route('face.enroll') }}" icon='fas-user-plus' :active="request()->routeIs('face.enroll')">Daftarkan Wajah</x-layouts.sidebar-link>
                    @else
                        <x-layouts.sidebar-link href="{{ route('face.edit') }}" icon='fas-user-edit' :active="request()->routeIs('face.edit')">Perbarui Wajah</x-layouts.sidebar-link>
                    @endif
                @endif

                <!-- Settings (for all users) -->
                <x-layouts.sidebar-link href="{{ route('settings.profile.edit') }}" icon='fas-cog' :active="request()->routeIs('settings.*')">Pengaturan</x-layouts.sidebar-link>
            </ul>
        </nav>
    </div>
</aside>
