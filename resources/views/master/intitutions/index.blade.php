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
    <script src="{{ mix('dist/js/intitution.js') }}"></script> 
    <script>
        dtIntegration();
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
        let dttable = setDataTable(
            'example-datatable',
            columns,
            dt_route
        );

        /* Add placeholder attribute to the search input */
        $('.dataTables_filter input').attr('placeholder', 'Search');

        function deleteItem(id) {
            let url = "{{ route('intitutions.destroy', '_ID_') }}";
            url = url.replace('_ID_', id);
            deleteMaster(
                "{{ __('view.delete_text') }}",
                "{{ __('view.confirm_delete') }}",
                "{{ __('view.cancel_delete') }}",
                url,
                dttable
            );
        }

        var baseUrl = window.location.origin;

        function saveItem() {
            let form = $('#form-intitution');
            let data = form.serialize();
            let url = form.attr('action');
            let method = form.attr('method');
            let classWrapper = $('.class-wrapper');
            for (let a = 0; a < classWrapper.length; a++) {
                let classInput = $(`.class-input-${a}`).val();
                let levelInput = $(`.level-input-${a}`).val();
                if (levelInput != '' && classInput == '') {
                    return showNotif(true, 'Class cannot be empty if level of class is declare');
                }
            }

            $.ajax({
                type: method,
                url: url,
                data: data,
                beforeSend: function() {
                    $('#btn-save').prop('disabled', true);
                    $('#btn-cancel').prop('disabled', true);
                },
                success: function(res) {
                    $('#btn-save').prop('disabled', false);
                    $('#btn-cancel').prop('disabled', false);
                    dttable.ajax.reload();
                    showNotif(false, res.message);
                    closeModal('modalIntitution');
                },
                error: function(err) {
                    $('#btn-save').prop('disabled', false);
                    $('#btn-cancel').prop('disabled', false);
                    showNotif(true, err);
                }
            })
        }

        function hasClass() {
            let elem = $('input[name="has_class"]')[0].checked;
            if (elem) {
                $('.class-container').removeClass('d-none');
                let all = $('.class-wrapper');
                for (let a = 0; a < all.length; a++) {
                    if (a != 0) {
                        let id = all[a].id;
                        $('#' + id).remove();
                    }
                }
                $('input[name="ins[0][class_name]"]').val('');
                $('input[name="ins[0][class][0][level]"]').val('');
                $('#target-class-level-0').html('');
            } else {
                $('.class-container').addClass('d-none');
            }
        }

        function createForm(createText) {
            let url = baseUrl + '/intitutions/create';
            let storeUrl = baseUrl + '/intitutions';
            $.ajax({
                type: "GET",
                url: url,
                dataType: 'json',
                success: function(res) {
                    $('#modalIntitutionLabel').text(createText);
                    $('#modalIntitution .modal-body').html(res.view);
                    $('#modalIntitution').modal('show');
                    $('#form-intitution').attr('action', storeUrl);
                    $('#form-intitution').attr('method', "POST");
                },
                error: function(err) {
                    showNotif(true, err);
                }
            })
        }

        function appendLevel(classId) {
            let current = $('.level-helper-s');
            let classWrapper = $('.class-wrapper');
            let levelWrapper = $('.level-wrapper-' + classId);
            let classLen = classWrapper.length;
            let len = levelWrapper.length;
            let elem = `
                <div class="col-md-6 level-wrapper-${classId}" id="level-helper-f-${len}-${classLen}"></div>
                <div class="col-md-6" id="level-helper-s-${len}-${classLen}">
                    <div class="input-group">
                        <input type="text" id="class_level-${len}-${classId}" name="ins[${classId}][class][${len}][level]" class="form-control form-control-sm level-input-${classId}" placeholder="A / B / C / etc" required>
                        <span class="input-group-addon"><i class="gi gi-remove_2" onclick="deleteLevel(${len}, ${classLen})" style="color: red; cursor: pointer;"></i></span>
                    </div>
                </div>
            `;
            $(`#target-class-level-${classId}`).append(elem);
            $(`#class_level-${len}-${classId}`).focus();
        }

        function appendClass(labelName, labelLevel) {
            let elems = $('.class-wrapper');
            let len = elems.length;
            let form = `
                <div class="border p-3 mb-3 class-wrapper" id="class-wrapper-${len}" style="position: relative; width: 100%;">
                    <span class="gi gi-remove text-danger" onclick="deleteClassRow(${len})" style="position: absolute; top: -4px; right: -2px; font-size: 18px; cursor: pointer;"></span>
                    <div class="row">
                        <div class="col-md-6 col-sm-12 level-wrapper-${len}">
                            <div class="form-group mb-3">
                                <label for="class_name" class="control-label">${labelName}</label>
                                <input type="text" name="ins[${len}][class_name]" placeholder="${labelName}" class="form-control form-control-sm class-input-${len}" id="class_name-${len}">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label for="class_level" class="control-label">${labelLevel}</label>
                                <div class="input-group">
                                    <input type="text" id="class_level" name="ins[${len}][class][0][level]" class="form-control form-control-sm level-input-${len}" placeholder="A / B / C / etc" required>
                                    <span class="input-group-addon"><i class="gi gi-plus" onclick="appendLevel(${len})" style="cursor: pointer;"></i></span>
                                </div>
                            </div>
                        </div>
                        <div id="target-class-level-${len}"></div>
                    </div>
                </div>
            `;
            $('#target-class').append(form);
            $(`#class_name-${len}`).focus();
        }

        function deleteClassRow(id) {
            $(`#class-wrapper-${id}`).remove();
        }

        function deleteLevel(id, classId) {
            $(`#level-helper-f-${id}-${classId}`).remove();
            $(`#level-helper-s-${id}-${classId}`).remove();
        }

        function updateForm(id, createText = 'Update institution') {
            let url = baseUrl + `/intitutions/${id}/edit`;
            let storeUrl = baseUrl + `/intitutions/${id}`;
            $.ajax({
                type: "GET",
                url: url,
                dataType: 'json',
                success: function(res) {
                    $('#modalIntitutionLabel').text(createText);
                    $('#modalIntitution .modal-body').html(res.view);
                    $('#modalIntitution').modal('show');
                    $('#form-intitution').attr('action', storeUrl);
                    $('#form-intitution').attr('method', "PUT");
                },
                error: function(err) {
                    showNotif(true, err);
                }
            })
        }
    </script>
@endpush