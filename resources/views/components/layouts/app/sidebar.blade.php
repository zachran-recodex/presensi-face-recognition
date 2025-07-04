<aside :class="{ 'w-full md:w-64': sidebarOpen, 'w-0 md:w-16 hidden md:block': !sidebarOpen }"
       class="bg-sidebar text-gray-700 border-r border-gray-200 sidebar-transition overflow-hidden">
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
                    @if(auth()->user()->is_face_enrolled)
                        @if(!auth()->user()->hasCheckedInToday())
                            <x-layouts.sidebar-link href="{{ route('attendance.check-in') }}" icon='fas-right-to-bracket' :active="request()->routeIs('attendance.check-in')">Check In</x-layouts.sidebar-link>
                        @elseif(!auth()->user()->hasCheckedOutToday())
                            <x-layouts.sidebar-link href="{{ route('attendance.check-out') }}" icon='fas-right-from-bracket' :active="request()->routeIs('attendance.check-out')">Check Out</x-layouts.sidebar-link>
                        @endif
                    @endif

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

                        <!-- Face API Testing (Super Admin Only) -->
                        @if(auth()->user()->isSuperAdmin())
                            <x-layouts.sidebar-two-level-link href="{{ route('admin.face-api-test.index') }}" icon='fas-cogs' :active="request()->routeIs('admin.face-api-test.*')">Test Face API</x-layouts.sidebar-two-level-link>
                        @endif

                    </x-layouts.sidebar-two-level-link-parent>

                    <!-- Attendance -->
                    @if(auth()->user()->is_face_enrolled)
                        @if(!auth()->user()->hasCheckedInToday())
                            <x-layouts.sidebar-link href="{{ route('attendance.check-in') }}" icon='fas-right-to-bracket' :active="request()->routeIs('attendance.check-in')">Check In</x-layouts.sidebar-link>
                        @elseif(!auth()->user()->hasCheckedOutToday())
                            <x-layouts.sidebar-link href="{{ route('attendance.check-out') }}" icon='fas-right-from-bracket' :active="request()->routeIs('attendance.check-out')">Check Out</x-layouts.sidebar-link>
                        @endif
                    @endif

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
