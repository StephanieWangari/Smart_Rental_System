<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['tenant_id', 'amount', 'payment_type', 'mpesa_transaction_id', 'checkout_request_id', 'phone_number', 'status', 'month_paid'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
