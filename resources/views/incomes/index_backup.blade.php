@extends('layouts.base')

@push('styles')
    <style>
        table > thead > tr > th {
            font-size: 13px !important;
        }
        .filter-section {
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .filter-section .btn-filter {
            border: 1px solid #f4f4f4;
        }
        .filter-section .btn-filter.active {
            border: 1px solid lightblue;
        }
    </style>
@endpush

@section('content')
	<div class="block full">
        <div class="block-title">
            <h2>{{ __('view.income') }}</h2>
            @if (auth()->user()->can('income create'))
                <div style="padding: 0 15px;">
                    <a class="btn btn-primary btn-sm" href="{{ route('incomes.create') }}">{{ __('view.create') }}</a>
                </div>
            @endif
        </div>

        <div class="row">
            <div class="col-md-2">
                {{-- begin::inner siderbar --}}
                @include('incomes.components.sidebar.side')
                {{-- end::inner siderbar --}}
            </div>
            <div class="col-md-10">
                {{-- begin::content --}}
                {{-- <div class="inner-skeleton" id="inner-skeleton-income">
                    @include('layouts.skeleton_table_with_filter')
                </div> --}}
                {{-- filter section --}}
                {{-- <div class="inner-content d-none" id="inner-content-income">
                    <div class="filter-section">
                        <div class="btn-group btn-group-sm" data-toggle="buttons">
                            <button class="btn btn-filter" style="margin-right: 5px;" id="btn-main-filter" type="button"><i class="fa fa-filter"></i></button>
                            <button class="btn btn-filter active" id="btn-all" onclick="filterInvoice('all')">{{ __('view.all') }}</button>
                            <button class="btn btn-filter" id="btn-paid" onclick="filterInvoice('paid')">{{ __('view.paid') }}</button>
                            <button class="btn btn-filter" id="btn-unpaid" onclick="filterInvoice('unpaid')">{{ __('view.unpaid') }}</button>
                            <button class="btn btn-filter" id="btn-partially" onclick="filterInvoice('partially')">{{ __('view.partially_paid') }}</button>
                        </div>
            
                        <div class="btn-group btn-group-sm" data-toggle="buttons">
                            <button class="btn btn-warning btn-filter" id="btn-all" onclick="filterInvoice('all')">{{ __('view.print') }}</button>
                            <button class="btn btn-success btn-filter" id="btn-paid" onclick="filterInvoice('paid')">{{ __('view.export') }}</button>
                        </div>
                    </div>
            
                    <div class="table-responsive">
                        <table id="table-incomes" class="table table-vcenter table-condensed">
                            <thead>
                                <tr>
                                    <th class="testing">{{ __('view.invoice') }}</th>
                                    <th>{{ __('view.user') }}</th>
                                    <th>{{ __('view.starting_date') }}</th>
                                    <th>{{ __('view.due_date') }}</th>
                                    <th>{{ __('view.status') }}</th>
                                    <th>{{ __('view.remaining_bill') }}</th>
                                    <th>{{ __('view.total') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div> --}}
                {{-- end::content --}}
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="{{ mix('dist/js/mainIncome.js') }}"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>

    <script>
        dtIntegration();

        let columns, dt_incomes;
        let classId = "{{ $param['class_id'] }}";
        let institutionId = "{{ $param['institution_id'] }}";
        let status = "{{ $param['status'] }}";
        let param = {
            status: status,
            institution_id: institutionId,
            class_id: classId
        };
        initTable(param);

        function initTable(param = null) {
            if (param) {
                $('#table-incomes').DataTable().destroy();
            }
            generateDataFilter({
                institution_id: institutionId,
                target_id: 'btn-main-filter'
            });
            columns = [
                {data: 'invoice_number', name: 'invoice_number'},
                {data: 'user', name: 'user'},
                {data: 'transaction_start_date', name: 'transaction_start_date'},
                {data: 'due_date', name: 'due_date'},
                {data: 'payment_status', name: 'payment_status'},
                {data: 'remaining_bill', name: 'remaining_bill'},
                {data: 'total_amount', name: 'total_amount'},
            ];
            let dt_route = base_url + '/incomes/ajax'
            dt_incomes = $('#table-incomes').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                ajax: {
                    url: dt_route,
                    data: param
                },
                columns: columns,
                drawCallback: function(settings, json) {
                    $('#inner-skeleton-income').addClass('d-none');
                    $('#inner-content-income').removeClass('d-none');
                    document.getElementById('inner-sidebar-' + param.institution_id).classList.remove('btn-default');
                    document.getElementById('inner-sidebar-' + param.institution_id).classList.add('btn-primary');
                    let api = this.api();
                    let data = api.rows( {page:'current'} ).data();
                    if (data.length) {
                        for (let a = 0; a < data.length; a++) {
                            let item = data[a].tippy_content;
                            let id = data[a].id;
                            tippy('#index-invoice-number-' + id, {
                              content: item,
                              allowHTML: true,
                            });
                        }
                    }
                },
                order: [
                    [0, 'desc']
                ],
            });
        }

        function filterInvoice(id) {
            let elems = $('.btn-filter');
            for (let a = 0; a < elems.length; a++) {
                elems[a].classList.remove('active');
            }
            $('#btn-' + id).addClass('active');
            let status, param;
            if (id == 'all') {
                status = 0;
            } else if (id == 'paid') {
                status = 1
            } else if (id == 'unpaid') {
                status = 3;
            } else if (id == 'partially') {
                status = 2;
            }
            param = {status: status};
            initTable(param);
        }
    </script>
@endpush