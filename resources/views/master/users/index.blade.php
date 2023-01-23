@extends('layouts.base')

@push('styles')
    <style>
        .user-internal .header-image {
            justify-content: center;
            align-content: center;
            display: flex;
        }

        .user-internal .header-image img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
        }
        .user-internal .header-text .name {
            font-weight: bold;
            font-size: 18px;
            margin: 0;
        }
        .user-internal .header-text .nis {
            font-size: 16px;
            margin: 0;
        }
        /* Style the tab */
        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }

        /* Style the buttons inside the tab */
        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 8px 10px;
            transition: 0.3s;
            font-size: 17px;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
            background-color: #ddd;
        }

        /* Create an active/current tablink class */
        .tab button.active {
            background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border-top: none;
        }

        /* @if ($type == 'external') */
            .input-group:has(input[type="search"]) {
                display: none !important;
            }
            .col-sm-6:has(.dataTables_length) {
                display: none !important;
            }
            
            
        /* @endif */
    </style>
@endpush

@section('content')
    <div class="block full">
        <div class="block-title">
            <h2>{{ __('view.user_list') }}</h2>
            <div style="padding: 0 15px;">
                <button class="btn btn-primary btn-sm" type="button" onclick="createUser(`{{ __('view.create_user') }}`, `{{ $type }}`)">{{ __('view.create') }}</button>
            </div>
        </div>

        {{-- filter section --}}
        @if ($type == 'external')
            @include('master.users.components.search_external')
        @endif

        <div class="table-responsive">
            <table id="table-users" class="table table-vcenter table-condensed row-border">
                <thead>
                    @if ($type == 'internal')
                        @include('master.users.components.table_internal')
                    @else
                        @include('master.users.components.table_external', ['without_text' => false])
                    @endif
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    {{-- modal --}}
    <div class="modal fade" id="modalUser" tabindex="-1" aria-labelledby="modalUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-{{ $type == 'internal' ? 'lg' : 'md' }}">
            <div class="modal-content">
                <form action="" id="form-user" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalUserLabel"></h1>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btn-cancel" onclick="closeModal('modalUser')">Close</button>
                        <button type="button" class="btn btn-primary" id="btn-save" onclick="saveItem()">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('dist/js/user.js') }}"></script>
    <script src="/js/lang.js"></script> // this is for load all lang
    <script>
        $('#search-user-status').chosen({width: '100%'});

        DataTableIntegration();

        const user_type = "{{ $type }}";
        let columns = ''

        @if($type == 'internal')
            columns = [
                {data: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    width: '5%',
                    className: 'text-center'
                },
                {data: 'name', name: 'name'},
                {data: 'nis', name: 'nis'},
                {data: 'phone', name: 'phone'},
                {data: 'institution', name: 'institution'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', className: 'text-center', orderable: false},
            ];
        @else
            columns = [
                {data: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    width: '5%',
                    className: 'text-center'
                },
                {data: 'name', name: 'name'},
                {data: 'user_type', name: 'user_type', className: 'select-filter'},
                {data: 'phone', name: 'phone'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', className: 'text-center', orderable: false},
            ];   
        @endif
        let dt_route = base_url + '/users/ajax/' + user_type
        let dt_user = createDataTables(
            'table-users',
            columns,
            dt_route,
        );

        $('#user-type-search').chosen({width: '100%'});
        $('#user-status-search').chosen({width: '100%'});
    </script>
@endpush