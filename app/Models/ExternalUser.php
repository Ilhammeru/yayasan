<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'address',
        'district_id',
        'city_id',
        'province_id',
        'status'
    ];
    protected $hidden = ['created_at', 'updated_at'];
}
