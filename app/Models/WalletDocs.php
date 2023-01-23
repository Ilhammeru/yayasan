<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletDocs extends Model
{
    use HasFactory;
    protected $fillable = ['wallet_id', 'path'];
}
