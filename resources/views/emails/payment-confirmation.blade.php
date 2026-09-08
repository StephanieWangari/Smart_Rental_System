<x-mail::message>
# Payment Confirmation

Dear {{ $payment->tenant->user->name }},

Your rent payment has been received successfully.

<x-mail::panel>
- **Amount:** KES {{ number_format($payment->amount, 2) }}
- **Transaction ID:** {{ $payment->mpesa_transaction_id }}
- **Month:** {{ $payment->month_paid }}
- **Property:** {{ $payment->tenant->property->name }}
</x-mail::panel>

Thank you for your payment.

{{ config('app.name') }}
</x-mail::message>
