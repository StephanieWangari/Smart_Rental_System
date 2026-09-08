<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['tenant_id', 'amount', 'mpesa_transaction_id', 'phone_number', 'status', 'month_paid'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
