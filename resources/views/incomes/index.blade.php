@extends('layouts.base')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond/dist/filepond.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/month-picker/MonthPicker.css') }}">
    <style>
        .empty-jpg {
            width: 150px;
            height: auto;
        }
        #modal-send-wallet .modal-dialog {
            max-width: 800px;
            height: auto;
            margin: 6.75rem;
            margin-right: auto;
            margin-left: auto;
        }
    </style>
@endpush

@section('content')
    <div class="block full">
        <div class="block-title">
            <h2>{{ __('view.income') }}</h2>
            {{-- @if (auth()->user()->can('income create') && session('show_create_button'))
                <div style="padding: 0 15px;">
                    <a class="btn btn-primary btn-sm" href="{{ route('incomes.create') }}">{{ __('view.create') }}</a>
                </div>
            @endif --}}
        </div>

        <div class="row">

            @if (count($classes) > 0)
                {{-- begin::filter --}}
                <div class="col-md-2">
                    @include('incomes.components.sidebar.side')
                </div>
                {{-- end::filter --}}

                {{-- begin::main-content --}}
                <div class="col-md-10">
                    <div class="main-content-incomes">
                        {!! $view !!}
                    </div>
                </div>
                {{-- end::main-content --}}
            @else
                <div style="display: flex; align-items: center; justify-content: center;">
                    <img src="{{ asset('assets/img/empty_data.jpg') }}" class="empty-jpg" alt="">
                </div>
                <div class="text-center">
                    <p>@lang('view.empty_class')</p>
                </div>
            @endif

        </div>
    </div>

    {{-- modal invoice --}}
    <div class="modal animation-fadeInQuick"
        id="invoice-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button"
                        class="btn"
                        type="button"
                        onclick="closeModal('invoice-modal')">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer d-none" style="text-align: center !important;">
                    <button type="button"
                        class="btn btn-secondary btn-close"
                        data-bs-dismiss="modal"
                        onclick="closeModal('invoice-modal')"></button>
                    <button type="button"
                        class="btn btn-primary btn-save"></button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="{{ asset('assets/plugins/filepond/dist/filepond.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js') }}"></script>
    <script src="{{ asset('assets/plugins/month-picker/MonthPicker.js   ') }}"></script>
    <script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
    <script src="{{ mix('dist/js/mainIncome.js') }}"></script>
    <script>

    </script>
@endpush