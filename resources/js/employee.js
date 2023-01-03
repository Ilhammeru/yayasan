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

window.createEmployee = createEmployee;
window.saveItem = saveItem;
window.deleteItem = deleteItem;
window.updateForm = updateForm;