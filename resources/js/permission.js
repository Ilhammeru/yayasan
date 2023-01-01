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
let dt_route = base_url + '/permissions/ajax'
let dt_permission = setDataTable(
    'table-permissions',
    columns,
    dt_route
);

function createPermission(text) {
    openModalWithValue(
        'GET',
        'form-permission',
        'modalPermission',
        'modalPermissionLabel',
        text,
        base_url + '/permissions/create'
    );
}

function updateForm(id, text) {
    openModalWithValue(
        'GET',
        'form-permission',
        'modalPermission',
        'modalPermissionLabel',
        text,
        base_url + '/permissions/' + id + '/edit'
    );
}

function saveItem() {
    let form = $('#form-permission');
    let data = form.serialize();
    let method = form.attr('method');
    let url = form.attr('action');

    $.ajax({
        type: method,
        url: url,
        data: data,
        beforeSend: function() {
            disableButton('btn-save');
            disableButton('btn-cancel');
        },
        success: function(res) {
            console.log('res',res);
            disableButton('btn-save', false);
            disableButton('btn-cancel', false);
            showNotif(false, res.message);
            closeModal('modalPermission');
            dt_permission.ajax.reload();
        },
        error: function(err) {
            disableButton('btn-save', false);
            disableButton('btn-cancel', false);
            showNotif(true, err);
        }
    })
}

function deleteItem(id, text) {
    let url = base_url + `/permissions/${id}`;
    deleteMaster(
        text,
        'Yes! Delete it',
        'Cancel',
        url,
        dt_permission
    );
}

window.createPermission = createPermission;
window.saveItem = saveItem;
window.updateForm = updateForm;
window.deleteItem = deleteItem;