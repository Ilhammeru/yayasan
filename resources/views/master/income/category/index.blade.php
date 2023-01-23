@extends('layouts.base')

@section('content')
	<div class="block full">
        <div class="block-title">
            <h2>{{ __('view.income_list') }}</h2>
            <div style="padding: 0 15px;">
                <button class="btn btn-primary btn-sm" type="button" onclick="createCategory(`{{ __('view.create_income_category') }}`)">{{ __('view.create') }}</button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="table-income-category" class="table table-vcenter table-condensed row-border">
                <thead>
                    <tr>
                    	<th>#</th>
                        <th>{{ __('view.name') }}</th>
                        <th>{{ __('view.income_type') }}</th>
                        <th>{{ __('view.action') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    {{-- modal --}}
    <div class="modal fade" id="modalIncomeCategory" tabindex="-1" aria-labelledby="modalIncomeCategoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form action="" id="form-income-category" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalIncomeCategoryLabel"></h1>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btn-cancel" onclick="closeModal('modalIncomeCategory')">Close</button>
                        <button type="button" class="btn btn-primary" id="btn-save" onclick="saveItem()">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('dist/js/income.js') }}"></script>
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
            {data: 'income_type_id', name: 'income_type_id'},
            {data: 'action', name: 'action', className: 'text-center', orderable: false},
        ];
        let dt_route = base_url + '/income/category/ajax'
        let dt_income_category = setDataTable(
            'table-income-category',
            columns,
            dt_route
        );
    </script>
@endpush