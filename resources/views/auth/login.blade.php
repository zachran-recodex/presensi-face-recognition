<x-layouts.auth :title="__('Login')">
    <!-- Login Card -->
    <div
        class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        <div class="p-6">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Login</h1>
                <p class="text-gray-600 mt-1">Silakan masukkan kredensial Anda</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Username Input -->
                <div class="mb-4">
                    <x-forms.input label="Username" name="username" type="text" placeholder="username" />
                </div>

                <!-- Password Input -->
                <div class="mb-4">
                    <x-forms.input label="Password" name="password" type="password" placeholder="••••••••" />
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-xs text-blue-600 hover:underline">Lupa password?</a>
                    @endif
                </div>

                <!-- Remember Me -->
                <div class="mb-6">
                    <x-forms.checkbox label="Remember me" name="remember" />
                </div>

                <!-- Login Button -->
                <x-button type="primary" class="w-full">Login</x-button>
            </form>

        </div>
    </div>
</x-layouts.auth>
