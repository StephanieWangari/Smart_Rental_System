@extends('layouts.app')
@section('content')

<div class="mb-6">
    <a href="{{ auth()->user()->isAdmin() ? route('admin.payments.index') : route('payments.index') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-violet-600 dark:text-violet-400 hover:underline">
        ← Back to Payments
    </a>
</div>

<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-xl">🧾</div>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">Payment Receipt</h1>
            <p class="text-sm text-gray-400 dark:text-gray-500">{{ $payment->created_at->format('d M Y, h:i A') }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        {{-- Header band --}}
        <div class="px-6 py-5 flex items-center justify-between"
             style="background: linear-gradient(135deg, #7c3aed, #4f46e5)">
            <div>
                <p class="text-white font-bold text-lg">{{ $payment->tenant->user->name }}</p>
                <p class="text-purple-200 text-sm">{{ $payment->tenant->property->name }}</p>
            </div>
            <div class="text-right">
                <p class="text-white font-extrabold text-2xl">KES {{ number_format($payment->amount, 2) }}</p>
                @if(($payment->payment_type ?? 'full') === 'partial')
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-400/30 text-yellow-100">Partial</span>
                @else
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-white/20 text-white">Full Payment</span>
                @endif
            </div>
        </div>

        {{-- Details --}}
        <div class="px-6 py-5 space-y-0 text-sm divide-y divide-gray-50 dark:divide-gray-700/50">
            @foreach([
                ['Property', $payment->tenant->property->name],
                ['Month', $payment->month_paid],
                ['M-Pesa Number', $payment->phone_number],
                ['Transaction ID', $payment->mpesa_transaction_id ?? '—'],
            ] as [$label, $value])
            <div class="flex justify-between items-center py-3">
                <span class="text-gray-400 dark:text-gray-500">{{ $label }}</span>
                <span class="font-semibold text-gray-800 dark:text-gray-200 font-mono text-xs">{{ $value }}</span>
            </div>
            @endforeach

            @if(($payment->payment_type ?? 'full') === 'partial' && $payment->tenant->property->rent_amount > $payment->amount)
            <div class="flex justify-between items-center py-3">
                <span class="text-gray-400 dark:text-gray-500">Balance Remaining</span>
                <span class="font-bold text-red-500 dark:text-red-400">KES {{ number_format($payment->tenant->property->rent_amount - $payment->amount, 2) }}</span>
            </div>
            @endif

            <div class="flex justify-between items-center py-3">
                <span class="text-gray-400 dark:text-gray-500">Status</span>
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
            </div>
        </div>

        @if($payment->status === 'pending')
        <div class="px-6 py-5 border-t border-gray-100 dark:border-gray-700 bg-amber-50 dark:bg-amber-900/10">
            <div class="flex items-center gap-2 text-amber-700 dark:text-amber-400 mb-3">
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                <span class="text-sm font-semibold">Waiting for M-Pesa confirmation on {{ $payment->phone_number }}...</span>
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-3">Didn't receive the prompt?</p>
            <button id="stkBtn" onclick="sendStkPush({{ $payment->id }}, '{{ $payment->phone_number }}')"
                    class="btn-primary text-white px-5 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2">
                📱 Resend M-Pesa Prompt
            </button>
            <p id="stkMsg" class="mt-2 text-sm text-gray-500 dark:text-gray-400"></p>
        </div>

        <script>
        const pollInterval = setInterval(() => {
            fetch('{{ route("payments.status", $payment) }}')
                .then(r => r.json())
                .then(data => {
                    if (data.status !== 'pending') {
                        clearInterval(pollInterval);
                        location.reload();
                    }
                });
        }, 5000);

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
        @endif
    </div>
</div>
@endsection

