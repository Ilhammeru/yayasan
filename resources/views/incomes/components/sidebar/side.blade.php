<style>
    .filter-name {
        font-weight: bold;
        font-size: 14px;
        margin-bottom: 5px;
    }
    .main-filter {
        border-radius: 12px;
        box-shadow: rgb(49 53 59 / 12%) 0px 1px 6px 0px;
        width: unset;
        padding: 10px;
    }
    .filter-item {
        margin-bottom: 10px;
    }
    .filter-item .title {
        font-weight: bold;
        margin-bottom: 5px;
    }
    .filter-item.filter-submit button {
        width: 100%;
        margin-top: 5px;
    }
</style>

<p class="filter-name">@lang('view.filter_data')</p>

<div class="main-filter">
    <form action="" id="form-filter-income">
        {{-- <div class="filter-item">
            <p class="title">@lang('view.status')</p>
            @include('incomes.components.sidebar.status')
        </div>
        <div class="filter-item">
            <p class="title">@lang('view.transaction_date')</p>
            @include('incomes.components.sidebar.transaction_date')
        </div> --}}
        @if (count($classes) > 0)
            <div class="filter-item">
                <p class="title">@lang('view.class')</p>
                @include('incomes.components.sidebar.class')
            </div>
        @endif
        <div class="filter-item">
            <p class="title">@lang('view.income_category')</p>
            @include('incomes.components.sidebar.category')
        </div>

        {{-- hidden inputs --}}
        <input type="hidden" name="institution_id" value="{{ $institution_id }}">

        <div class="filter-item filter-submit">
            <div class="form-group">
                <button class="btn btn-primary btn-sm" onclick="filterIncome()" type="button">@lang('view.apply')</button>
            </div>
        </div>
    </form>
</div>