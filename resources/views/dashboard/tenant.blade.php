@extends('layouts.app')
@section('content')

@if($tenant)

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Welcome back, {{ auth()->user()->name }} 👋</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Here's an overview of your tenancy</p>
    </div>
    <a href="{{ route('payments.create') }}"
       class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors shadow">
        💳 Pay Rent
    </a>
</div>

{{-- Top Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow border border-gray-100 dark:border-gray-700">
        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Property</p>
        <p class="text-lg font-bold text-green-700 dark:text-green-400">{{ $tenant->property->name }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $tenant->property->location }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow border border-gray-100 dark:border-gray-700">
        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Monthly Rent</p>
        <p class="text-lg font-bold text-green-600 dark:text-green-400">KES {{ number_format($tenant->property->rent_amount, 2) }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Due every month</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow border border-gray-100 dark:border-gray-700">
        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">M-Pesa Number</p>
        <p class="text-lg font-bold text-gray-800 dark:text-white">{{ $tenant->phone }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Used for payments</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow border border-gray-100 dark:border-gray-700">
        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">This Month</p>
        @if($currentMonthPayment)
            <p class="text-lg font-bold text-green-600 dark:text-green-400">✅ Paid</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">KES {{ number_format($currentMonthPayment->amount, 2) }}</p>
        @else
            <p class="text-lg font-bold text-red-500 dark:text-red-400">⚠️ Unpaid</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ now()->format('F Y') }}</p>
        @endif
    </div>
</div>

{{-- Profile & Payment Plan --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    {{-- My Details --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-800 dark:text-white">👤 My Details</h2>
        </div>
        <div class="px-6 py-4 space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">Full Name</span>
                <span class="font-medium text-gray-800 dark:text-gray-200">{{ auth()->user()->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">Email</span>
                <span class="font-medium text-gray-800 dark:text-gray-200">{{ auth()->user()->email }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">Phone / M-Pesa</span>
                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $tenant->phone }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">Move-in Date</span>
                <span class="font-medium text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($tenant->move_in_date)->format('d M Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">Status</span>
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $tenant->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                    {{ ucfirst($tenant->status) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Payment Plan --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-800 dark:text-white">📋 Payment Plan</h2>
        </div>
        <div class="px-6 py-4 space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">Property</span>
                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $tenant->property->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">Location</span>
                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $tenant->property->location }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">Monthly Rent</span>
                <span class="font-bold text-green-600 dark:text-green-400">KES {{ number_format($tenant->property->rent_amount, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">Payment Method</span>
                <span class="font-medium text-gray-800 dark:text-gray-200">M-Pesa ({{ $tenant->phone }})</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">Total Paid (All Time)</span>
                <span class="font-bold text-green-600 dark:text-green-400">KES {{ number_format($totalPaid, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">Months Paid</span>
                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $monthsPaid }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Payment History --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
        <h2 class="text-base font-semibold text-gray-800 dark:text-white">🧾 Payment History</h2>
        <span class="text-xs text-gray-400 dark:text-gray-500">{{ $payments->count() }} record(s)</span>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700/50">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Month</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Amount</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">M-Pesa No.</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Transaction ID</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Date</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Status</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Receipt</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($payments as $payment)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                <td class="px-6 py-3 font-medium text-gray-800 dark:text-gray-200">{{ $payment->month_paid }}</td>
                <td class="px-6 py-3 text-gray-800 dark:text-gray-200">KES {{ number_format($payment->amount, 2) }}</td>
                <td class="px-6 py-3 text-gray-600 dark:text-gray-400">{{ $payment->phone_number ?? $tenant->phone }}</td>
                <td class="px-6 py-3 text-gray-600 dark:text-gray-400">
                    {{ $payment->mpesa_transaction_id ?? '—' }}
                </td>
                <td class="px-6 py-3 text-gray-600 dark:text-gray-400">{{ $payment->created_at->format('d M Y') }}</td>
                <td class="px-6 py-3">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $payment->status === 'completed' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400' : ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-400' : 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400') }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </td>
                <td class="px-6 py-3">
                    <a href="{{ route('payments.show', $payment) }}"
                       class="text-green-600 dark:text-green-400 hover:underline font-medium text-xs">
                        View Receipt
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-10 text-center text-gray-400 dark:text-gray-500">
                    No payments yet. <a href="{{ route('payments.create') }}" class="text-green-600 dark:text-green-400 hover:underline">Make your first payment →</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@else
<div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-xl p-6 text-yellow-800 dark:text-yellow-300">
    ⚠️ Your account has not been assigned to a property yet. Please contact the admin.
</div>
@endif

@endsection
