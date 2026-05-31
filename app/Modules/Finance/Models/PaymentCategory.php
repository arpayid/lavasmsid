<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
