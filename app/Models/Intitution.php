<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Intitution extends Model
{
    use HasFactory;

    protected $table = 'intitutions';

    protected $fillable = [
        'name',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function classes(): HasMany
    {
        return $this->hasMany(InstitutionClass::class, 'intitution_id');
    }

    public function incomeCategories(): HasMany
    {
        return $this->hasMany(InstitutionIncomeCategory::class, 'institution_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
