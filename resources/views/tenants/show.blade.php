@extends('layouts.app')
@section('content')
<div class="mb-5">
    <a href="{{ route('tenants.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm">← Back to Tenants</a>
</div>

<h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">{{ $tenant->user->name }}</h1>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-800 dark:text-white">👤 Tenant Info</h2>
        </div>
        <div class="px-6 py-4 space-y-3 text-sm divide-y divide-gray-100 dark:divide-gray-700">
            <div class="flex justify-between py-1"><span class="text-gray-500 dark:text-gray-400">Email</span><span class="text-gray-800 dark:text-gray-200">{{ $tenant->user->email }}</span></div>
            <div class="flex justify-between py-1"><span class="text-gray-500 dark:text-gray-400">Phone</span><span class="text-gray-800 dark:text-gray-200">{{ $tenant->phone }}</span></div>
            <div class="flex justify-between py-1"><span class="text-gray-500 dark:text-gray-400">Move-in Date</span><span class="text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($tenant->move_in_date)->format('d M Y') }}</span></div>
            <div class="flex justify-between py-1">
                <span class="text-gray-500 dark:text-gray-400">Status</span>
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $tenant->status === 'active' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                    {{ ucfirst($tenant->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-800 dark:text-white">🏢 Property Info</h2>
        </div>
        <div class="px-6 py-4 space-y-3 text-sm divide-y divide-gray-100 dark:divide-gray-700">
            <div class="flex justify-between py-1"><span class="text-gray-500 dark:text-gray-400">Property</span><span class="text-gray-800 dark:text-gray-200">{{ $tenant->property->name }}</span></div>
            <div class="flex justify-between py-1"><span class="text-gray-500 dark:text-gray-400">Location</span><span class="text-gray-800 dark:text-gray-200">{{ $tenant->property->location }}</span></div>
            <div class="flex justify-between py-1"><span class="text-gray-500 dark:text-gray-400">Monthly Rent</span><span class="font-bold text-indigo-600 dark:text-indigo-400">KES {{ number_format($tenant->property->rent_amount, 2) }}</span></div>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
        <h2 class="text-base font-semibold text-gray-800 dark:text-white">🧾 Payment History</h2>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700/50">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Month</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Amount</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Transaction ID</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Status</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($tenant->payments as $payment)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                <td class="px-6 py-3 text-gray-800 dark:text-gray-200">{{ $payment->month_paid }}</td>
                <td class="px-6 py-3 text-gray-800 dark:text-gray-200">KES {{ number_format($payment->amount, 2) }}</td>
                <td class="px-6 py-3 font-mono text-gray-600 dark:text-gray-400">{{ $payment->mpesa_transaction_id ?? '—' }}</td>
                <td class="px-6 py-3">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $payment->status === 'completed' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-400' : ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-400' : 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400') }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </td>
                <td class="px-6 py-3 text-gray-600 dark:text-gray-400">{{ $payment->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">No payments yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

