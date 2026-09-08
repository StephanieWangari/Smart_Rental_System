@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tenants</h1>
    <a href="{{ route('tenants.create') }}"
       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
        + Add Tenant
    </a>
</div>

<form method="GET" class="mb-5 flex gap-2">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..."
           class="border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 w-72 bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
    <button class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 text-sm transition-colors">Search</button>
</form>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700/50">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Name</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Email</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Phone</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Property</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Status</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($tenants as $tenant)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                <td class="px-6 py-3 font-medium text-gray-800 dark:text-gray-200">{{ $tenant->user->name }}</td>
                <td class="px-6 py-3 text-gray-600 dark:text-gray-400">{{ $tenant->user->email }}</td>
                <td class="px-6 py-3 text-gray-600 dark:text-gray-400">{{ $tenant->phone }}</td>
                <td class="px-6 py-3 text-gray-600 dark:text-gray-400">{{ $tenant->property->name }}</td>
                <td class="px-6 py-3">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $tenant->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                        {{ ucfirst($tenant->status) }}
                    </span>
                </td>
                <td class="px-6 py-3 flex gap-3">
                    <a href="{{ route('tenants.show', $tenant) }}" class="text-green-600 dark:text-green-400 hover:underline font-medium">View</a>
                    <a href="{{ route('tenants.edit', $tenant) }}" class="text-yellow-600 dark:text-yellow-400 hover:underline font-medium">Edit</a>
                    <form method="POST" action="{{ route('tenants.destroy', $tenant) }}" onsubmit="return confirm('Remove this tenant?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 dark:text-red-400 hover:underline font-medium">Remove</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">No tenants found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $tenants->withQueryString()->links() }}</div>
@endsection
