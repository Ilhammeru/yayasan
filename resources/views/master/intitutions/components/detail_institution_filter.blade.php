@push('styles')
    <style>
        .detail-filter .item {
            margin-bottom: 5px;
        }
        .detail-filter .title {
            text-align: center;
            font-weight: bolder;
            font-size: 16px;
        }
    </style>    
@endpush

<div class="detail-filter">
    <p class="title">@lang('view.filter_class')</p>
    @foreach ($classes as $key => $class)
        <div class="item">
            <button class="btn w-100 filter-class btn-default"
                id="filter-class-{{ $class->id }}"
                type="button"
                onclick="changeClass({{$class->id}}, {{$institution_id}})">
                {{ $class->name }}
            </button>
        </div>
    @endforeach
</div>