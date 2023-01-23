<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_id',
        'debit',
        'credit',
        'status',
        'description',
        'source_id',
    ];
}
