<div class="main-filter" style="width: 100%;">
    <form action="" method="POST" id="form-filter-incomes">
        @if (count($class) > 0)
            <div class="form-group">
                <label for="class" class="control-label">@lang('view.class')</label>
                <select name="filter_class" id="filter-class" class="form-control" data-placeholder="Choose Class">
                    <option value="" selected disabled>-- Choose Class --</option>
                    @foreach ($class as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                <input type="time" class="form-control" date-tippy="date">
            </div>
            <div class="form-group">
                <label for="transaction_date_filter" class="control-label">@lang('view.transaction_date')</label>
                <div class="input-group">
                    <input type="date" id="transaction_date_filter_start" name="transaction_date_filter_start" class="form-control text-center" placeholder="From">
                    <span class="input-group-addon"><i class="fa fa-angle-right"></i></span>
                    <input type="date" id="transaction_date_filter_end" name="transaction_date_filter_end" class="form-control text-center" placeholder="To">
                </div>
            </div>
        @endif
    </form>
</div>

<script>
    $('#filter-class').chosen({width: '100%'});
</script>