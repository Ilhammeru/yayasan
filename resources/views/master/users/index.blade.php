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

        <div class="table-responsive">
            <table id="table-users" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    @if ($type == 'internal')
                        @include('master.users.components.table_internal')
                    @endif
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    {{-- modal --}}
    <div class="modal fade" id="modalUser" tabindex="-1" aria-labelledby="modalUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="" id="form-user" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalUserLabel"></h1>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btn-save" onclick="closeModal('modalUser')">Close</button>
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
        @endif
        let dt_route = base_url + '/users/ajax/' + user_type
        let dt_user = setDataTable(
            'table-users',
            columns,
            dt_route
        );

        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            var min = parseInt($('#min').val(), 10);
            var max = parseInt($('#max').val(), 10);
            var age = parseFloat(data[3]) || 0; // use data for the age column
        
            if (
                (isNaN(min) && isNaN(max)) ||
                (isNaN(min) && age <= max) ||
                (min <= age && isNaN(max)) ||
                (min <= age && age <= max)
            ) {
                return true;
            }
            return false;
        });

        $('#min, #max').keyup(function () {
            dt_user.draw();
        });

        function createUser(text, type) {
            openModalWithValue(
                'GET',
                'form-user',
                'modalUser',
                'modalUserLabel',
                text,
                base_url + '/users/create/' + type
            );
        }

        function updateForm(id, text, type) {
            openModalWithValue(
                'GET',
                'form-user',
                'modalUser',
                'modalUserLabel',
                text,
                base_url + '/users/' + id + '/edit' + '/' + type
            );
        }

        function saveItem() {
            let form = $('#form-user');
            let data = new FormData($('#form-user')[0]);
            let method = form.attr('method');
            let url = form.attr('action');
            let status = 0;
            if ($('#status').prop('checked')) {
                status = 1;
            }
            data.append('status', status);

            $.ajax({
                type: method,
                url: url,
                data: data,
                contentType: false,
                processData: false, 
                beforeSend: function() {
                    disableButton('btn-save');
                    disableButton('btn-cancel');
                },
                 success: function(res) {
                    disableButton('btn-save', false);
                    disableButton('btn-cancel', false);
                    showNotif(false, res.message);
                    closeModal('modalUser');
                    dt_user.ajax.reload();
                },
                error: function(err) {
                     ;
                    disableButton('btn-save', false);
                    disableButton('btn-cancel', false);
                    showNotif(true, err);
                }
            })
        }

        function getClasses(e) {
            let val = e.value;
            $.ajax({
                type: 'POST',
                url: "{{ route('users.get-class') }}",
                data: {
                    institution_id: val
                },
                beforeSend: function() {
                    $('#institution_class_id').chosen('destroy');
                    $('#institution_class_id').prop('disabled', true);
                    $('#institution_class_id').html('');
                    $('#institution_class_level_id').chosen('destroy');
                    $('#institution_class_level_id').html('');
                    $('#institution_class_level_id').prop('disabled', true);
                },
                success: function(res) {
                    let data = res.data;
                    let option = `<option></option>`;
                    for (let a = 0; a < data.length; a++) {
                        option += `<option value="${data[a].id}">${data[a].name}</option>`;
                    }
                    $('#institution_class_id').html(option);
                    $('#institution_class_id').prop('disabled', false);
                    $('#institution_class_id').chosen({width: '100%'});
                },
                error: function(err) {
                    showNotif(true, err);
                }
            })
        }

        function getLevels(e) {
            let val = e.value;
            $.ajax({
                type: 'POST',
                url: "{{ route('users.get-level') }}",
                data: {
                    class_id: val
                },
                beforeSend: function() {
                    $('#institution_class_level_id').chosen('destroy');
                    $('#institution_class_level_id').html('');
                    $('#institution_class_level_id').prop('disabled', true);
                },
                success: function(res) {
                    let data = res.data;
                    let option = `<option></option>`;
                    for (let a = 0; a < data.length; a++) {
                        option += `<option value="${data[a].id}">${data[a].name}</option>`;
                    }
                    $('#institution_class_level_id').prop('disabled', false);
                    $('#institution_class_level_id').html(option);
                    $('#institution_class_level_id').chosen({width: '100%'});
                },
                error: function(err) {
                    setNotif(true, err.responseJSON);
                }
            })
        }

        function selectImage() {
            $('#user-image').click();
        }

        function showImage(event) {
            var reader = new FileReader();
            reader.onload = function()
            {
                $('#preview-image').css({
                    'backgroundImage': `url(${reader.result})`
                });
            }
            reader.readAsDataURL(event.target.files[0]);
            $('#icon-action-image').removeClass("fa-camera");
            $('#icon-action-image').addClass("fa-times");
            $('#icon-action-image').css({
                'color': 'red'
            });
            $('#icon-action-image').attr('onclick', 'removePreviewImage()');
        }

        function removePreviewImage(edit = null) {
            $('#preview-image').css({
                'backgroundImage': 'url(../assets/img/blank.png)'
            });
            $('#icon-action-image').addClass("fa-camera");
            $('#icon-action-image').removeClass("fa-times");
            $('#icon-action-image').attr('onclick', 'selectImage()');
            $('#icon-action-image').css({
                'color': 'black'
            });
            $('#user-image').val('');
            if (edit) {
                $('#is_delete_image').val(1);
            }
        }

        function showProfile(type, id, text) {
            let url = base_url + '/users/' + id + '/' + type + '/show';
            openModalWithValue(
                'GET',
                'form-user',
                'modalUser',
                'modalUserLabel',
                text,
                url,
                null,
                true
            );
        }

        function deleteItem(id, text, type) {
            let url = base_url + `/users/${id}/${type}`;
            deleteMaster(
                text,
                'Yes! Delete it',
                'Cancel',
                url,
                dt_user
            );
        }
    </script>
@endpush