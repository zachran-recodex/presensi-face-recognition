<x-layouts.app>
    <div class="min-h-96 flex items-center justify-center">
        <div class="max-w-md w-full">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 dark:bg-yellow-900 mb-4">
                    @svg('fas-exclamation-triangle', 'h-8 w-8 text-yellow-600 dark:text-yellow-400')
                </div>

                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                    {{ __('Employee Record Not Found') }}
                </h1>

                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    {{ __('Your user account is not linked to an employee record in our attendance system.') }}
                </p>

                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Your Account Details') }}</h3>
                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <div>{{ __('Username') }}: {{ Auth::user()->username }}</div>
                        <div>{{ __('Email') }}: {{ Auth::user()->email }}</div>
                        <div>{{ __('Name') }}: {{ Auth::user()->name }}</div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 p-4 text-left">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                @svg('fas-info-circle', 'h-5 w-5 text-blue-500')
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">{{ __('What to do next?') }}</h3>
                                <ul class="mt-2 text-sm text-blue-700 dark:text-blue-300 list-disc list-inside space-y-1">
                                    <li>{{ __('Contact your HR department') }}</li>
                                    <li>{{ __('Request employee profile creation') }}</li>
                                    <li>{{ __('Provide your username and email above') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('dashboard') }}"
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center">
                        @svg('fas-arrow-left', 'w-5 h-5 mr-2')
                        {{ __('Back to Dashboard') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
