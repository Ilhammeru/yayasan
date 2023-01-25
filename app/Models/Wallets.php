<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallets extends Model
{
    use HasFactory;
    protected $fillable = [
        'model',
        'user_id',
        'debit',
        'credit',
        'source_id',
        'source_text',
        'income_category_id',
        'is_out',
        'out',
        'proposal_id',
    ];

    public function incomeCategory(): BelongsTo
    {
        return $this->belongsTo(IncomeCategory::class, 'income_category_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payments::class, 'source_id', 'id');
    }

    public function scopeIsOut($query)
    {
        return $query->where('is_out', 1);
    }

    public function scopeIsNotOut($query)
    {
        return $query->where('out', 0)
            ->where('credit', 0);
    }

    public function scopeMyWallet($query)
    {
        $self_data = Employees::select('id')
            ->where('user_id', auth()->id())
            ->first();
        $self_id = $self_data->id;

        return $query->where('user_id', $self_id);
    }

    public function proposal()
    {
        $data = null;
        if ($this->income_category_id == 0) {
            $data = Proposal::find($this->proposal_id);
        }

        return $data;
    }
}
