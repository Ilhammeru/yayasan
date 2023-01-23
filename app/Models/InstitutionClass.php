<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstitutionClass extends Model
{
    use HasFactory;

    protected $table = 'institution_class';

    protected $fillable = [
        'intitution_id',
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function levels(): HasMany
    {
        return $this->hasMany(InstitutionClassLevel::class, 'institution_class_id');
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Intitution::class, 'intitution_id');
    }
}
