@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Admin Dashboard</h1>

<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow border border-gray-100 dark:border-gray-700">
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Income</p>
        <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">KES {{ number_format($totalIncome, 2) }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow border border-gray-100 dark:border-gray-700">
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Active Tenants</p>
        <p class="text-2xl font-bold text-green-700 dark:text-green-300 mt-1">{{ $totalTenants }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow border border-gray-100 dark:border-gray-700">
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pending Payments</p>
        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ $pendingPayments }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow border border-gray-100 dark:border-gray-700">
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Completed Payments</p>
        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $completedPayments }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow border border-gray-100 dark:border-gray-700">
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Occupied Properties</p>
        <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">{{ $occupiedProperties }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow border border-gray-100 dark:border-gray-700">
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Available Properties</p>
        <p class="text-2xl font-bold text-teal-600 dark:text-teal-400 mt-1">{{ $availableProperties }}</p>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
        <h2 class="text-base font-semibold text-gray-800 dark:text-white">Recent Payments</h2>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700/50">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tenant</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Property</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Amount</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Month</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($recentPayments as $payment)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                <td class="px-6 py-3 font-medium text-gray-800 dark:text-gray-200">{{ $payment->tenant->user->name }}</td>
                <td class="px-6 py-3 text-gray-600 dark:text-gray-400">{{ $payment->tenant->property->name }}</td>
                <td class="px-6 py-3 text-gray-800 dark:text-gray-200">KES {{ number_format($payment->amount, 2) }}</td>
                <td class="px-6 py-3 text-gray-600 dark:text-gray-400">{{ $payment->month_paid }}</td>
                <td class="px-6 py-3">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $payment->status === 'completed' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400' : ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-400' : 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400') }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">No payments yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
