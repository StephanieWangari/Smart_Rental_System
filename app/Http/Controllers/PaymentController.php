<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Mail\PaymentConfirmationMail;
use App\Models\Payment;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('tenant.user', 'tenant.property');

        if (!auth()->user()->isAdmin()) {
            $tenant = auth()->user()->tenant;
            $query->where('tenant_id', $tenant?->id);
        }

        $payments = $query
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->month, fn($q) => $q->where('month_paid', $request->month))
            ->latest()->paginate(10);

        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $tenant = auth()->user()->isAdmin()
            ? null
            : auth()->user()->tenant;

        $tenants = auth()->user()->isAdmin() ? Tenant::with('user')->get() : null;
        return view('payments.create', compact('tenant', 'tenants'));
    }

    public function store(Request $request)
    {
        $tenant = Tenant::with('property')->findOrFail($request->tenant_id);
        $isPartial = $request->payment_type === 'partial';

        $request->validate([
            'tenant_id'    => 'required|exists:tenants,id',
            'phone_number' => 'required|string|max:20',
            'month_paid'   => 'required|string',
            'payment_type' => 'required|in:full,partial',
            'amount'       => $isPartial
                ? 'required|numeric|min:1|max:' . $tenant->property->rent_amount
                : 'nullable',
        ]);

        $amount = $isPartial ? $request->amount : $tenant->property->rent_amount;

        $payment = Payment::create([
            'tenant_id'    => $tenant->id,
            'amount'       => $amount,
            'payment_type' => $request->payment_type,
            'phone_number' => $request->phone_number,
            'month_paid'   => $request->month_paid,
            'status'       => 'pending',
        ]);

        $this->triggerStkPush($payment, $request->phone_number);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'M-Pesa prompt sent to ' . $request->phone_number . '. Enter your PIN to complete payment.');
    }

    public function show(Payment $payment)
    {
        $payment->load('tenant.user', 'tenant.property');
        return view('payments.show', compact('payment'));
    }

    public function reports(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');

        $monthlyIncome = Payment::where('status', 'completed')
            ->where('month_paid', $month)->sum('amount');

        $outstanding = Tenant::where('status', 'active')
            ->whereDoesntHave('payments', fn($q) =>
                $q->where('month_paid', $month)->where('status', 'completed')
            )->with('user', 'property')->get();

        return view('payments.reports', compact('monthlyIncome', 'outstanding', 'month'));
    }

    public function exportReport(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');
        $filename = 'rent-report-' . $month . '.xlsx';
        return Excel::download(new ReportExport($month), $filename);
    }

    public function status(Payment $payment)
    {
        return response()->json(['status' => $payment->fresh()->status]);
    }

    public function adminIndex(Request $request)
    {
        $payments = Payment::with('tenant.user', 'tenant.property')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->month, fn($q) => $q->where('month_paid', $request->month))
            ->latest()->paginate(15);

        return view('payments.admin-index', compact('payments'));
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Payment record deleted.');
    }

    private function triggerStkPush(Payment $payment, string $phone): void
    {
        try {
            $consumerKey    = config('mpesa.consumer_key');
            $consumerSecret = config('mpesa.consumer_secret');
            $tokenResponse  = Http::withBasicAuth($consumerKey, $consumerSecret)
                ->get(config('mpesa.auth_url'));
            $token     = $tokenResponse->json('access_token');
            $timestamp = now()->format('YmdHis');
            $shortcode = config('mpesa.shortcode');
            $passkey   = config('mpesa.passkey');
            $password  = base64_encode($shortcode . $passkey . $timestamp);
            $phone     = preg_replace('/^0/', '254', $phone);

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

            if ($response->successful() && $response->json('ResponseCode') === '0') {
                $payment->update(['checkout_request_id' => $response->json('CheckoutRequestID')]);
            }
        } catch (\Exception $e) {
            // Silent fail — tenant can retry from show page
        }
    }
}
