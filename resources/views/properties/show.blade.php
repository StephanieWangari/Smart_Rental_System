@extends('layouts.app')
@section('content')
<div class="mb-4"><a href="{{ route('properties.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm">← Back to Properties</a></div>
<h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">{{ $property->name }}</h1>
<p class="text-gray-500 dark:text-gray-400 mb-6">{{ $property->location }}</p>

<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4 shadow-sm">
        <p class="text-sm text-gray-500 dark:text-gray-400">Monthly Rent</p>
        <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400">KES {{ number_format($property->rent_amount, 2) }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4 shadow-sm">
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Status</p>
        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $property->status === 'available' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400' : 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400' }}">
            {{ ucfirst($property->status) }}
        </span>
    </div>
</div>

@if($property->description)
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4 shadow-sm mb-6">
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Description</p>
    <p class="text-gray-800 dark:text-gray-200">{{ $property->description }}</p>
</div>
@endif

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
        <h2 class="text-base font-semibold text-gray-800 dark:text-white">Tenants ({{ $property->tenants->count() }})</h2>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700/40">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Phone</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Move-in</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
            @forelse($property->tenants as $tenant)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                <td class="px-6 py-3 text-gray-800 dark:text-gray-200">{{ $tenant->user->name }}</td>
                <td class="px-6 py-3 text-gray-600 dark:text-gray-400">{{ $tenant->user->email }}</td>
                <td class="px-6 py-3 text-gray-600 dark:text-gray-400">{{ $tenant->phone }}</td>
                <td class="px-6 py-3 text-gray-600 dark:text-gray-400">{{ $tenant->move_in_date }}</td>
                <td class="px-6 py-3"><span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $tenant->status === 'active' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">{{ ucfirst($tenant->status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">No tenants assigned.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

