function createMethod(text) {
    openModalWithValue(
        'GET',
        'form-income-method',
        'modalIncomeMethod',
        'modalIncomeMethodLabel',
        text,
        base_url + '/income/method/create'
    );
}

function updateForm(id, text) {
    openModalWithValue(
        'GET',
        'form-income-method',
        'modalIncomeMethod',
        'modalIncomeMethodLabel',
        text,
        base_url + '/income/method/' + id + '/edit'
    );
}

function saveItem() {
    let form = $('#form-income-method');
    let data = form.serialize();
    let method = form.attr('method');
    let url = form.attr('action');
    let status = 0;
    if ($('#status').prop('checked')) {
        status = 1;
    }

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
            closeModal('modalIncomeMethod');
            dt_income_method.ajax.reload();
        },
        error: function(err) {;
            disableButton('btn-save', false);
            disableButton('btn-cancel', false);
            showNotif(true, err);
        }
    })
}

function deleteItem(id, text) {
    let url = base_url + `/income/method/${id}`;
    deleteMaster(
        text,
        'Yes! Delete it',
        'Cancel',
        url,
        dt_income_method
    );
}

window.createMethod = createMethod;
window.updateForm = updateForm;
window.saveItem = saveItem;
window.deleteItem = deleteItem;