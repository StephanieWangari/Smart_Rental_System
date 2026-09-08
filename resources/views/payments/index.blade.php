@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Payments</h1>
    @if(!auth()->user()->isAdmin())
    <a href="{{ route('payments.create') }}"
       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
        Pay Rent
    </a>
    @endif
</div>

<form method="GET" class="mb-5 flex gap-2 flex-wrap">
    <select name="status"
            class="border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
        <option value="">All Statuses</option>
        <option value="pending"   {{ request('status') === 'pending'    ? 'selected' : '' }}>Pending</option>
        <option value="completed" {{ request('status') === 'completed'  ? 'selected' : '' }}>Completed</option>
        <option value="failed"    {{ request('status') === 'failed'     ? 'selected' : '' }}>Failed</option>
    </select>
    <input type="month" name="month" value="{{ request('month') }}"
           class="border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
    <button class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 text-sm transition-colors">Filter</button>
    <a href="{{ route('payments.index') }}"
       class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm transition-colors">Clear</a>
</form>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700/50">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tenant</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Property</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Amount</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Month</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Transaction ID</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Status</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($payments as $payment)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                <td class="px-6 py-3 font-medium text-gray-800 dark:text-gray-200">{{ $payment->tenant->user->name }}</td>
                <td class="px-6 py-3 text-gray-600 dark:text-gray-400">{{ $payment->tenant->property->name }}</td>
                <td class="px-6 py-3 text-gray-800 dark:text-gray-200">KES {{ number_format($payment->amount, 2) }}</td>
                <td class="px-6 py-3 text-gray-600 dark:text-gray-400">{{ $payment->month_paid }}</td>
                <td class="px-6 py-3 text-gray-600 dark:text-gray-400">{{ $payment->mpesa_transaction_id ?? '-' }}</td>
                <td class="px-6 py-3">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $payment->status === 'completed' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400' : ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-400' : 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400') }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </td>
                <td class="px-6 py-3 flex gap-3">
                    <a href="{{ route('payments.show', $payment) }}" class="text-green-600 dark:text-green-400 hover:underline font-medium">View</a>
                    @if(auth()->user()->isAdmin())
                    <form method="POST" action="{{ route('payments.destroy', $payment) }}" onsubmit="return confirm('Delete this payment?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 dark:text-red-400 hover:underline font-medium">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">No payments found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $payments->withQueryString()->links() }}</div>
@endsection
