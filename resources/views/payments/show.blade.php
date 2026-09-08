@extends('layouts.app')
@section('content')
<div class="mb-5">
    <a href="{{ route('payments.index') }}" class="text-green-600 dark:text-green-400 hover:underline text-sm">← Back to Payments</a>
</div>

<h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Payment Receipt</h1>

<div class="max-w-lg bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-4 bg-green-600 dark:bg-green-800">
        <p class="text-white font-semibold text-lg">🧾 Receipt</p>
        <p class="text-green-100 text-sm">{{ $payment->created_at->format('d M Y, h:i A') }}</p>
    </div>
    <div class="px-6 py-5 space-y-4 text-sm divide-y divide-gray-100 dark:divide-gray-700">
        <div class="flex justify-between py-2">
            <span class="text-gray-500 dark:text-gray-400">Tenant</span>
            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $payment->tenant->user->name }}</span>
        </div>
        <div class="flex justify-between py-2">
            <span class="text-gray-500 dark:text-gray-400">Property</span>
            <span class="text-gray-800 dark:text-gray-200">{{ $payment->tenant->property->name }}</span>
        </div>
        <div class="flex justify-between py-2">
            <span class="text-gray-500 dark:text-gray-400">Amount</span>
            <span class="font-bold text-green-600 dark:text-green-400 text-base">KES {{ number_format($payment->amount, 2) }}</span>
        </div>
        <div class="flex justify-between py-2">
            <span class="text-gray-500 dark:text-gray-400">Month</span>
            <span class="text-gray-800 dark:text-gray-200">{{ $payment->month_paid }}</span>
        </div>
        <div class="flex justify-between py-2">
            <span class="text-gray-500 dark:text-gray-400">M-Pesa Number</span>
            <span class="text-gray-800 dark:text-gray-200">{{ $payment->phone_number }}</span>
        </div>
        <div class="flex justify-between py-2">
            <span class="text-gray-500 dark:text-gray-400">Transaction ID</span>
            <span class="font-mono text-gray-800 dark:text-gray-200">{{ $payment->mpesa_transaction_id ?? '—' }}</span>
        </div>
        <div class="flex justify-between py-2">
            <span class="text-gray-500 dark:text-gray-400">Status</span>
            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                {{ $payment->status === 'completed' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400' : ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-400' : 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400') }}">
                {{ ucfirst($payment->status) }}
            </span>
        </div>
    </div>

    @if($payment->status === 'pending')
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Complete this payment via M-Pesa STK push:</p>
        <button id="stkBtn" onclick="sendStkPush({{ $payment->id }}, '{{ $payment->phone_number }}')"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors">
            📱 Send M-Pesa Prompt
        </button>
        <p id="stkMsg" class="mt-2 text-sm text-gray-500 dark:text-gray-400"></p>
    </div>
    @endif
</div>

<script>
function sendStkPush(paymentId, phone) {
    const btn = document.getElementById('stkBtn');
    const msg = document.getElementById('stkMsg');
    btn.disabled = true;
    msg.textContent = 'Sending prompt to ' + phone + '...';
    fetch('{{ route("mpesa.stk") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ payment_id: paymentId, phone_number: phone })
    })
    .then(r => r.json())
    .then(data => { msg.textContent = data.message; btn.disabled = false; })
    .catch(() => { msg.textContent = 'Error sending prompt. Try again.'; btn.disabled = false; });
}
</script>
@endsection
