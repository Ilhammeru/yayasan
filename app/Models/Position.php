<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role;

class Position extends Model
{
    use HasFactory;

    protected $table = 'positions';

    protected $fillable = ['name', 'role_id', 'is_responsible_for_foundation_finance'];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function scopeGetRole($query, $position_id)
    {
        return $query->select('role_id')
            ->find($position_id);
    }
}
