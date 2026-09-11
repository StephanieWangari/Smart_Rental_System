@extends('layouts.app')
@section('content')

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Properties</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Manage all rental properties</p>
    </div>
    <a href="{{ route('properties.create') }}"
       class="btn-primary text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg flex items-center gap-2">
        + Add Property
    </a>
</div>

<form method="GET" class="mb-6 flex gap-3">
    <div class="relative flex-1 max-w-sm">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or location..."
               class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-violet-500 text-sm">
    </div>
    <button class="btn-primary text-white px-5 py-2.5 rounded-xl text-sm font-semibold">Search</button>
    @if(request('search'))
    <a href="{{ route('properties.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm transition-all">Clear</a>
    @endif
</form>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/40">
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Property</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Location</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rent (KES)</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                @forelse($properties as $property)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-sm flex-shrink-0">🏢</div>
                            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $property->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">📍 {{ $property->location }}</td>
                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ number_format($property->rent_amount, 2) }}</td>
                    <td class="px-6 py-4">
                        @if($property->status === 'available')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Available
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Occupied
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('properties.show', $property) }}"
                               class="px-3 py-1.5 rounded-lg bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400 text-xs font-semibold hover:bg-violet-100 dark:hover:bg-violet-900/50 transition-all">View</a>
                            <a href="{{ route('properties.edit', $property) }}"
                               class="px-3 py-1.5 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-xs font-semibold hover:bg-amber-100 dark:hover:bg-amber-900/50 transition-all">Edit</a>
                            <form method="POST" action="{{ route('properties.destroy', $property) }}" onsubmit="return confirm('Delete this property?')">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-semibold hover:bg-red-100 dark:hover:bg-red-900/50 transition-all">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="text-5xl mb-3">🏢</div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">No properties found.</p>
                        <a href="{{ route('properties.create') }}" class="text-violet-600 dark:text-violet-400 text-sm hover:underline mt-1 inline-block">Add your first property →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-5">{{ $properties->withQueryString()->links() }}</div>
@endsection

