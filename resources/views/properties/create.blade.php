@extends('layouts.app')
@section('content')
<div class="max-w-xl">
    <h1 class="text-2xl font-bold mb-6">Add Property</h1>
    <form method="POST" action="{{ route('properties.store') }}" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="mt-1 w-full border rounded px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Location</label>
            <input type="text" name="location" value="{{ old('location') }}" class="mt-1 w-full border rounded px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Monthly Rent (KES)</label>
            <input type="number" name="rent_amount" value="{{ old('rent_amount') }}" step="0.01" class="mt-1 w-full border rounded px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="3" class="mt-1 w-full border rounded px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">{{ old('description') }}</textarea>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded hover:bg-indigo-700">Save</button>
            <a href="{{ route('properties.index') }}" class="px-5 py-2 rounded border hover:bg-gray-50">Cancel</a>
        </div>
    </form>
</div>
@endsection
