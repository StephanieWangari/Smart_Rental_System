@extends('layouts.app')
@section('content')
<div class="max-w-xl">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Register Tenant</h1>
    <form method="POST" action="{{ route('tenants.store') }}"
          class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 p-6 space-y-5">
        @csrf

        @foreach([
            ['label' => 'Full Name',    'name' => 'name',     'type' => 'text',     'placeholder' => 'John Doe'],
            ['label' => 'Email',        'name' => 'email',    'type' => 'email',    'placeholder' => 'john@example.com'],
            ['label' => 'Password',     'name' => 'password', 'type' => 'password', 'placeholder' => 'Min. 8 characters'],
            ['label' => 'Phone Number', 'name' => 'phone',    'type' => 'text',     'placeholder' => '0712345678'],
        ] as $field)
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $field['label'] }}</label>
            <input type="{{ $field['type'] }}" name="{{ $field['name'] }}"
                   value="{{ $field['type'] !== 'password' ? old($field['name']) : '' }}"
                   placeholder="{{ $field['placeholder'] }}"
                   class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm"
                   {{ in_array($field['name'], ['name','email','phone']) ? 'required' : 'required' }}>
        </div>
        @endforeach

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assign Property</label>
            <select name="property_id"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm" required>
                <option value="">-- Select Property --</option>
                @foreach($properties as $property)
                <option value="{{ $property->id }}" {{ old('property_id') == $property->id ? 'selected' : '' }}>
                    {{ $property->name }} — {{ $property->location }} (KES {{ number_format($property->rent_amount, 2) }})
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Move-in Date</label>
            <input type="date" name="move_in_date" value="{{ old('move_in_date') }}"
                   class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm" required>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium text-sm transition-colors">
                Register Tenant
            </button>
            <a href="{{ route('tenants.index') }}"
               class="px-6 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

