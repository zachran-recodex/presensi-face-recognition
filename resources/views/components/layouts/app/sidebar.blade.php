<aside :class="{ 'w-full md:w-64': sidebarOpen, 'w-0 md:w-16 hidden md:block': !sidebarOpen }"
       class="bg-sidebar text-sidebar-foreground border-r border-gray-200 dark:border-gray-700 sidebar-transition overflow-hidden">
    <!-- Sidebar Content -->
    <div class="h-full flex flex-col">
        <!-- Sidebar Menu -->
        <nav class="flex-1 overflow-y-auto custom-scrollbar py-4">
            <ul class="space-y-1 px-2">
                <!-- Dashboard -->
                <x-layouts.sidebar-link href="{{ route('dashboard') }}" icon='fas-house'
                                        :active="request()->routeIs('dashboard*')">Dashboard</x-layouts.sidebar-link>

                @if(auth()->user()->isUser())
                    <!-- User Menu Items -->

                    <!-- Attendance -->
                    <x-layouts.sidebar-two-level-link-parent title="Attendance" icon="fas-clock"
                                                             :active="request()->routeIs('attendance.*') || request()->routeIs('face.*')">
                        <x-layouts.sidebar-two-level-link href="{{ route('attendance.index') }}" icon='fas-clock'
                                                          :active="request()->routeIs('attendance.index')">Overview</x-layouts.sidebar-two-level-link>

                        @if(!auth()->user()->hasCheckedInToday())
                            <x-layouts.sidebar-two-level-link href="{{ route('attendance.check-in') }}" icon='fas-sign-in-alt'
                                                              :active="request()->routeIs('attendance.check-in')">Check In</x-layouts.sidebar-two-level-link>
                        @elseif(!auth()->user()->hasCheckedOutToday())
                            <x-layouts.sidebar-two-level-link href="{{ route('attendance.check-out') }}" icon='fas-sign-out-alt'
                                                              :active="request()->routeIs('attendance.check-out')">Check Out</x-layouts.sidebar-two-level-link>
                        @endif

                        <x-layouts.sidebar-two-level-link href="{{ route('attendance.history') }}" icon='fas-history'
                                                          :active="request()->routeIs('attendance.history') || request()->routeIs('attendance.show')">History</x-layouts.sidebar-two-level-link>
                    </x-layouts.sidebar-two-level-link-parent>

                    <!-- Face Recognition -->
                    <x-layouts.sidebar-two-level-link-parent title="Face Recognition" icon="fas-user-check"
                                                             :active="request()->routeIs('face.*')">
                        @if(!auth()->user()->is_face_enrolled)
                            <x-layouts.sidebar-two-level-link href="{{ route('face.enroll') }}" icon='fas-user-plus'
                                                              :active="request()->routeIs('face.enroll')">Enroll Face</x-layouts.sidebar-two-level-link>
                        @else
                            <x-layouts.sidebar-two-level-link href="{{ route('face.edit') }}" icon='fas-user-edit'
                                                              :active="request()->routeIs('face.edit')">Update Face</x-layouts.sidebar-two-level-link>
                        @endif
                    </x-layouts.sidebar-two-level-link-parent>

                @elseif(auth()->user()->isAdmin())
                    <!-- Admin Menu Items -->

                    <!-- Location Management -->
                    <x-layouts.sidebar-two-level-link-parent title="Locations" icon="fas-map-marker-alt"
                                                             :active="request()->routeIs('admin.locations.*')">
                        <x-layouts.sidebar-two-level-link href="{{ route('admin.locations.index') }}" icon='fas-list'
                                                          :active="request()->routeIs('admin.locations.index') || request()->routeIs('admin.locations.show')">All Locations</x-layouts.sidebar-two-level-link>
                        <x-layouts.sidebar-two-level-link href="{{ route('admin.locations.create') }}" icon='fas-plus'
                                                          :active="request()->routeIs('admin.locations.create')">Add Location</x-layouts.sidebar-two-level-link>
                    </x-layouts.sidebar-two-level-link-parent>

                    <!-- Attendance Management -->
                    <x-layouts.sidebar-two-level-link-parent title="Attendance Management" icon="fas-users-clock"
                                                             :active="request()->routeIs('admin.attendance.*') || request()->routeIs('attendance.show')">
                        <x-layouts.sidebar-two-level-link href="{{ route('attendance.index') }}" icon='fas-tachometer-alt'
                                                          :active="request()->routeIs('attendance.index')">Dashboard</x-layouts.sidebar-two-level-link>
                        <x-layouts.sidebar-two-level-link href="{{ route('admin.attendance.history') }}" icon='fas-history'
                                                          :active="request()->routeIs('admin.attendance.history') || request()->routeIs('attendance.show')">All Records</x-layouts.sidebar-two-level-link>
                    </x-layouts.sidebar-two-level-link-parent>

                    <!-- User Management -->
                    <x-layouts.sidebar-link href="#" icon='fas-users'
                                            :active="false">User Management</x-layouts.sidebar-link>

                    <!-- Reports -->
                    <x-layouts.sidebar-link href="#" icon='fas-chart-bar'
                                            :active="false">Reports</x-layouts.sidebar-link>
                @endif

                <!-- Settings (for all users) -->
                <x-layouts.sidebar-two-level-link-parent title="Settings" icon="fas-cog"
                                                         :active="request()->routeIs('settings.*')">
                    <x-layouts.sidebar-two-level-link href="{{ route('settings.profile.edit') }}" icon='fas-user'
                                                      :active="request()->routeIs('settings.profile.*')">Profile</x-layouts.sidebar-two-level-link>
                    <x-layouts.sidebar-two-level-link href="{{ route('settings.password.edit') }}" icon='fas-key'
                                                      :active="request()->routeIs('settings.password.*')">Password</x-layouts.sidebar-two-level-link>
                    <x-layouts.sidebar-two-level-link href="{{ route('settings.appearance.edit') }}" icon='fas-palette'
                                                      :active="request()->routeIs('settings.appearance.*')">Appearance</x-layouts.sidebar-two-level-link>
                </x-layouts.sidebar-two-level-link-parent>
            </ul>
        </nav>
    </div>
</aside>
