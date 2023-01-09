@extends('layouts.base')

@section('content')
	<div class="block full">
        <div class="block-title">
            <h2>{{ __('view.income_method') }}</h2>
            <div style="padding: 0 15px;">
                <button class="btn btn-primary btn-sm" type="button" onclick="createMethod(`{{ __('view.create_income_method') }}`)">{{ __('view.create') }}</button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="table-income-method" class="table table-vcenter table-condensed row-border">
                <thead>
                    <tr>
                    	<th>#</th>
                        <th>{{ __('view.name') }}</th>
                        <th>{{ __('view.action') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    {{-- modal --}}
    <div class="modal fade" id="modalIncomeMethod" tabindex="-1" aria-labelledby="modalIncomeMethodLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form action="" id="form-income-method" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalIncomeMethodLabel"></h1>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btn-cancel" onclick="closeModal('modalIncomeMethod')">Close</button>
                        <button type="button" class="btn btn-primary" id="btn-save" onclick="saveItem()">{{ __('view.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('dist/js/incomeMethod.js') }}"></script>
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
            {data: 'name', name: 'name'},
            {data: 'action', name: 'action', className: 'text-center', orderable: false},
        ];
        let dt_route = base_url + '/income/method/ajax'
        let dt_income_method = setDataTable(
            'table-income-method',
            columns,
            dt_route
        );
    </script>
@endpush