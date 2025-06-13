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

                @role('Admin')
                    <!-- Employee Management -->
                    <x-layouts.sidebar-link href="{{ route('employees.index') }}" icon='fas-users'
                        :active="request()->routeIs('employees.*')">{{ __('Employees') }}</x-layouts.sidebar-link>

                    <!-- Location Management -->
                    <x-layouts.sidebar-link href="{{ route('locations.index') }}" icon='fas-map-marker-alt'
                        :active="request()->routeIs('locations.*')">{{ __('Locations') }}</x-layouts.sidebar-link>

                    <!-- Attendance Management -->
                    <x-layouts.sidebar-two-level-link-parent title="{{ __('Attendance') }}" icon="fas-clock"
                        :active="request()->routeIs('attendances.*')">
                        <x-layouts.sidebar-two-level-link href="{{ route('attendances.index') }}" icon='fas-list'
                            :active="request()->routeIs('attendances.index')">{{ __('Records') }}</x-layouts.sidebar-two-level-link>
                        <x-layouts.sidebar-two-level-link href="{{ route('attendances.report') }}" icon='fas-chart-bar'
                            :active="request()->routeIs('attendances.report')">{{ __('Reports') }}</x-layouts.sidebar-two-level-link>
                    </x-layouts.sidebar-two-level-link-parent>
                @else
                    <!-- Employee Attendance -->
                    <x-layouts.sidebar-link href="{{ route('attendance.employee') }}" icon='fas-clock'
                        :active="request()->routeIs('attendance.*')">{{ __('My Attendance') }}</x-layouts.sidebar-link>
                @endrole
            </ul>
        </nav>
    </div>
</aside>
