@extends('layouts.app')
@section('content')
<div class="max-w-lg">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Pay Rent</h1>

    <form method="POST" action="{{ route('payments.store') }}"
          class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 p-6 space-y-5">
        @csrf

        @if(auth()->user()->isAdmin())
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Tenant</label>
            <select name="tenant_id" id="tenantSelect"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 text-sm" required>
                <option value="">-- Select Tenant --</option>
                @foreach($tenants as $t)
                <option value="{{ $t->id }}" data-rent="{{ $t->property->rent_amount }}">
                    {{ $t->user->name }} — {{ $t->property->name }}
                </option>
                @endforeach
            </select>
        </div>
        @else
        <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4 space-y-1">
            <p class="text-xs font-semibold text-green-700 dark:text-green-400 uppercase tracking-wide">Your Property</p>
            <p class="font-bold text-gray-800 dark:text-white text-base">{{ $tenant->property->name }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $tenant->property->location }}</p>
            <p class="text-xl font-bold text-green-600 dark:text-green-400 mt-1" id="rentDisplay">
                KES {{ number_format($tenant->property->rent_amount, 2) }}
            </p>
        </div>
        @endif

        {{-- Payment Type Toggle --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Type</label>
            <div class="flex gap-3">
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="payment_type" value="full" class="sr-only peer" checked>
                    <div class="peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600
                                border-2 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-center
                                text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors">
                        ✅ Full Payment
                    </div>
                </label>
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="payment_type" value="partial" class="sr-only peer">
                    <div class="peer-checked:bg-yellow-500 peer-checked:text-white peer-checked:border-yellow-500
                                border-2 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-center
                                text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors">
                        💰 Partial Payment
                    </div>
                </label>
            </div>
        </div>

        {{-- Amount Field (shown only for partial) --}}
        <div id="amountField" class="hidden">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Amount to Pay <span class="text-gray-400 dark:text-gray-500 font-normal" id="maxAmountHint"></span>
            </label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-medium text-gray-500 dark:text-gray-400">KES</span>
                <input type="number" name="amount" id="amountInput" min="1" step="1"
                       placeholder="Enter amount"
                       class="w-full pl-12 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 text-sm">
            </div>
            @error('amount')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">M-Pesa Phone Number</label>
            <input type="text" name="phone_number"
                   value="{{ old('phone_number', auth()->user()->isAdmin() ? '' : $tenant->phone) }}"
                   placeholder="e.g. 0712345678"
                   class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 text-sm" required>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">The M-Pesa prompt will be sent to this number</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Month</label>
            <input type="month" name="month_paid" value="{{ old('month_paid', now()->format('Y-m')) }}"
                   class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 text-sm" required>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-medium text-sm transition-colors">
                📱 Send M-Pesa Prompt
            </button>
            <a href="{{ route('payments.index') }}"
               class="px-6 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    @if(!auth()->user()->isAdmin())
    const rentAmount = {{ $tenant->property->rent_amount }};
    @else
    let rentAmount = 0;
    document.getElementById('tenantSelect')?.addEventListener('change', function () {
        rentAmount = parseFloat(this.selectedOptions[0]?.dataset.rent || 0);
        updateHint();
    });
    @endif

    function updateHint() {
        const hint = document.getElementById('maxAmountHint');
        if (hint && rentAmount > 0) {
            hint.textContent = '(max KES ' + rentAmount.toLocaleString() + ')';
        }
        const input = document.getElementById('amountInput');
        if (input) input.max = rentAmount;
    }

    document.querySelectorAll('input[name="payment_type"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const field = document.getElementById('amountField');
            const input = document.getElementById('amountInput');
            if (this.value === 'partial') {
                field.classList.remove('hidden');
                input.required = true;
                updateHint();
            } else {
                field.classList.add('hidden');
                input.required = false;
                input.value = '';
            }
        });
    });

    updateHint();
</script>
@endsection
