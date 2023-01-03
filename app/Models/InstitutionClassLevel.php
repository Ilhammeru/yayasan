<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InstitutionClassLevel extends Model
{
    use HasFactory;
    protected $table = 'institutions_class_level';
    protected $fillable = [
        'institution_class_id',
        'name'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function class():BelongsTo
    {
        return $this->belongsTo(InstitutionClass::class, 'institution_class_id');
    }
}
