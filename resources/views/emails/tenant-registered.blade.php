<x-mail::message>
# Welcome to Smart Rental System 🏠

Dear **{{ $user->name }}**,

Your tenant account has been created successfully. Here are your details:

<x-mail::panel>
**Your Login Credentials**
- **Email:** {{ $user->email }}
- **Password:** {{ $plainPassword }}

**Your Property Details**
- **Property:** {{ $tenant->property->name }}
- **Location:** {{ $tenant->property->location }}
- **Move-in Date:** {{ \Carbon\Carbon::parse($tenant->move_in_date)->format('d M Y') }}
- **Monthly Rent:** KES {{ number_format($tenant->property->rent_amount, 2) }}
- **Your M-Pesa Number:** {{ $tenant->phone }}
</x-mail::panel>

Use the button below to log in and access your dashboard where you can view your payment plan and make rent payments.

<x-mail::button :url="url('/login')" color="success">
Access My Dashboard
</x-mail::button>

> For security, please change your password after your first login.

Thanks,
**{{ config('app.name') }}**
</x-mail::message>

