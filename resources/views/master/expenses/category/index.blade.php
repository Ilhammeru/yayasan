@extends('layouts.base')

@section('content')
    <div class="block full">
        <div class="block-title">
            <h2>{{ __('view.category_list') }}</h2>
            <div style="padding: 0 15px;">
                <button class="btn btn-primary btn-sm" type="button" onclick="createCategory(`{{ __('view.create_category') }}`)">{{ __('view.create') }}</button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="table-expense_categories" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr class="text-secondary text-muted">
                        <th class="text-center">
                            <small>#</small>
                        </th>
                        <th>
                            <small>Name</small>
                        </th>
                        <th class="text-center">
                            <small>Action</small>
                        </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    {{-- modal --}}
    <div class="modal fade" id="modalExpenseCategory" tabindex="-1" aria-labelledby="modalExpenseCategoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form action="" id="form-expense-category" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalExpenseCategoryLabel"></h1>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btn-save" onclick="closeModal('modalExpenseCategory')">Close</button>
                        <button type="button" class="btn btn-primary" id="btn-cancel" onclick="saveItem()">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script src="{{ mix('dist/js/user.js') }}"></script> --}}
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
        let dt_route = base_url + '/expenses/category/ajax'
        let dt_expense_category = setDataTable(
            'table-expense_categories',
            columns,
            dt_route
        );

        function createCategory(text, type) {
            openModalWithValue(
                'GET',
                'form-expense-category',
                'modalExpenseCategory',
                'modalExpenseCategoryLabel',
                text,
                base_url + '/expenses/category/create'
            );
        }

        function updateForm(id, text, type) {
            openModalWithValue(
                'GET',
                'form-expense-category',
                'modalExpenseCategory',
                'modalExpenseCategoryLabel',
                text,
                base_url + '/expenses/category/' + id + '/edit'
            );
        }

        function saveItem() {
            let form = $('#form-expense-category');
            let data = form.serialize();
            let method = form.attr('method');
            let url = form.attr('action');
            let status = 0;
            if ($('#status').prop('checked')) {
                status = 1;
            }

            $.ajax({
                type: method,
                url: url,
                data: data,
                beforeSend: function() {
                    disableButton('btn-save');
                    disableButton('btn-cancel');
                },
                 success: function(res) {
                    disableButton('btn-save', false);
                    disableButton('btn-cancel', false);
                    showNotif(false, res.message);
                    closeModal('modalExpenseCategory');
                    dt_expense_category.ajax.reload();
                },
                error: function(err) {
                     ;
                    disableButton('btn-save', false);
                    disableButton('btn-cancel', false);
                    showNotif(true, err);
                }
            })
        }

        function deleteItem(id, text) {
            let url = base_url + `/expenses/category/${id}`;
            deleteMaster(
                text,
                'Yes! Delete it',
                'Cancel',
                url,
                dt_expense_category
            );
        }
    </script>
@endpush