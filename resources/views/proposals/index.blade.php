@extends('layouts.base')

@push('styles')
    
    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond/dist/filepond.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.min.css') }}"> 

    <style>
        #table-proposal tbody tr td {
            vertical-align: baseline;
        }

        #modal-funding .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        #modal-funding .modal-header::before,
        #modal-funding .modal-header::after {
            display: none;
        }
    </style>
@endpush

@section('content')
	<div class="block full">
        <div class="block-title">
            <h2>{{ __('view.proposal') }} @lang('view.list')</h2>
            @if (auth()->user()->can('create proposal'))
                <div style="padding: 0 15px;">
                    <button class="btn btn-primary btn-sm" type="button" onclick="createProposal(`{{ __('view.create_proposal') }}`)">{{ __('view.create') }}</button>
                </div>
            @endif
        </div>

        <div class="table-responsive">
            <table id="table-proposal" class="table table-condensed row-border">
                <thead>
                    <tr>
                    	<th>#</th>
                        <th>
                            <small><b>{{ __('view.title') }}</b></small>
                        </th>
                        <th>
                            <small><b>{{ __('view.budget') }}</b></small>
                        </th>
                        <th>
                            <small><b>{{ __('view.pic') }}</b></small>
                        </th>
                        <th>
                            <small><b>{{ __('view.event_date') }}</b></small>
                        </th>
                        <th>
                            <small><b>{{ __('view.status') }}</b></small>
                        </th>
                        <th>
                            <small><b>{{ __('view.action') }}</b></small>
                        </th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    {{-- modal funding --}}
    <div class="modal animation-fadeInQuick"
        id="modal-funding">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('view.cash_out')</h5>
                    <button type="button"
                        class="btn"
                        type="button"
                        onclick="closeModal('modal-funding')">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="form-funding">
                        <div class="form-group">
                            <label for="amount" class="control-label">@lang('view.amount') <span class="text-danger">*</span></label>
                            <input type="text"
                                oninput="regexNumber(this)"
                                class="form-control"
                                name="amount"
                                id="amount-cash-out"
                                placeholder="0"
                                onchange="updateValueAmount(this)">
                        </div>
                        <div class="form-group">
                            <label for="account" class="control-label">@lang('view.from_account') <span class="text-danger">*</span></label>
                            <select name="account"
                                id="account"
                                class="form-control select2"
                                data-placeholder="{{ __('view.select_account') }}">
                                @foreach (\App\Models\Account::all() as $item)
                                    <option value="{{ $item->id }}">{{ $item->code . ' - ' . $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="file"
                                class="control-label">
                                @lang('view.attachment') <span class="text-danger">*</span>
                            </label>
                            <input type="file"
                                class="filepond"
                                name="attachments_proposal[]"
                                id="filepond-funding"
                                multiple
                                data-allow-reorder="true"
                                data-max-file-size="3MB"
                                data-max-files="1">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-secondary btn-close"
                        data-bs-dismiss="modal"
                        onclick="closeModal('modal-funding')">
                        @lang('view.close')
                    </button>
                    <button type="button"
                        class="btn btn-primary btn-save"
                        onclick="cashOut()">
                        @lang('view.send')
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/plugins/filepond/dist/filepond.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js') }}"></script>
    <script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
    <script src="{{ mix('dist/js/proposal.js') }}"></script>
    <script>
        dtIntegration();

        let columns = ''
        columns = [
            {data: 'id',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                width: '5%',
                className: 'text-center'
            },
            {data: 'title', name: 'title'},
            {data: 'budget_total', name: 'budget_total'},
            {data: 'pic', name: 'pic'},
            {data: 'event_date', name: 'event_date'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', className: 'text-center', orderable: false, width: '8%'},
        ];
        let dt_route = base_url + '/proposals/ajax'
        let dt_proposal = createDataTables(
            'table-proposal',
            columns,
            dt_route
        );

        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginImageExifOrientation,
            FilePondPluginFileValidateSize,
            FilePondPluginImageEdit,
            FilePondPluginFileValidateType
        );

        // Select the file input and use
        // create() to turn it into a pond
        let pondFunding = FilePond.create(
            document.getElementById('filepond-funding')
        );

        FilePond.setOptions({
            server: {
                url: '/proposals/upload-attachments',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                }
            },
            acceptedFileTypes: [
                'image/png',
                'image/jpg',
            ],
        });

        // filepond event listener
        document.addEventListener('FilePond:addfilestart', (e) => {
            $('#modal-funding .btn-save').prop("disabled", true);
        });
        document.addEventListener('FilePond:processfileprogress', (e) => {
            $('#modal-funding .btn-save').prop("disabled", true);
        });
        document.addEventListener('FilePond:processfile', (e) => {
            $('#modal-funding .btn-save').prop("disabled", false);
        });
    </script>
@endpush