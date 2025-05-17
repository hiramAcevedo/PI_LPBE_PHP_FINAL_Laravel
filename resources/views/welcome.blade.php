<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Hirstagram') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
            @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            // Check for dark mode preference
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light');
            } else {
                document.documentElement.classList.add('light');
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body class="bg-gray-100 dark:bg-deep-black">
        <div class="min-h-screen flex flex-col">
            <header class="bg-white dark:bg-deeper-gray-800 shadow">
                <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8 flex justify-between items-center">
                    <h1 class="text-3xl font-bold tracking-tight bg-gradient-to-r from-purple-600 to-pink-500 bg-clip-text text-transparent dark:from-purple-400 dark:to-pink-300">
                        Hirstagram
                    </h1>
                    
                    <!-- Dark mode toggle -->
                    <button onclick="toggleDarkMode()" class="p-2 rounded-full text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-deeper-gray-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>
            </header>

            <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl w-full space-y-8">
                    <div class="bg-white dark:bg-deeper-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 md:p-8">
                        <div class="text-center mb-8">
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                                Bienvenido a Hirstagram
                            </h2>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">
                                Producto integrador. Mi aplicación construida con lenguajes de programación Backend
                            </p>
                        </div>

                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-6 mb-8">
                            <h3 class="text-lg font-medium text-purple-800 dark:text-purple-300 mb-4">Instrucciones de inicio de sesión</h3>
                            
                            <div class="space-y-4 text-gray-700 dark:text-gray-300">
                                <div>
                                    <h4 class="font-medium mb-2">Usuario Administrador:</h4>
                                    <ul class="list-disc list-inside pl-4 space-y-1">
                                        <li>Email: <span class="font-mono bg-gray-100 dark:bg-deeper-gray-700 px-2 py-1 rounded">admin@example.com</span></li>
                                        <li>Contraseña: <span class="font-mono bg-gray-100 dark:bg-deeper-gray-700 px-2 py-1 rounded">password</span></li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h4 class="font-medium mb-2">Usuario Regular:</h4>
                                    <ul class="list-disc list-inside pl-4 space-y-1">
                                        <li>Email: <span class="font-mono bg-gray-100 dark:bg-deeper-gray-700 px-2 py-1 rounded">hiramwoki@example.com</span></li>
                                        <li>Contraseña: <span class="font-mono bg-gray-100 dark:bg-deeper-gray-700 px-2 py-1 rounded">password</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row gap-6 justify-center">
            @if (Route::has('login'))
                    @auth
                                    <a href="{{ url('/dashboard') }}" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-md shadow-sm flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        Ir al Panel
                        </a>
                    @else
                                    <a href="{{ route('login') }}" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-md shadow-sm flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                        </svg>
                                        Iniciar Sesión
                        </a>

                        @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="px-6 py-3 bg-white dark:bg-deeper-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-deeper-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-md shadow-sm flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                            </svg>
                                            Registrarse
                            </a>
                        @endif
                    @endauth
            @endif
                        </div>
                </div>
                </div>
            </main>

            <footer class="bg-white dark:bg-deeper-gray-800 shadow mt-auto">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-gray-600 dark:text-gray-400 text-sm">
                    <p>Producto integrador. Mi aplicación construida con lenguajes de programación Backend</p>
                </div>
            </footer>
        </div>

        <script>
            // Toggle dark mode
            function toggleDarkMode() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    document.documentElement.classList.add('light');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.remove('light');
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }
        </script>
    </body>
</html>
