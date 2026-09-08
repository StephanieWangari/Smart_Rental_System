<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $data = [
                'totalIncome'       => Payment::where('status', 'completed')->sum('amount'),
                'totalTenants'      => Tenant::where('status', 'active')->count(),
                'pendingPayments'   => Payment::where('status', 'pending')->count(),
                'completedPayments' => Payment::where('status', 'completed')->count(),
                'occupiedProperties'=> Property::where('status', 'occupied')->count(),
                'availableProperties'=> Property::where('status', 'available')->count(),
                'recentPayments'    => Payment::with('tenant.user', 'tenant.property')
                                        ->latest()->take(5)->get(),
            ];
            return view('dashboard.admin', $data);
        }

        $tenant = auth()->user()->tenant()->with('property')->first();
        $payments = $tenant ? $tenant->payments()->latest()->get() : collect();
        $currentMonthPayment = $tenant ? $tenant->payments()
            ->where('month_paid', now()->format('Y-m'))
            ->where('status', 'completed')
            ->first() : null;
        $totalPaid = $tenant ? $tenant->payments()->where('status', 'completed')->sum('amount') : 0;
        $monthsPaid = $tenant ? $tenant->payments()->where('status', 'completed')->count() : 0;

        return view('dashboard.tenant', compact('tenant', 'payments', 'currentMonthPayment', 'totalPaid', 'monthsPaid'));
    }
}
