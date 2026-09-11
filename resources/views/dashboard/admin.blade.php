@extends('layouts.app')
@section('content')

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Admin Dashboard</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Welcome back! Here's what's happening today.</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('tenants.create') }}"
           class="btn-primary text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-lg flex items-center gap-2">
            <span>+</span> Add Tenant
        </a>
        <a href="{{ route('properties.create') }}"
           class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all flex items-center gap-2 shadow-sm">
            🏢 Add Property
        </a>
    </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
    <div class="stat-card card-glow col-span-2 lg:col-span-1 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden"
         style="background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%)">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -translate-y-8 translate-x-8"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/10 rounded-full translate-y-6 -translate-x-6"></div>
        <p class="text-purple-200 text-xs font-semibold uppercase tracking-widest mb-2">Total Income</p>
        <p class="text-3xl font-extrabold" x-data x-init="animateCount($el, {{ $totalIncome }})">KES 0</p>
        <p class="text-purple-200 text-xs mt-2">All completed payments</p>
        <span class="absolute top-4 right-4 text-3xl opacity-30">💰</span>
    </div>

    <div class="stat-card card-glow rounded-2xl p-6 text-white shadow-xl relative overflow-hidden"
         style="background: linear-gradient(135deg, #059669 0%, #0d9488 100%)">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-6 translate-x-6"></div>
        <p class="text-emerald-100 text-xs font-semibold uppercase tracking-widest mb-2">Active Tenants</p>
        <p class="text-3xl font-extrabold" x-data x-init="animateCount($el, {{ $totalTenants }}, false)">0</p>
        <p class="text-emerald-100 text-xs mt-2">Currently renting</p>
        <span class="absolute top-4 right-4 text-3xl opacity-30">👥</span>
    </div>

    <div class="stat-card card-glow rounded-2xl p-6 text-white shadow-xl relative overflow-hidden"
         style="background: linear-gradient(135deg, #d97706 0%, #dc2626 100%)">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-6 translate-x-6"></div>
        <p class="text-orange-100 text-xs font-semibold uppercase tracking-widest mb-2">Pending Payments</p>
        <p class="text-3xl font-extrabold" x-data x-init="animateCount($el, {{ $pendingPayments }}, false)">0</p>
        <p class="text-orange-100 text-xs mt-2">Awaiting confirmation</p>
        <span class="absolute top-4 right-4 text-3xl opacity-30">⏳</span>
    </div>

    <div class="stat-card card-glow rounded-2xl p-6 text-white shadow-xl relative overflow-hidden"
         style="background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%)">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-6 translate-x-6"></div>
        <p class="text-blue-100 text-xs font-semibold uppercase tracking-widest mb-2">Completed Payments</p>
        <p class="text-3xl font-extrabold" x-data x-init="animateCount($el, {{ $completedPayments }}, false)">0</p>
        <p class="text-blue-100 text-xs mt-2">Successfully processed</p>
        <span class="absolute top-4 right-4 text-3xl opacity-30">✅</span>
    </div>

    <div class="stat-card card-glow rounded-2xl p-6 text-white shadow-xl relative overflow-hidden"
         style="background: linear-gradient(135deg, #db2777 0%, #9333ea 100%)">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-6 translate-x-6"></div>
        <p class="text-pink-100 text-xs font-semibold uppercase tracking-widest mb-2">Occupied</p>
        <p class="text-3xl font-extrabold" x-data x-init="animateCount($el, {{ $occupiedProperties }}, false)">0</p>
        <p class="text-pink-100 text-xs mt-2">Properties rented out</p>
        <span class="absolute top-4 right-4 text-3xl opacity-30">🏠</span>
    </div>

    <div class="stat-card card-glow rounded-2xl p-6 text-white shadow-xl relative overflow-hidden"
         style="background: linear-gradient(135deg, #0891b2 0%, #0d9488 100%)">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-6 translate-x-6"></div>
        <p class="text-cyan-100 text-xs font-semibold uppercase tracking-widest mb-2">Available</p>
        <p class="text-3xl font-extrabold" x-data x-init="animateCount($el, {{ $availableProperties }}, false)">0</p>
        <p class="text-cyan-100 text-xs mt-2">Ready to rent</p>
        <span class="absolute top-4 right-4 text-3xl opacity-30">🔑</span>
    </div>
</div>

{{-- Quick Actions --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
    <a href="{{ route('admin.payments.index') }}" class="group bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-100 dark:border-gray-700 hover:border-violet-300 dark:hover:border-violet-600 shadow-sm hover:shadow-md transition-all text-center">
        <div class="w-10 h-10 rounded-xl bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center text-xl mx-auto mb-2 group-hover:scale-110 transition-transform">💳</div>
        <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">All Payments</p>
    </a>
    <a href="{{ route('payments.reports') }}" class="group bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-100 dark:border-gray-700 hover:border-amber-300 dark:hover:border-amber-600 shadow-sm hover:shadow-md transition-all text-center">
        <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center text-xl mx-auto mb-2 group-hover:scale-110 transition-transform">📈</div>
        <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">Reports</p>
    </a>
    <a href="{{ route('tenants.index') }}" class="group bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-100 dark:border-gray-700 hover:border-emerald-300 dark:hover:border-emerald-600 shadow-sm hover:shadow-md transition-all text-center">
        <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-xl mx-auto mb-2 group-hover:scale-110 transition-transform">👥</div>
        <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">Tenants</p>
    </a>
    <a href="{{ route('properties.index') }}" class="group bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-100 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600 shadow-sm hover:shadow-md transition-all text-center">
        <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-xl mx-auto mb-2 group-hover:scale-110 transition-transform">🏢</div>
        <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">Properties</p>
    </a>
</div>

{{-- Recent Payments --}}
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <div>
            <h2 class="text-base font-bold text-gray-900 dark:text-white">Recent Payments</h2>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Latest 5 transactions</p>
        </div>
        <a href="{{ route('admin.payments.index') }}" class="text-xs font-semibold text-violet-600 dark:text-violet-400 hover:underline">View all →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/40">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tenant</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Property</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Month</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                @forelse($recentPayments as $payment)
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
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $payment->month_paid }}</td>
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
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">No payments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function animateCount(el, target, isCurrency = true) {
    const duration = 1200;
    const start = performance.now();
    const update = (time) => {
        const progress = Math.min((time - start) / duration, 1);
        const ease = 1 - Math.pow(1 - progress, 3);
        const value = Math.floor(ease * target);
        el.textContent = isCurrency ? 'KES ' + value.toLocaleString() : value.toLocaleString();
        if (progress < 1) requestAnimationFrame(update);
        else el.textContent = isCurrency ? 'KES ' + target.toLocaleString() : target.toLocaleString();
    };
    requestAnimationFrame(update);
}
</script>
@endsection

