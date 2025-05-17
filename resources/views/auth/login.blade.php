<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Credenciales de acceso rápido -->
    <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 mb-6">
        <h3 class="text-sm font-medium text-purple-800 dark:text-purple-300 mb-2">Credenciales para acceso rápido:</h3>
        
        <div class="text-xs text-gray-700 dark:text-gray-300 space-y-1">
            <p><strong>Administrador:</strong> admin@example.com / password</p>
            <p><strong>Usuario 1:</strong> hiramwoki@example.com / password</p>
            <p><strong>Usuario 2:</strong> usuario1@example.com / password</p>
            <p><strong>Usuario 3:</strong> usuario2@example.com / password</p>
            <p><strong>Usuario 4:</strong> usuario3@example.com / password</p>
        </div>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-deeper-gray-700 border-gray-300 dark:border-gray-600 text-purple-600 shadow-sm focus:ring-purple-500 dark:focus:ring-purple-400" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            <div class="flex items-center">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
    
    <!-- Registro de nuevos usuarios -->
    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">¿Todavía no tienes una cuenta?</p>
        <a href="{{ route('register') }}" class="mt-2 inline-flex items-center px-4 py-2 bg-purple-600 dark:bg-purple-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 dark:hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
            Registrarse
        </a>
    </div>
</x-guest-layout>
