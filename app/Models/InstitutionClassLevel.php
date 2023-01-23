<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstitutionClassLevel extends Model
{
    use HasFactory;

    protected $table = 'institutions_class_level';

    protected $fillable = [
        'institution_class_id',
        'name',
        'homeroom_teacher',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(InstitutionClass::class, 'institution_class_id');
    }

    public function homeroomTeacher(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'homeroom_teacher', 'id');
    }
}
