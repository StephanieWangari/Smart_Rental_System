@extends('layouts.app')
@section('content')

<div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Reports & Analytics</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Financial overview for {{ $month }}</p>
    </div>
    <a href="{{ route('payments.export', ['month' => $month]) }}"
       class="btn-primary text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
        </svg>
        Export Excel
    </a>
</div>

<form method="GET" class="mb-8 flex gap-3 items-center">
    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Select Month:</label>
    <input type="month" name="month" value="{{ $month }}"
           class="border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-2.5 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500 text-sm">
    <button class="btn-primary text-white px-5 py-2.5 rounded-xl text-sm font-semibold">View</button>
</form>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
    <div class="stat-card rounded-2xl p-7 text-white shadow-xl relative overflow-hidden"
         style="background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%)">
        <div class="absolute top-0 right-0 w-28 h-28 bg-white/10 rounded-full -translate-y-10 translate-x-10"></div>
        <p class="text-purple-200 text-xs font-semibold uppercase tracking-widest mb-2">Monthly Income</p>
        <p class="text-4xl font-extrabold">KES {{ number_format($monthlyIncome, 2) }}</p>
        <p class="text-purple-200 text-sm mt-2">Completed payments for {{ $month }}</p>
        <span class="absolute bottom-4 right-6 text-5xl opacity-20">💰</span>
    </div>
    <div class="stat-card rounded-2xl p-7 text-white shadow-xl relative overflow-hidden"
         style="background: linear-gradient(135deg, #dc2626 0%, #db2777 100%)">
        <div class="absolute top-0 right-0 w-28 h-28 bg-white/10 rounded-full -translate-y-10 translate-x-10"></div>
        <p class="text-red-100 text-xs font-semibold uppercase tracking-widest mb-2">Outstanding Payments</p>
        <p class="text-4xl font-extrabold">{{ $outstanding->count() }}</p>
        <p class="text-red-100 text-sm mt-2">Tenants yet to pay for {{ $month }}</p>
        <span class="absolute bottom-4 right-6 text-5xl opacity-20">⚠️</span>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/40 flex items-center justify-center">⚠️</div>
        <div>
            <h2 class="font-bold text-gray-900 dark:text-white">Outstanding Rent — {{ $month }}</h2>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Tenants who haven't paid this month</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/40">
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tenant</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Property</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rent Due</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                @forelse($outstanding as $tenant)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-400 to-pink-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($tenant->user->name, 0, 1)) }}
                            </div>
                            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $tenant->user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $tenant->user->email }}</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $tenant->property->name }}</td>
                    <td class="px-6 py-4">
                        <span class="font-extrabold text-red-600 dark:text-red-400">KES {{ number_format($tenant->property->rent_amount, 2) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center">
                        <div class="text-5xl mb-3">🎉</div>
                        <p class="font-bold text-emerald-600 dark:text-emerald-400 text-lg">All tenants have paid for {{ $month }}!</p>
                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Great collection rate this month.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

