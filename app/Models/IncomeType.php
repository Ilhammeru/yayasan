<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'period'];

    protected $hidden = ['created_at', 'updated_at;'];
}
