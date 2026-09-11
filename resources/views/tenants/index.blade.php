@extends('layouts.app')
@section('content')

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Tenants</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Manage all registered tenants</p>
    </div>
    <a href="{{ route('tenants.create') }}"
       class="btn-primary text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg flex items-center gap-2">
        + Add Tenant
    </a>
</div>

<form method="GET" class="mb-6 flex gap-3">
    <div class="relative flex-1 max-w-sm">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..."
               class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-violet-500 text-sm">
    </div>
    <button class="btn-primary text-white px-5 py-2.5 rounded-xl text-sm font-semibold">Search</button>
    @if(request('search'))
    <a href="{{ route('tenants.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm transition-all">Clear</a>
    @endif
</form>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/40">
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tenant</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Phone</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Property</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                @forelse($tenants as $tenant)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                {{ strtoupper(substr($tenant->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $tenant->user->name }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $tenant->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $tenant->phone }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 text-gray-700 dark:text-gray-300 font-medium">
                            🏢 {{ $tenant->property->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($tenant->status === 'active')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full pulse-dot"></span> Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('tenants.show', $tenant) }}"
                               class="px-3 py-1.5 rounded-lg bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400 text-xs font-semibold hover:bg-violet-100 dark:hover:bg-violet-900/50 transition-all">View</a>
                            <a href="{{ route('tenants.edit', $tenant) }}"
                               class="px-3 py-1.5 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-xs font-semibold hover:bg-amber-100 dark:hover:bg-amber-900/50 transition-all">Edit</a>
                            <form method="POST" action="{{ route('tenants.destroy', $tenant) }}" onsubmit="return confirm('Remove this tenant?')">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-semibold hover:bg-red-100 dark:hover:bg-red-900/50 transition-all">Remove</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="text-5xl mb-3">👥</div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">No tenants found.</p>
                        <a href="{{ route('tenants.create') }}" class="text-violet-600 dark:text-violet-400 text-sm hover:underline mt-1 inline-block">Add your first tenant →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-5">{{ $tenants->withQueryString()->links() }}</div>
@endsection

