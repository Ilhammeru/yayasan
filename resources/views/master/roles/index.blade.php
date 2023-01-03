@extends('layouts.base')

@section('content')
    <div class="block full">
        <div class="block-title">
            <h2>{{ __('view.role_list') }}</h2>
            <div style="padding: 0 15px;">
                <button class="btn btn-primary btn-sm" type="button" onclick="createRole(`{{ __('view.create_role') }}`)">{{ __('view.create') }}</button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="table-roles" class="table table-vcenter table-condensed table-bordered">
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
    <div class="modal fade" id="modalRole" tabindex="-1" aria-labelledby="modalRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="" id="form-role">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalRoleLabel"></h1>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btn-save" onclick="closeModal('modalRole')">Close</button>
                        <button type="button" class="btn btn-primary" id="btn-cancel" onclick="saveItem()">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script src="{{ mix('dist/js/role.js') }}"></script>  --}}
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
            {data: 'action', name: 'action', className: 'text-center', orderable: false},
        ];
        let dt_route = base_url + '/roles/ajax'
        let dt_role = setDataTable(
            'table-roles',
            columns,
            dt_route
        );

        function deleteItem(id, text) {
            let url = base_url + `/roles/${id}`
            deleteMaster(
                text,
                'Yes! Delete it',
                'Cancel',
                url,
                dt_role
            );
        }

        function createRole(text) {
            $.ajax({
                type: 'GET',
                url: base_url + '/roles/create',
                beforeSend: function() {
                    
                },
                success: function(res) {
                    $('#modalRoleLabel').text(text);
                    $('#form-role').attr('action', base_url + '/roles');
                    $('#form-role').attr('method', 'POST');
                    $('#modalRole .modal-body').html(res.view);
                    $('#modalRole').modal('show');
                },
                error: function(err) {
                    showNotif(true, err.responseJSON);
                }
            })
        }

        function updateForm(id, text) {
            let url = base_url + `/roles/${id}`
            $.ajax({
                type: 'GET',
                url: base_url + `/roles/${id}/edit`,
                beforeSend: function() {
                    
                },
                success: function(res) {
                    buildModalBody(text, url, res.view, 'PUT');
                },
                error: function(err) {
                    showNotif(true, err.responseJSON);
                }
            })
        }

        function buildModalBody(text, url, view, method) {
            $('#modalRoleLabel').text(text);
            $('#form-role').attr('action', url);
            $('#form-role').attr('method', method);
            $('#modalRole .modal-body').html(view);
            $('#modalRole').modal('show');
        }

        function saveItem() {
            let form = $('#form-role');
            let method = form.attr('method');
            let url = form.attr('action');
            let data = form.serialize();

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
                    dt_role.ajax.reload();
                    closeModal('modalRole');
                },
                error: function(err) {
                    disableButton('btn-save', false);
                    disableButton('btn-cancel', false);
                    showNotif(true, err.responseJSON);
                }
            })
        }
    </script>
@endpush