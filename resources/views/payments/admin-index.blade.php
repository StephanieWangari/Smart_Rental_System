@extends('layouts.app')
@section('content')

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">All Payments</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Track and manage all rent transactions</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('payments.reports') }}"
           class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all shadow-sm flex items-center gap-2">
            📈 Reports
        </a>
        <a href="{{ route('payments.create') }}"
           class="btn-primary text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg flex items-center gap-2">
            + Record Payment
        </a>
    </div>
</div>

<form method="GET" class="mb-6 flex gap-3 flex-wrap">
    <select name="status"
            class="border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-2.5 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500 text-sm">
        <option value="">All Statuses</option>
        <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
        <option value="failed"    {{ request('status') === 'failed'    ? 'selected' : '' }}>Failed</option>
    </select>
    <input type="month" name="month" value="{{ request('month') }}"
           class="border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-2.5 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500 text-sm">
    <button class="btn-primary text-white px-5 py-2.5 rounded-xl text-sm font-semibold">Filter</button>
    <a href="{{ route('admin.payments.index') }}"
       class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm transition-all">Clear</a>
</form>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/40">
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tenant</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Property</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Month</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Transaction ID</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-400 to-indigo-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($payment->tenant->user->name, 0, 1)) }}
                            </div>
                            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $payment->tenant->user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $payment->tenant->property->name }}</td>
                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">KES {{ number_format($payment->amount, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ ($payment->payment_type ?? 'full') === 'partial' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400' }}">
                            {{ ucfirst($payment->payment_type ?? 'full') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $payment->month_paid }}</td>
                    <td class="px-6 py-4 font-mono text-xs text-gray-500 dark:text-gray-400">{{ $payment->mpesa_transaction_id ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($payment->status === 'completed')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Completed
                            </span>
                        @elseif($payment->status === 'pending')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400">
                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full pulse-dot"></span> Pending
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Failed
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('payments.show', $payment) }}"
                               class="px-3 py-1.5 rounded-lg bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400 text-xs font-semibold hover:bg-violet-100 transition-all">View</a>
                            <form method="POST" action="{{ route('payments.destroy', $payment) }}" onsubmit="return confirm('Delete this payment?')">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-semibold hover:bg-red-100 transition-all">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center">
                        <div class="text-5xl mb-3">💳</div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">No payments found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-5">{{ $payments->withQueryString()->links() }}</div>
@endsection

