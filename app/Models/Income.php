<?php

namespace App\Models;

use App\Models\ExternalUser;
use App\Models\IncomeItem;
use App\Models\IncomeMedia;
use App\Models\IncomeMethod;
use App\Models\IncomePayment;
use App\Models\IncomeType;
use App\Models\InternalUser;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_number',
        'user_type',
        'user_id',
        'total_amount',
        'institution_id',
        'income_type_id',
        'income_method_id',
        'transaction_start_date',
        'due_date',
        'created_by',
        'status',
        'payment_status',
        'message',
    ];
    protected $hidden = ['updated_at'];

    // #################################### REALTIONSHIP ###########################

    public function items()
    {
        return $this->hasMany(IncomeItem::class, 'income_id');
    }

    public function type()
    {
        return $this->belongsTo(IncomeType::class, 'income_type_id');
    }

    public function method()
    {
        return $this->belongsTo(IncomeMethod::class, 'income_method_id');
    }

    public function media()
    {
        return $this->hasMany(IncomeMedia::class, 'income_id');
    }

    public function internal()
    {
        return $this->BelongsTo(InternalUser::class, 'user_id');
    }

    public function external():BelongsTo
    {
        return $this->belongsTo(ExternalUser::class, 'user_id');
    }

    public function payments()
    {
        return $this->hasMany(IncomePayment::class, 'income_id');
    }

    // ################################### END RELATIONSHIP ######################

    public function paymentStatusText():Attribute
    {
        $text = __('view.unpaid');
        if ($this->payment_status == 1) {
            $text = __('view.paid');
        } else if ($this->payment_status == 2) {
            $text = __('view.partially_paid');
        }
        return Attribute::make(
            get: fn() => $text,
        );
    }

    public function paymentStatusColor():Attribute
    {
        if ($this->payment_status == 3) {
            $color = '#7F1313';
        } else if ($this->payment_status == 2) {
            $color = '#0B26DA';
        } else {
            $color = '#0CCE39';
        }
        return Attribute::make(
            get: fn() => $color
        );
    }

    public function assignUser()
    {
        if ($this->user_type == 1) { // user internal
            return $this->internal;
        } else if ($this->user_type == 2) { // user external
            return $this->external;
        }
    }

    public function fullPaymentOnly():Attribute
    {
        $method = $this->method->name;
        return Attribute::make(
            get: fn() => strtolower($method) == 'tunai' ? true : false
        );
    }

    public function remainingAmount():Attribute
    {
        $payments = $this->payments;
        $remaining = $this->total_amount;
        if (count($payments) > 0) {
            $total_payment = collect($payments)->sum('amount');
            $remaining = $this->total_amount - $total_payment;
        }
        return Attribute::make(
            get: fn() => $remaining
        );
    }

    public function paymentIsComplete():Attribute
    {
        return Attribute::make(
            get: fn() => $this->remaining_amount == 0 ? true : false
        );
    }

    public function paymentIsPartial():Attribute
    {
        $res = false;
        if(
            $this->remaining_amount != 0 &&
            $this->remaining_amount < $this->total_amount
        ) {
            $res = true;
        }
        return Attribute::make(
            get: fn() => $res
        );
    }
}
