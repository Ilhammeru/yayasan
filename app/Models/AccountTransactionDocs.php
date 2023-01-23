<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTransactionDocs extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_transaction_id',
        'path'
    ];
}
