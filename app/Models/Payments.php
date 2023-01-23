<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payments extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_number_group',
        'invoice_number',
        'amount',
        'payment_date',
        'payment_time',
        'is_annualy',
        'is_monthly',
        'is_weekly',
        'is_daily',
        'annualy',
        'monthly',
        'weekly',
        'daily',
        'status',
        'user_type',
        'user_id',
        'income_category_id',
        'income_method_id',
        'income_type_id',
        'institution_id',
        'institution_class_id',
        'institution_class_level_id',
        'payment_at_class',
        'payment_target_position',
        'payment_target_user',
        'description'
    ];

    public function docs(): HasMany
    {
        return $this->hasMany(PaymentDocs::class, 'payment_id', 'id');
    }

    // public function user(): BelongsTo
    // {
    //     if ($this->user_type === 1) {
    //         return $this->belongsTo(InternalUser::class, 'user_id', 'id');
    //     } else if ($this->user_type === 2) {
    //         return $this->belongsTo(ExternalUser::class, 'user_id', 'id');
    //     }
    // }

    public function incomeCategory(): BelongsTo
    {
        return $this->belongsTo(IncomeCategory::class, 'income_category_id', 'id');
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Intitution::class, 'institution_id', 'id');
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(InstitutionClass::class, 'institution_class_id');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(InstitutionClassLevel::class, 'institution_class_level_id');
    }

    public function incomeMethod(): BelongsTo
    {
        return $this->belongsTo(IncomeMethod::class, 'income_method_id');
    }

    public function paidAt(): Attribute
    {
        $date = generate_indo_date($this->payment_date);
        return Attribute::make(
            get: fn () => $date,
        );
    }

    public function amountText(): Attribute
    {
        return Attribute::make(
            get: fn() => 'Rp. ' . number_format($this->amount, 0, '.', '.'),
        );
    }

    public function userData()
    {
        $user_type = $this->user_type;
        if ($user_type == 1) {
            $data = InternalUser::select('name', 'address', 'phone')
                ->where('id', $this->user_id)
                ->first();
        } else {
            $data = ExternalUser::select('name', 'address', 'phone')
                ->where('id', $this->user_id)
                ->first();
        }

        return $data;
    }
}
