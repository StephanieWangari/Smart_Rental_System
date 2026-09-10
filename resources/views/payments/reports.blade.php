@extends('layouts.app')
@section('content')
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Reports & Analytics</h1>
    <a href="{{ route('payments.export', ['month' => $month]) }}"
       class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
        </svg>
        Download Excel Report
    </a>
</div>

<form method="GET" class="mb-6 flex gap-2 items-center">
    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Select Month:</label>
    <input type="month" name="month" value="{{ $month }}"
           class="border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">View</button>
</form>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow border border-gray-100 dark:border-gray-700">
        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Monthly Income ({{ $month }})</p>
        <p class="text-3xl font-bold text-green-600 dark:text-green-400">KES {{ number_format($monthlyIncome, 2) }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow border border-gray-100 dark:border-gray-700">
        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Outstanding Payments</p>
        <p class="text-3xl font-bold text-red-500 dark:text-red-400">{{ $outstanding->count() }}</p>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
        <h2 class="text-base font-semibold text-gray-800 dark:text-white">Tenants with Outstanding Rent — {{ $month }}</h2>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700/50">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tenant</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Email</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Property</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Rent Due</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($outstanding as $tenant)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                <td class="px-6 py-3 font-medium text-gray-800 dark:text-gray-200">{{ $tenant->user->name }}</td>
                <td class="px-6 py-3 text-gray-600 dark:text-gray-400">{{ $tenant->user->email }}</td>
                <td class="px-6 py-3 text-gray-600 dark:text-gray-400">{{ $tenant->property->name }}</td>
                <td class="px-6 py-3 font-bold text-red-500 dark:text-red-400">KES {{ number_format($tenant->property->rent_amount, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-8 text-center text-green-600 dark:text-green-400 font-medium">
                    🎉 All tenants have paid for {{ $month }}!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
