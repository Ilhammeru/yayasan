<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'income_id',
        'income_category_id',
        'description',
        'amount',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function category()
    {
        return $this->belongsTo(IncomeCategory::class, 'income_category_id', 'id');
    }
}
