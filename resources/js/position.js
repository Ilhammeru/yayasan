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
    {data: 'role_id', name: 'role_id'},
    {data: 'action', name: 'action', className: 'text-center', orderable: false},
];
let dt_route = base_url + '/positions/ajax'
let dt_position = setDataTable(
    'table-positions',
    columns,
    dt_route
);

function createPosition(text) {
    openModalWithValue(
        'GET',
        'form-position',
        'modalPosition',
        'modalPositionLabel',
        text,
        base_url + '/positions/create'
    );
}

function updateForm(id, text) {
    openModalWithValue(
        'GET',
        'form-position',
        'modalPosition',
        'modalPositionLabel',
        text,
        base_url + '/positions/' + id
    );
}

function saveItem() {
    let form = $('#form-position');
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
            disableButton('btn-save', false);
            disableButton('btn-cancel', false);
            showNotif(false, res.message);
            closeModal('modalPosition');
            dt_position.ajax.reload();
        },
        error: function(err) {
            showNotif(true, err);
        }
    })
}

function deleteItem(id, text) {
    let url = base_url + `/positions/${id}`
    deleteMaster(
        text,
        'Yes! Delete it',
        'Cancel',
        url,
        dt_position
    );
}

window.createPosition = createPosition;
window.saveItem = saveItem;
window.deleteItem = deleteItem;
window.updateForm = updateForm;