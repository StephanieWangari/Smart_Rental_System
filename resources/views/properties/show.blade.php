@extends('layouts.app')
@section('content')
<div class="mb-4"><a href="{{ route('properties.index') }}" class="text-indigo-600 hover:underline">← Back to Properties</a></div>
<h1 class="text-2xl font-bold mb-2">{{ $property->name }}</h1>
<p class="text-gray-500 mb-6">{{ $property->location }}</p>

<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-lg p-4 shadow">
        <p class="text-sm text-gray-500">Monthly Rent</p>
        <p class="text-xl font-bold text-green-600">KES {{ number_format($property->rent_amount, 2) }}</p>
    </div>
    <div class="bg-white rounded-lg p-4 shadow">
        <p class="text-sm text-gray-500">Status</p>
        <span class="px-3 py-1 rounded text-sm font-medium {{ $property->status === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
            {{ ucfirst($property->status) }}
        </span>
    </div>
</div>

@if($property->description)
<div class="bg-white rounded-lg p-4 shadow mb-6">
    <p class="text-sm text-gray-500 mb-1">Description</p>
    <p>{{ $property->description }}</p>
</div>
@endif

<div class="bg-white rounded-lg shadow p-5">
    <h2 class="text-lg font-semibold mb-4">Tenants ({{ $property->tenants->count() }})</h2>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="text-left p-2">Name</th>
                <th class="text-left p-2">Email</th>
                <th class="text-left p-2">Phone</th>
                <th class="text-left p-2">Move-in</th>
                <th class="text-left p-2">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($property->tenants as $tenant)
            <tr class="border-t">
                <td class="p-2">{{ $tenant->user->name }}</td>
                <td class="p-2">{{ $tenant->user->email }}</td>
                <td class="p-2">{{ $tenant->phone }}</td>
                <td class="p-2">{{ $tenant->move_in_date }}</td>
                <td class="p-2"><span class="px-2 py-1 rounded text-xs {{ $tenant->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ ucfirst($tenant->status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="5" class="p-4 text-center text-gray-400">No tenants assigned.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
