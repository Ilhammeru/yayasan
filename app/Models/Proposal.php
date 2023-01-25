<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proposal extends Model
{
    use HasFactory;

    const APPROVE = 1;
    const WAITING_APPROVAL = 2;
    const APPROVE_WAIT_BUDGET = 3;
    const REJECT = 4;
    const DRAFT = 5;

    protected $fillable = [
        'title',
        'event_date',
        'event_time',
        'pic',
        'pic_user_type',
        'description',
        'budget_total',
        'approved_budget',
        'status',
        'approved_by',
        'rejected_by',
        'funding_by',
        'approve_at',
        'rejected_at',
        'funding_at',
    ];

    public function docs(): HasMany
    {
        return $this->hasMany(ProposalDoc::class, 'proposal_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', '!=', 5);
    }

    public function eventDateText(): Attribute
    {
        return Attribute::make(
            get: fn() => generate_indo_date($this->event_date),
        );
    }

    public function picData(): Attribute
    {
        if ($this->pic_user_type == 1) {
            $data = InternalUser::select('name', 'id')
                ->with(['institution:id,name', 'class:id,name', 'level:id,name'])
                ->find($this->pic);
            
            $text = $data->name . ' (' . $data->class->name . $data->level->name . ')';
        } else {
            $data = Employees::select('name', 'id', 'position_id', 'institution_id')
                ->with(['institution:id,name', 'position:id,name'])
                ->where('id', $this->pic)
                ->first();
                
            $text = $data->name . ' (' . $data->position->name . ')';
        }

        return Attribute::make(
            get: fn() => $text,
        );
    }

    public function picRaw()
    {
        if ($this->pic_user_type == 1) {
            $data = InternalUser::with(['institution:id,name', 'class:id,name', 'level:id,name'])
                ->find($this->pic);
        } else {
            $data = Employees::with(['institution:id,name', 'position:id,name'])
                ->where('id', $this->pic)
                ->first();
        }

        return $data;
    }

    public function statusText(): Attribute
    {
        if ($this->status == 1) {
            $class="themed-background-spring themed-color-white";
            $text = __('view.approved');
        } else if ($this->status == 2) {
            $class = "themed-background-autumn themed-color-white";
            $text = __('view.waiting_approval');
        } else if ($this->status == 3) {
            $class = "themed-background-amethyst themed-color-white";
            $text = __('view.approved_wait_budget');
        } else if ($this->status == 4) {
            $class = "themed-background-fire themed-color-white";
            $text = __('view.reject');
        } else {
            $class = "themed-background-night";
            $text = __('view.draft');
        }
        $res = '<span class="label '. $class .'">'. $text .'</span>';

        return Attribute::make(
            get: fn() => $res,
        );
    }

    public function totalText(): Attribute
    {
        return Attribute::make(
            get: fn() => 'Rp. ' . number_format($this->budget_total, 0, '.', '.')
        );
    }
}
