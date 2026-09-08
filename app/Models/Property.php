<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = ['name', 'location', 'description', 'rent_amount', 'status'];

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}
