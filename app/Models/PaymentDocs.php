<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDocs extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_id', 'path'
    ];

    public function fullPath(): Attribute
    {
        $path = asset('storage/' . $this->path);
        return Attribute::make(
            get: fn() => $path,
        );
    }
}
