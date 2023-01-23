<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncomeCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'income_type_id'];

    protected $hidden = ['created_at', 'updated_at'];

    public function type(): BelongsTo
    {
        return $this->belongsTo(IncomeType::class, 'income_type_id');
    }
}
