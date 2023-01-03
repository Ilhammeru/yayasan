<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;

class InternalUser extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'institution_id',
        'nis',
        'parent_data',
        'phone',
        'address',
        'district_id',
        'city_id',
        'province_id',
        'institution_class_id',
        'institution_class_level_id',
        'status'
    ];

    public function institution():BelongsTo
    {
        return $this->belongsTo(Intitution::class, 'institution_id');
    }

    public function class():BelongsTo
    {
        return $this->belongsTo(InstitutionClass::class, 'institution_class_id');
    }

    public function level():BelongsTo
    {
        return $this->belongsTo(InstitutionClassLevel::class, 'institution_class_level_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function province():BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function city():BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function district():BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
