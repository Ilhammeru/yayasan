<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomePaymentMedia extends Model
{
    use HasFactory;

    protected $fillable = ['income_payment_id', 'path'];

    protected $hidden = ['created_at', 'updated_at'];
}
