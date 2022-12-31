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
            console.log('res',res);
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

function getCity(e, fromEdit = null) {
    let val;
    if (!fromEdit) {
        val = e.value;
    } else {
        val = e;
    }
    $.ajax({
        type: 'POST',
        url: base_url + '/get-city',
        data: {
            province_id: val
        },
        beforeSend: function() {
            $('#city_id').chosen('destroy');
            $('#city_id').html('');
            $('#city_id').prop('disabled', true);
            $('#district_id').chosen('destroy');
            $('#district_id').prop('disabled', true);
            $('#district_id').html('');
        },
        success: function(res) {
            let data = res.data;
            let opt = '<option></option>';
            for (let a = 0; a < data.length; a++) {
                let selected = '';
                if (fromEdit) {
                    if (fromEdit == data[a].id) {
                        selected = 'selected';
                    }
                }
                opt += `<option value="${data[a].id}" ${selected}>${data[a].name}</option>`;
            }
            $('#city_id').html(opt);
            $('#city_id').prop('disabled', false);
            $('#city_id').chosen({width: "100%"});
        },
        error: function(err) {
            App.setNotif(true, err);
        }
    })
}

function getDistrict(e, fromEdit = null) {
    let val;
    if (!fromEdit) {
        val = e.value;
    } else {
        val = e;
    }
    $.ajax({
        type: 'POST',
        url: base_url + '/get-district',
        data: {
            city_id: val
        },
        beforeSend: function() {
            $('#district_id').chosen('destroy');
            $('#district_id').html('');
            $('#district_id').prop('disabled', true);
        },
        success: function(res) {
            let data = res.data;
            let opt = '<option></option>';
            for (let a = 0; a < data.length; a++) {
                let selected = '';
                if (fromEdit) {
                    if (fromEdit == data[a].id) {
                        selected = 'selected';
                    }
                }
                opt += `<option value="${data[a].id}" ${selected}>${data[a].name}</option>`;
            }
            $('#district_id').html(opt);
            $('#district_id').prop('disabled', false);
            $('#district_id').chosen({width: "100%"});
        },
        error: function(err) {
            App.setNotif(true, err);
        }
    })
}

window.createEmployee = createEmployee;
window.saveItem = saveItem;
window.deleteItem = deleteItem;
window.updateForm = updateForm;
window.getCity = getCity;
window.getDistrict = getDistrict;