<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="themeManager()" :class="{ 'dark': dark }" x-init="init()">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-green-50 to-green-100 dark:from-gray-950 dark:to-gray-900 min-h-screen transition-colors duration-200">

    {{-- Dark mode toggle --}}
    <div class="absolute top-4 right-4">
        <button @click="toggle()"
                class="flex items-center gap-2 px-3 py-2 rounded-lg bg-white dark:bg-gray-800 shadow text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border border-gray-200 dark:border-gray-700">
            <span x-text="dark ? '☀️' : '🌙'"></span>
            <span x-text="dark ? 'Light' : 'Dark'"></span>
        </button>
    </div>

    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        {{-- Logo / Brand --}}
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-600 rounded-2xl shadow-lg mb-4">
                <span class="text-3xl">🏠</span>
            </div>
            <h1 class="text-2xl font-bold text-green-800 dark:text-green-400">Smart Rental System</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your properties with ease</p>
        </div>

        {{-- Card --}}
        <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-100 dark:border-gray-700">
            {{ $slot }}
        </div>
    </div>

<script>
function themeManager() {
    return {
        dark: false,
        init() { this.dark = localStorage.getItem('theme') === 'dark'; },
        toggle() {
            this.dark = !this.dark;
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
        }
    }
}
</script>
</body>
</html>
