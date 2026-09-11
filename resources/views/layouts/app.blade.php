<!DOCTYPE html>
<html lang="en" x-data="themeManager()" :class="{ 'dark': dark }" x-init="init()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Rental System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-950 font-sans transition-colors duration-200">
<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-indigo-900 dark:bg-indigo-950 text-white flex flex-col shadow-xl">
        <div class="p-6 flex items-center gap-3 border-b border-indigo-700 dark:border-indigo-800">
            <span class="text-2xl">🏠</span>
            <span class="text-lg font-bold tracking-wide">RentalMS</span>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                <span>📊</span> Dashboard
            </a>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('properties.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('properties.*') ? 'bg-indigo-600 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                <span>🏢</span> Properties
            </a>
            <a href="{{ route('tenants.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('tenants.*') ? 'bg-indigo-600 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                <span>👥</span> Tenants
            </a>
            <a href="{{ route('payments.reports') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('payments.reports') ? 'bg-indigo-600 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                <span>📈</span> Reports
            </a>
            <a href="{{ route('admin.payments.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.payments.index') ? 'bg-indigo-600 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                <span>💳</span> Payments
            </a>
            @else
            <a href="{{ route('payments.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('payments.index') ? 'bg-indigo-600 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                <span>💳</span> Payments
            </a>
            @endif
        </nav>

        <div class="p-4 border-t border-indigo-700 dark:border-indigo-800 space-y-3">
            <button @click="toggle()"
                    class="w-full flex items-center justify-between px-3 py-2 rounded-lg bg-indigo-700 dark:bg-indigo-900 hover:bg-indigo-600 dark:hover:bg-indigo-800 text-sm text-white transition-colors">
                <span x-text="dark ? '☀️ Light Mode' : '🌙 Dark Mode'"></span>
                <span class="text-xs opacity-70" x-text="dark ? 'ON' : 'OFF'"></span>
            </button>
            <div class="px-1">
                <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                <p class="text-xs text-indigo-300 capitalize">{{ auth()->user()->role }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left text-sm text-indigo-300 hover:text-white px-1 transition-colors">
                    → Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 p-8 overflow-auto">
        @if(session('success'))
            <div class="mb-5 p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 text-blue-800 dark:text-blue-300 rounded-lg flex items-center gap-2">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-5 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-300 rounded-lg">
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>
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

