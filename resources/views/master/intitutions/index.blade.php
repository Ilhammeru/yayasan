@extends('layouts.base')

@section('content')

    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <h2>{{ __('view.intitution_list') }}</h2>
            <div style="padding: 0 15px;">
                <button class="btn btn-primary btn-sm" type="button" onclick="createForm('{{ __('view.create_intitution') }}')">{{ __('view.create') }}</button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr class="text-secondary text-muted">
                        <th class="text-center">
                            <small>#</small>
                        </th>
                        <th>
                            <small>Name</small>
                        </th>
                        <th>
                            <small>Total Class</small>
                        </th>
                        <th>
                            <small>Status</small>
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
    <div class="modal fade" id="modalIntitution" tabindex="-1" aria-labelledby="modalIntitutionLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form action="" id="form-intitution">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalIntitutionLabel"></h1>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btn-save" onclick="closeModal('modalIntitution')">Close</button>
                        <button type="button" class="btn btn-primary" id="btn-cancel" onclick="saveItem()">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('dist/js/intitution.js') }}"></script> 
    <script>
         App.datatables();
        /* Initialize Datatables */
        let columns = [
            {data: 'id',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                width: '5%',
                className: 'text-center'
            },
            {data: 'name', name: 'name'},
            {data: 'total_class', name: 'total_class'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', className: 'text-center', orderable: false},
        ];
        let dt_route = "{{ route('intitutions.ajax') }}"
        let dttable = App.setDataTable(
            'example-datatable',
            columns,
            dt_route
        );

        /* Add placeholder attribute to the search input */
        $('.dataTables_filter input').attr('placeholder', 'Search');

        function deleteItem(id) {
            let url = "{{ route('intitutions.destroy', '_ID_') }}";
            url = url.replace('_ID_', id);
            App.deleteMaster(
                "{{ __('view.delete_text') }}",
                "{{ __('view.confirm_delete') }}",
                "{{ __('view.cancel_delete') }}",
                url,
                dttable
            );
        }
    </script>
@endpush