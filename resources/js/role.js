App.datatables();

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
let dt_role = App.setDataTable(
    'table-roles',
    columns,
    dt_route
);

function deleteItem(id, text) {
    let url = base_url + `/roles/${id}`
    App.deleteMaster(
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
            App.setNotif(true, err.responseJSON);
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
            App.setNotif(true, err.responseJSON);
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
            App.setNotif(false, res.message);
            dt_role.ajax.reload();
            closeModal('modalRole');
        },
        error: function(err) {
            disableButton('btn-save', false);
            disableButton('btn-cancel', false);
            App.setNotif(true, err.responseJSON);
        }
    })
}

window.deleteItem = deleteItem;
window.createRole = createRole;
window.updateForm = updateForm;
window.saveItem = saveItem;
window.buildModalBody = buildModalBody;