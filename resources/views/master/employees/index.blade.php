@extends('layouts.base')

@section('content')
    <div class="block full">
        <div class="block-title">
            <h2>{{ __('view.position_list') }}</h2>
            <div style="padding: 0 15px;">
                <button class="btn btn-primary btn-sm" type="button" onclick="createEmployee(`{{ __('view.create_employee') }}`)">{{ __('view.create') }}</button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="table-employee" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr class="text-secondary text-muted">
                        <th class="text-center">
                            <small>#</small>
                        </th>
                        <th>
                            <small>{{ __('view.name') }}</small>
                        </th>
                        <th>
                            <small>{{ __("view.position") }}</small>
                        </th>
                        <th>
                            <small>{{ __("view.intitutions") }}</small>
                        </th>
                        <th>
                            <small>NIP</small>
                        </th>
                        <th>
                            <small>{{ __('view.phone') }}</small>
                        </th>
                        <th>
                            <small>{{ __('view.email') }}</small>
                        </th>
                        <th>
                            <small>{{ __('view.status') }}</small>
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
    <div class="modal fade" id="modalEmployee" tabindex="-1" aria-labelledby="modalEmployeeLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form action="" id="form-employee" class="form-horizontal">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalEmployeeLabel"></h1>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btn-cancel" onclick="closeModal('modalEmployee')">Close</button>
                        <button type="button" class="btn btn-primary" id="btn-save" onclick="saveItem()">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script src="{{ mix('dist/js/employee.js') }}"></script>  --}}
    <script>
        dtIntegration();

        let columns = [
            {data: 'id',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                width: '5%',
                className: 'text-center'
            },
            {data: 'name', name: 'name'},
            {data: 'position_id', name: 'position_id'},
            {data: 'institution_id', name: 'institution_id'},
            {data: 'nip', name: 'nip'},
            {data: 'phone', name: 'phone'},
            {data: 'email', name: 'email'},
            {data: 'status', name: 'status', orderable: false},
            {data: 'action', name: 'action', className: 'text-center', orderable: false},
        ];
        let dt_route = base_url + '/employees/ajax'
        let dt_employee = setDataTable(
            'table-employee',
            columns,
            dt_route
        );

        function createEmployee(text) {
            openModalWithValue(
                'GET',
                'form-employee',
                'modalEmployee',
                'modalEmployeeLabel',
                text,
                base_url + '/employees/create'
            );
        }

        function updateForm(id, text) {
            openModalWithValue(
                'GET',
                'form-employee',
                'modalEmployee',
                'modalEmployeeLabel',
                text,
                base_url + '/employees/' + id + '/edit'
            );
        }

        function saveItem() {
            let form = $('#form-employee');
            let data = form.serialize();
            let method = form.attr('method');
            let url = form.attr('action');
            let status = 0;
            if ($('#status').prop('checked')) {
                status = 1;
            }
            data = data + '&status=' + status;

            $.ajax({
                type: method,
                url: url,
                data: data,
                beforeSend: function() {
                    disableButton('btn-save');
                    disableButton('btn-cancel');
                },
                success: function(res) {
                    ;
                    disableButton('btn-save', false);
                    disableButton('btn-cancel', false);
                    showNotif(false, res.message);
                    closeModal('modalEmployee');
                    dt_employee.ajax.reload();
                },
                error: function(err) {
                    disableButton('btn-save', false);
                    disableButton('btn-cancel', false);
                    showNotif(true, err);
                }
            })
        }

        function deleteItem(id, text) {
            let url = base_url + `/employees/${id}`;
            deleteMaster(
                text,
                'Yes! Delete it',
                'Cancel',
                url,
                dt_employee
            );
        }
    </script>
@endpush