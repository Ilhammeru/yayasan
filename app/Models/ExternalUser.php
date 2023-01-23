<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;

class ExternalUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'user_type',
        'phone',
        'address',
        'district_id',
        'city_id',
        'province_id',
        'status',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function type(): Attribute
    {
        return Attribute::make(
            get: fn () => 'external'
        );
    }

    public static function get_user_type()
    {
        return [
            ['id' => 1, 'name' => __('view.public')],
            ['id' => 2, 'name' => __('view.goverment')],
        ];
    }

    protected function userTypeText(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user_type == 1 ? __('view.public') : __('view.goverment'),
        );
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function province(): BelongsTo
    {
        return $this->BelongsTo(Province::class, 'province_id');
    }

    public function city(): BelongsTo
    {
        return $this->BelongsTo(City::class, 'city_id');
    }

    public function district(): BelongsTo
    {
        return $this->BelongsTo(District::class, 'district_id');
    }
}
