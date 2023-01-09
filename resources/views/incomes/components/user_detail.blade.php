<style>
    .detail-user .item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 4px;
    }
    .detail-user .item .text {
        margin: 0;
        font-size: 10px;
        color: #063970;
    }
</style>

{{-- address --}}
<div class="item">
    <i class="fa fa-home"></i>
    <p class="text">{{ $detail->address . ', ' . ucfirst($detail->district->name) . ' ' . ucfirst($detail->city->name) . ' ' . ucfirst($detail->province->name) }}</p>
</div>
{{-- type user --}}
<div class="item">
    <i class="fa fa-user"></i>
    <p class="text"><span class="label label-primary">{{ $detail->type }}</span></p>
</div>
<div class="item">
    <i class="fa fa-phone"></i>
    <p class="text">{{ $detail->phone }}</p>
</div>
<div class="item">
    <i class="fa fa-building"></i>
    <p class="text">
        @if ($detail->type == 'internal')
            {{ strtoupper($detail->institution->name) . ' ( '. $detail->class->name . $detail->level->name .' )' }}
        @else
            {{ $detail->user_type_text }}
        @endif
    </p>
</div>