<?php

namespace App\Http\Controllers;

use App\Mail\PaymentConfirmationMail;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MpesaController extends Controller
{
    private function getAccessToken(): string
    {
        $consumerKey    = config('mpesa.consumer_key');
        $consumerSecret = config('mpesa.consumer_secret');

        $response = Http::withBasicAuth($consumerKey, $consumerSecret)
            ->get(config('mpesa.auth_url'));

        return $response->json('access_token');
    }

    public function stkPush(Request $request)
    {
        $request->validate([
            'payment_id'   => 'required|exists:payments,id',
            'phone_number' => 'required|string',
        ]);

        $payment   = Payment::with('tenant.property')->findOrFail($request->payment_id);
        $token     = $this->getAccessToken();
        $timestamp = now()->format('YmdHis');
        $shortcode = config('mpesa.shortcode');
        $passkey   = config('mpesa.passkey');
        $password  = base64_encode($shortcode . $passkey . $timestamp);

        $phone = preg_replace('/^0/', '254', $request->phone_number);

        $response = Http::withToken($token)->post(config('mpesa.stk_url'), [
            'BusinessShortCode' => $shortcode,
            'Password'          => $password,
            'Timestamp'         => $timestamp,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => (int) $payment->amount,
            'PartyA'            => $phone,
            'PartyB'            => $shortcode,
            'PhoneNumber'       => $phone,
            'CallBackURL'       => config('mpesa.callback_url'),
            'AccountReference'  => 'Rent-' . $payment->id,
            'TransactionDesc'   => 'Rent Payment',
        ]);

        if ($response->successful()) {
            $payment->update(['status' => 'pending', 'phone_number' => $request->phone_number]);
            return response()->json(['message' => 'STK push sent. Check your phone.']);
        }

        return response()->json(['message' => 'Failed to initiate payment.'], 422);
    }

    public function callback(Request $request)
    {
        $data     = $request->json()->all();
        $result   = $data['Body']['stkCallback'] ?? null;

        if (!$result) return response()->json(['status' => 'ok']);

        $resultCode = $result['ResultCode'];
        $metadata   = collect($result['CallbackMetadata']['Item'] ?? []);

        $transactionId = $metadata->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? null;
        $accountRef    = $result['AccountReference'] ?? '';
        $paymentId     = str_replace('Rent-', '', $accountRef);

        $payment = Payment::find($paymentId);
        if (!$payment) return response()->json(['status' => 'ok']);

        if ($resultCode === 0) {
            $payment->update(['status' => 'completed', 'mpesa_transaction_id' => $transactionId]);
            Mail::to($payment->tenant->user->email)->send(new PaymentConfirmationMail($payment));
        } else {
            $payment->update(['status' => 'failed']);
        }

        return response()->json(['status' => 'ok']);
    }
}
