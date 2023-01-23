<style>
    .form-wrapper {
        border: .3px solid #e6e6e6;
        padding: 12px;
    }
</style>
@php
    $is_enable = false;
    if ($enable_all) {
        $is_enable = $enable_all;
    }
@endphp
<form action="" id="form-payment">

    {{-- hidden inputs --}}
    <input type="hidden" name="class_id" value="{{ $class_id }}">
    <input type="hidden" name="level_id" value="{{ $level_id }}">

    <div class="block-body">
        <div class="form-wrapper">
            <form id="form-invoice" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="control-label" for="user">{{ __('view.user') }} <span class="text-danger">*</span></label>
                            <select class="form-control select-chosen" {{ $is_enable ? '' : 'readonly' }} name="user" data-placeholder="{{ __('view.choose_user') }}">
                                <option></option>
                                @foreach ($users as $user)
                                    @if ($is_enable)
                                        <option value="{{ $user->id . '-' . $user->type }}">{{ $user->name }}</option>
                                    @else
                                        <option value="{{ $user->id . '-' . $user->type }}" {{ $user->selected ? 'selected' : 'disabled' }}>{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group" id="form-group-invoice-number">
                            <label class="control-label" for="invoice_number">{{ __('view.invoice_number') }}</label>
                            <input type="text" id="invoice_number" name="invoice_number" class="form-control" value="{{ $suggest_number }}" onfocusout="checkInvoiceNumber(this)">
                            <span class="help-block d-none">{{ __('view.invoice_taken') }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="detail-user"></div>
                    </div>
                </div>
    
                {{-- due date, transaction date, institution --}}
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <label class="control-label" for="transaction_start_date">{{ __('view.starting_date') }} <span class="text-danger">*</span></label>
                        <input class="form-control" id="transaction_start_date" name="transaction_start_date" placeholder="yyyy-mm-dd">
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label class="control-label" for="institution_id">{{ __('view.intitutions') }} <span class="text-danger">*</span></label>
                        <select class="form-control select-chosen" id="institution_id" name="institution_id" data-placeholder="{{ __('view.search_institution') }}">
                            <option></option>
                            @foreach ($institutions as $institution)
                                <option value="{{ $institution->id }}" {{ $institution->selected ? 'selected' : 'disabled' }}>{{ $institution->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
    
                {{-- income type, income method  --}}
                <div class="row" style="margin-top: 12px;">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="control-label" for="income_type_id">{{ __('view.income_type') }} <span class="text-danger">*</span></label>
                            <select class="form-control select-chosen" id="income_type_id" name="income_type_id" data-placeholder="{{ __('view.search_income_type') }}">
                                <option></option>
                                @foreach ($incomeTypes as $it)
                                    <option value="{{ $it->id }}" {{ $it->selected ? 'selected' : 'disabled' }}>{{ $it->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="control-label" for="income_method_id">{{ __('view.income_method') }} <span class="text-danger">*</span></label>
                            <select class="form-control select-chosen" id="income_method_id" name="income_method_id" data-placeholder="{{ __('view.search_income_method') }}">
                                <option></option>
                                @foreach ($incomeMethods as $im)
                                    <option value="{{ $im->id }}">{{ $im->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
    
                {{-- products --}}
                @include('incomes.components.product', [
                    'incomeCategories' => $incomeCategories,
                    'income_category_id' => $income_category_id,
                    'selected_month' => $selected_month,
                    'is_enable' => $is_enable,
                ])
            </form>
        </div>
    </div>
</form>

<script>
    $('#transaction_start_date').daterangepicker({
        singleDatePicker: true,
        autoApply: true,
        minDate: "{{ date('Y-m-d') }}",
        locale: {
            format: "YYYY-MM-DD"
        }
    });
    $('#due_date').daterangepicker({
        singleDatePicker: true,
        autoApply: true,
        minDate: "{{ date('Y-m-d') }}",
        locale: {
            format: "YYYY-MM-DD"
        }
    });
</script>