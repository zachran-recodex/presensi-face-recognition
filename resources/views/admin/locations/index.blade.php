<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
           class="text-blue-600 hover:underline">{{ __('Dashboard') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500">{{ __('Locations Management') }}</span>
    </div>

    <!-- Page Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ __('Locations Management') }}</h1>
            <p class="text-gray-600 mt-1">
                {{ __('Manage attendance locations and their settings') }}
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.locations.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('Add New Location') }}
            </a>
        </div>
    </div>

    <!-- Locations List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @if($locations->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Location') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Address') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Coordinates') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Radius') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Status') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($locations as $location)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $location->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            ID: {{ $location->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate">
                                    {{ $location->address }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($location->latitude && $location->longitude)
                                    <div>{{ number_format($location->latitude, 6) }},</div>
                                    <div>{{ number_format($location->longitude, 6) }}</div>
                                @else
                                    <span class="text-gray-400">{{ __('Not set') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $location->radius }}m
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($location->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ __('Active') }}
                                        </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ __('Inactive') }}
                                        </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.locations.show', $location) }}"
                                       class="text-blue-600 hover:text-blue-900">
                                        {{ __('View') }}
                                    </a>
                                    <a href="{{ route('admin.locations.edit', $location) }}"
                                       class="text-indigo-600 hover:text-indigo-900">
                                        {{ __('Edit') }}
                                    </a>

                                    <!-- Toggle Status -->
                                    <form method="POST" action="{{ route('admin.locations.toggle-status', $location) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="text-yellow-600 hover:text-yellow-900">
                                            @if($location->is_active)
                                                {{ __('Deactivate') }}
                                            @else
                                                {{ __('Activate') }}
                                            @endif
                                        </button>
                                    </form>

                                    <!-- Delete -->
                                    <form method="POST" action="{{ route('admin.locations.destroy', $location) }}"
                                          class="inline"
                                          onsubmit="return confirm('{{ __('Are you sure you want to delete this location?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-900">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $locations->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No locations found') }}</h3>
                <p class="mt-1 text-sm text-gray-500">
                    {{ __('Get started by creating your first attendance location.') }}
                </p>
                <div class="mt-6">
                    <a href="{{ route('admin.locations.create') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        {{ __('Add New Location') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
