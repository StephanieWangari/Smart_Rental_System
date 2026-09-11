<x-mail::message>
# Successful Login 🔐

Hi **{{ $user->name }}**,

We detected a new login to your **Smart Rental System** account.

<x-mail::panel>
- **Date & Time:** {{ $loginTime }}
- **IP Address:** {{ $ipAddress }}
</x-mail::panel>

If this was you, no action is needed.

If you did **not** log in, please contact the admin immediately or change your password.

<x-mail::button :url="url('/profile')" color="success">
Review My Account
</x-mail::button>

Thanks,
**{{ config('app.name') }}**
</x-mail::message>

