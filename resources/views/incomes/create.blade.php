@extends('layouts.base')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond/dist/filepond.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.min.css') }}">
    <style>
        .form-wrapper {
            border: .3px solid #e6e6e6;
            padding: 12px;
        }
    </style>
@endpush

@section('content')
	<div class="block full">
        <div class="block-title">
            <h2>{{ __('view.create_invoice') }}</h2>
            <div style="padding: 0 15px;">
                <a class="btn btn-warning btn-sm" href="{{ route('incomes.index') }}">{{ __('view.back') }}</a>
            </div>
        </div>

        <div class="block-body">
            <div class="form-wrapper">
                <form id="form-invoice" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label class="control-label" for="user">{{ __('view.user') }} <span class="text-danger">*</span></label>
                                <select class="form-control" id="user" name="user" data-placeholder="{{ __('view.choose_user') }}" onchange="getDetailUser(this)">
                                    <option></option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id . '-' . $user->type }}">{{ $user->name }}</option>
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
                        <div class="col-md-4 col-sm-12">
                            <label class="control-label" for="transaction_start_date">{{ __('view.starting_date') }} <span class="text-danger">*</span></label>
                            <input class="form-control" id="transaction_start_date" name="transaction_start_date" placeholder="yyyy-mm-dd">
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label class="control-label" for="due_date">{{ __('view.due_date') }} <span class="text-danger">*</span></label>
                            <input class="form-control" id="due_date" name="due_date" placeholder="yyyy-mm-dd">
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label class="control-label" for="institution_id">{{ __('view.intitutions') }} <span class="text-danger">*</span></label>
                            <select class="form-control" id="institution_id" name="institution_id" data-placeholder="{{ __('view.search_institution') }}">
                                <option></option>
                                @foreach ($institutions as $institution)
                                    <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- income type, income method  --}}
                    <div class="row" style="margin-top: 12px;">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label class="control-label" for="income_type_id">{{ __('view.income_type') }} <span class="text-danger">*</span></label>
                                <select class="form-control" id="income_type_id" name="income_type_id" data-placeholder="{{ __('view.search_income_type') }}">
                                    <option></option>
                                    @foreach ($incomeTypes as $it)
                                        <option value="{{ $it->id }}">{{ $it->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label class="control-label" for="income_method_id">{{ __('view.income_method') }} <span class="text-danger">*</span></label>
                                <select class="form-control" id="income_method_id" name="income_method_id" data-placeholder="{{ __('view.search_income_method') }}">
                                    <option></option>
                                    @foreach ($incomeMethods as $im)
                                        <option value="{{ $im->id }}">{{ $im->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- products --}}
                    @include('incomes.components.product', ['incomeCategories' => $incomeCategories])
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('dist/js/mainIncome.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
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
@endpush