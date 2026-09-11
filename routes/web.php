<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MpesaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tenant: own payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{payment}/status', [PaymentController::class, 'status'])->name('payments.status');

    // M-Pesa STK push
    Route::post('/mpesa/stk-push', [MpesaController::class, 'stkPush'])->name('mpesa.stk');
});

// Admin-only routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('properties', PropertyController::class);
    Route::resource('tenants', TenantController::class);
    Route::get('/admin/payments', [PaymentController::class, 'adminIndex'])->name('admin.payments.index');
    Route::get('/reports', [PaymentController::class, 'reports'])->name('payments.reports');
    Route::get('/reports/export', [PaymentController::class, 'exportReport'])->name('payments.export');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
});

// M-Pesa callback (no auth, called by Safaricom)
Route::post('/mpesa/callback', [MpesaController::class, 'callback'])->name('mpesa.callback');
