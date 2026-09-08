<?php

namespace App\Http\Controllers;

use App\Mail\PaymentConfirmationMail;
use App\Models\Payment;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        $request->validate([
            'tenant_id'    => 'required|exists:tenants,id',
            'phone_number' => 'required|string|max:20',
            'month_paid'   => 'required|string',
        ]);

        $tenant = Tenant::with('property')->findOrFail($request->tenant_id);

        $payment = Payment::create([
            'tenant_id'    => $tenant->id,
            'amount'       => $tenant->property->rent_amount,
            'phone_number' => $request->phone_number,
            'month_paid'   => $request->month_paid,
            'status'       => 'pending',
        ]);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment initiated. Complete via M-Pesa prompt.');
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

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment record deleted.');
    }
}
