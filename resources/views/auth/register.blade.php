<x-layouts.auth>
    <div
        class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        <div class="p-6">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">{{ __('Register') }}</h1>
                <p class="text-gray-600 mt-1">
                    {{ __('Create your attendance account') }}
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <!-- Full Name Input -->
                <div class="mb-4">
                    <x-forms.input label="Full Name" name="name" type="text" placeholder="{{ __('Full Name') }}" />
                </div>

                <!-- Email Input -->
                <div class="mb-4">
                    <x-forms.input label="Email" name="email" type="email" placeholder="your@email.com" />
                </div>

                <!-- Employee ID Input -->
                <div class="mb-4">
                    <x-forms.input label="Employee ID (Optional)" name="employee_id" type="text" placeholder="EMP001" />
                    <p class="mt-1 text-xs text-gray-500">{{ __('Leave empty to auto-generate') }}</p>
                </div>

                <!-- Phone Input -->
                <div class="mb-4">
                    <x-forms.input label="Phone Number (Optional)" name="phone" type="tel" placeholder="+62812345678" />
                </div>

                <!-- Password Input -->
                <div class="mb-4">
                    <x-forms.input label="Password" name="password" type="password" placeholder="••••••••" />
                </div>

                <!-- Confirm Password Input -->
                <div class="mb-4">
                    <x-forms.input label="Confirm Password" name="password_confirmation" type="password"
                                   placeholder="••••••••" />
                </div>

                <!-- Register Button -->
                <x-button type="primary" class="w-full">{{ __('Create Account') }}</x-button>
            </form>

            <!-- Login Link -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}"
                       class="text-blue-600 hover:underline font-medium">{{ __('Sign in') }}</a>
                </p>
            </div>
        </div>
    </div>
</x-layouts.auth>
