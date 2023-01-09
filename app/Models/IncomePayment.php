<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomePayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'income_id',
        'amount',
        'account_id',
        'proof_payment',
        'payment_time'
    ];

    public function media()
    {
        return $this->hasMany(IncomePaymentMedia::class, 'income_payment_id');
    }
}
