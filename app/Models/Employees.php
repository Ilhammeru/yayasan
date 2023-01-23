<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employees extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'user_id',
        'nip',
        'phone',
        'address',
        'district_id',
        'city_id',
        'province_id',
        'account_number',
        'institution_id',
        'position_id',
        'status',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function scopeIsNotMe($query, $user_id)
    {
        return $query->where('user_id', '!=', $user_id);
    }

    public function scopeActive($query) {
        return $query->where('status', 1);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Intitution::class, 'institution_id', 'id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function classTeacher(): HasOne
    {
        return $this->hasOne(InstitutionClassLevel::class, 'homeroom_teacher', 'id');
    }
}
