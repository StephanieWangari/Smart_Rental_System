<?php

return [
    'consumer_key'    => env('MPESA_CONSUMER_KEY', ''),
    'consumer_secret' => env('MPESA_CONSUMER_SECRET', ''),
    'shortcode'       => env('MPESA_SHORTCODE', '174379'),
    'passkey'         => env('MPESA_PASSKEY', ''),
    'auth_url'        => env('MPESA_AUTH_URL', 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'),
    'stk_url'         => env('MPESA_STK_URL', 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest'),
    'callback_url'    => env('MPESA_CALLBACK_URL', ''),
];
