@extends('layouts.app')
@section('content')

@if($tenant)

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">
            Hey, {{ explode(' ', auth()->user()->name)[0] }} 👋
        </h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Here's your tenancy overview for {{ now()->format('F Y') }}</p>
    </div>
    <a href="{{ route('payments.create') }}"
       class="btn-primary text-white px-5 py-3 rounded-xl text-sm font-bold shadow-xl flex items-center gap-2">
        💳 Pay Rent Now
    </a>
</div>

{{-- This month banner --}}
@if($currentMonthPayment)
<div class="mb-6 rounded-2xl p-5 text-white shadow-lg flex items-center justify-between"
     style="background: linear-gradient(135deg, #059669 0%, #0d9488 100%)">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-2xl">✅</div>
        <div>
            <p class="font-bold text-lg">Rent Paid for {{ now()->format('F Y') }}</p>
            <p class="text-emerald-100 text-sm">KES {{ number_format($currentMonthPayment->amount, 2) }} — You're all good!</p>
        </div>
    </div>
    <a href="{{ route('payments.show', $currentMonthPayment) }}" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-xl text-sm font-semibold transition-all">View Receipt</a>
</div>
@else
<div class="mb-6 rounded-2xl p-5 text-white shadow-lg flex items-center justify-between"
     style="background: linear-gradient(135deg, #dc2626 0%, #db2777 100%)">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-2xl">⚠️</div>
        <div>
            <p class="font-bold text-lg">Rent Due for {{ now()->format('F Y') }}</p>
            <p class="text-red-100 text-sm">KES {{ number_format($tenant->property->rent_amount, 2) }} — Please pay before month end</p>
        </div>
    </div>
    <a href="{{ route('payments.create') }}" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-xl text-sm font-semibold transition-all">Pay Now →</a>
</div>
@endif

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="stat-card card-glow rounded-2xl p-5 text-white shadow-lg relative overflow-hidden"
         style="background: linear-gradient(135deg, #7c3aed, #4f46e5)">
        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -translate-y-4 translate-x-4"></div>
        <p class="text-purple-200 text-xs font-semibold uppercase tracking-widest mb-1">Property</p>
        <p class="text-base font-bold leading-tight">{{ $tenant->property->name }}</p>
        <p class="text-purple-200 text-xs mt-1">{{ $tenant->property->location }}</p>
    </div>
    <div class="stat-card card-glow rounded-2xl p-5 text-white shadow-lg relative overflow-hidden"
         style="background: linear-gradient(135deg, #0891b2, #2563eb)">
        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -translate-y-4 translate-x-4"></div>
        <p class="text-blue-100 text-xs font-semibold uppercase tracking-widest mb-1">Monthly Rent</p>
        <p class="text-xl font-extrabold">KES {{ number_format($tenant->property->rent_amount, 0) }}</p>
        <p class="text-blue-100 text-xs mt-1">Due every month</p>
    </div>
    <div class="stat-card card-glow rounded-2xl p-5 text-white shadow-lg relative overflow-hidden"
         style="background: linear-gradient(135deg, #059669, #0d9488)">
        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -translate-y-4 translate-x-4"></div>
        <p class="text-emerald-100 text-xs font-semibold uppercase tracking-widest mb-1">Total Paid</p>
        <p class="text-xl font-extrabold">KES {{ number_format($totalPaid, 0) }}</p>
        <p class="text-emerald-100 text-xs mt-1">All time</p>
    </div>
    <div class="stat-card card-glow rounded-2xl p-5 text-white shadow-lg relative overflow-hidden"
         style="background: linear-gradient(135deg, #d97706, #dc2626)">
        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -translate-y-4 translate-x-4"></div>
        <p class="text-orange-100 text-xs font-semibold uppercase tracking-widest mb-1">Months Paid</p>
        <p class="text-3xl font-extrabold">{{ $monthsPaid }}</p>
        <p class="text-orange-100 text-xs mt-1">Completed payments</p>
    </div>
</div>

{{-- Details + Plan --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center">👤</div>
            <h2 class="font-bold text-gray-900 dark:text-white">My Details</h2>
        </div>
        <div class="px-6 py-4 space-y-3 text-sm">
            @foreach([['Full Name', auth()->user()->name], ['Email', auth()->user()->email], ['M-Pesa Number', $tenant->phone], ['Move-in Date', \Carbon\Carbon::parse($tenant->move_in_date)->format('d M Y')]] as [$label, $value])
            <div class="flex justify-between items-center py-1.5 border-b border-gray-50 dark:border-gray-700/50 last:border-0">
                <span class="text-gray-400 dark:text-gray-500">{{ $label }}</span>
                <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $value }}</span>
            </div>
            @endforeach
            <div class="flex justify-between items-center py-1.5">
                <span class="text-gray-400 dark:text-gray-500">Status</span>
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $tenant->status === 'active' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                    {{ ucfirst($tenant->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center">📋</div>
            <h2 class="font-bold text-gray-900 dark:text-white">Payment Plan</h2>
        </div>
        <div class="px-6 py-4 space-y-3 text-sm">
            @foreach([['Property', $tenant->property->name], ['Location', $tenant->property->location], ['Payment Method', 'M-Pesa ('.$tenant->phone.')']] as [$label, $value])
            <div class="flex justify-between items-center py-1.5 border-b border-gray-50 dark:border-gray-700/50">
                <span class="text-gray-400 dark:text-gray-500">{{ $label }}</span>
                <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $value }}</span>
            </div>
            @endforeach
            <div class="flex justify-between items-center py-1.5 border-b border-gray-50 dark:border-gray-700/50">
                <span class="text-gray-400 dark:text-gray-500">Monthly Rent</span>
                <span class="font-extrabold text-violet-600 dark:text-violet-400">KES {{ number_format($tenant->property->rent_amount, 2) }}</span>
            </div>
            <div class="flex justify-between items-center py-1.5">
                <span class="text-gray-400 dark:text-gray-500">Total Paid (All Time)</span>
                <span class="font-extrabold text-emerald-600 dark:text-emerald-400">KES {{ number_format($totalPaid, 2) }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Payment History --}}
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-pink-100 dark:bg-pink-900/40 flex items-center justify-center">🧾</div>
            <div>
                <h2 class="font-bold text-gray-900 dark:text-white">Payment History</h2>
                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $payments->count() }} record(s)</p>
            </div>
        </div>
        <a href="{{ route('payments.index') }}" class="text-xs font-semibold text-violet-600 dark:text-violet-400 hover:underline">View all →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/40">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Month</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Transaction ID</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Receipt</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <td class="px-6 py-4 font-semibold text-gray-800 dark:text-gray-200">{{ $payment->month_paid }}</td>
                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">KES {{ number_format($payment->amount, 2) }}</td>
                    <td class="px-6 py-4 font-mono text-xs text-gray-500 dark:text-gray-400">{{ $payment->mpesa_transaction_id ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $payment->created_at->format('d M Y') }}</td>
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
                        <a href="{{ route('payments.show', $payment) }}"
                           class="inline-flex items-center gap-1 text-xs font-semibold text-violet-600 dark:text-violet-400 hover:underline">
                            View →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="text-4xl mb-3">💳</div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">No payments yet.</p>
                        <a href="{{ route('payments.create') }}" class="text-violet-600 dark:text-violet-400 text-sm hover:underline mt-1 inline-block">Make your first payment →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@else
<div class="rounded-2xl p-8 text-center" style="background: linear-gradient(135deg, #fef3c7, #fde68a)">
    <div class="text-5xl mb-4">🏠</div>
    <h2 class="text-xl font-bold text-amber-900 mb-2">No Property Assigned Yet</h2>
    <p class="text-amber-700">Your account hasn't been linked to a property. Please contact the admin.</p>
</div>
@endif

@endsection

