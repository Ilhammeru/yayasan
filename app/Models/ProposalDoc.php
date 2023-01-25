<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalDoc extends Model
{
    use HasFactory;
    protected $fillable = [
        'proposal_id',
        'path'
    ];

    public function realPath(): Attribute
    {
        return Attribute::make(
            get: fn() => public_path('storage/' . $this->path),
        );
    }
}
