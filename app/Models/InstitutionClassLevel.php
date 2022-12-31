<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
