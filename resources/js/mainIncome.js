$('#user').chosen({
    width: '100%'
});
$('#institution_id').chosen({
    width: '100%'
});
$('#income_type_id').chosen({
    width: '100%'
});
$('#income_method_id').chosen({
    width: '100%'
});

function getDetailUser(e) {
    let val = e.value;
    $.ajax({
        type: "POST",
        url: base_url + '/incomes/get-detail-user',
        data: {
            user_id: val
        },
        success: function(res) {
            $('.detail-user').html(res.view);
            let option = '<option></option>';
            for (let a = 0; a < res.data.institutions.length; a++) {
                option += `<option value="${res.data.institutions[a].id}" ${res.data.institutions[a].selected}>
            			${res.data.institutions[a].name}
            		</option>`;
            }
            $('#institution_id').html(option);
            if (res.data.type == 'internal') {
                $('#institution_id').prop('readonly', true);
            } else {
                $('#institution_id').prop('readonly', false);
            }

            $('#institution_id').chosen('destroy');
            $('#institution_id').chosen({
                width: '100%'
            });
        }
    })
}

function checkInvoiceNumber(e) {
    let val = e.value;
    $.ajax({
        type: "POST",
        url: base_url + '/incomes/check-invoice-number',
        data: {
            invoice_number: val
        },
        success: function(res) {
            if (!res.data.available) {
                $('#form-group-invoice-number').addClass('has-warning');
                $('#form-group-invoice-number .help-block').removeClass('d-none');
                $('#btn-save-invoice').prop('disabled', true);
            } else {
                $('#form-group-invoice-number').removeClass('has-warning');
                $('#form-group-invoice-number .help-block').addClass('d-none');
                $('#btn-save-invoice').prop('disabled', false);
            }
        }
    })
}

function addItem() {
    let table = $('.table-item');

    // append new row
    let tr = $('.tr-item');
    let len = tr.length;
    $.ajax({
        type: "POST",
        url: base_url + '/incomes/build-item-row',
        data: {
            len: len,
        },
        success: function(res) {
            $('.table-item tbody').append(res.view);
            $('.td-delete-0').removeClass('d-none');
            $('.th-additional').removeClass('d-none');
        }
    })
}

function deleteRow(id) {
    let tr = $('.tr-item');
    $('#tr-item-' + id).remove();
    if (tr.length == 2) {
        $('.td-delete-0').addClass('d-none');
        $('.th-additional').addClass('d-none');
    }
}

function updateTotal() {
    let inputs = $('.price_item');
    let val = [];
    let ids = [];
    for (let a = 0; a < inputs.length; a++) {
        let id = inputs[a].id;
        ids.push(id);
        val.push($('#' + id).val());
    }
    const sum = val.reduce((partialSum, a) => parseInt(partialSum) + parseInt(a), 0);
    res = numberWithCommas(sum);

    $('#amount_total').val(res);
    $('#remaining_bill').val(res);
}

function updateValue(e) {
    let val = e.value;
    e.value = parseInt(val);
}

function saveItem() {
    let form = $('#form-invoice');
    let data = new FormData($('#form-invoice')[0]);
    let ckEditor = CKEDITOR.instances.ckeditor_message.getData();
    data.append('message', ckEditor);
    $.ajax({
        type: "POST",
        url: base_url + '/incomes',
        data: data,
        contentType: false,
        processData: false,
        beforeSend() {
            disableButton('btn-save-invoice');
        },
        success: function(res) {
            disableButton('btn-save-invoice', false);
            showNotif(false, res.message);
            window.location.href = base_url + '/incomes/' + res.data.item.id;
        },
        error: function(err) {
            disableButton('btn-save-invoice', false);
            showNotif(true, err);
        }
    })
}

function appendPaymentDetail(id) {
    let totalItem = $('#total-item-td');
    let remaining = $('#remaining-bill-td');
    $.ajax({
        type: "POST",
        url: base_url + '/incomes/append-payment-detail',
        data: {
            income_id: id
        },
        beforeSend: function() {
            // set preloading item / skeleton

        },
        success: function(res) {
            $('#target-payment-detail').html(res.data.tr);
        }
    })
}

function pay(id) {
    let form = $('#form-payment');
    let data = new FormData($('#form-payment')[0]);
    $.ajax({
        type: "POST",
        url: base_url + '/incomes/pay',
        data: data,
        contentType: false,
        processData: false,
        beforeSend: function() {
            disableButton('btn-add-payment', true, i18n.view.pay);
        },
        success: function(res) {
            showNotif(false, res.message);
            disableButton('btn-add-payment', false, i18n.view.pay);
            initTransaction(res.data);
            appendPaymentDetail(res.data);
        },
        error: function(err) {
            showNotif(true, err);
            disableButton('btn-add-payment', false, i18n.view.pay);
        }
    })
}

function validatePaymentAmount(e, amount) {
    let val = e.value;
    if (parseInt(val) > parseInt(amount)) {
        $('#btn-add-payment').prop('disabled', true);
        $('#form-group-payment-amount').addClass('has-warning');
        $('#form-group-payment-amount .help-block').html(i18n.view.payment_amount_exceed);
    } else {
        $('#btn-add-payment').prop('disabled', false);
        $('#form-group-payment-amount').removeClass('has-warning');
        $('#form-group-payment-amount .help-block').html('');
    }

    val = val.replace(/\D+/, '');
    e.value = val;
}

function normalizeValue(e) {
    let val = e.value.toString().replace(',', '').replace(',', '').replace(',', '');
    e.value = val;
}

function changeToThousand(e) {
    let val = e.value;
    val = numberWithCommas(val);
    e.value = val;
}

function openProofofPayment(incomePaymentId) {
    $.ajax({
        type: 'POST',
        url: base_url + '/incomes/proof-of-payment',
        data: {
            income_payment_id: incomePaymentId
        },
        success: function(res) {
            $('#modalProofPayment').modal('show');
            $('#modalProofPayment .modal-body').html(res.view);
        },
        error: function(err) {
            showNotif(true, err);
        }
    })
}

window.getDetailUser = getDetailUser;
window.checkInvoiceNumber = checkInvoiceNumber;
window.addItem = addItem;
window.deleteRow = deleteRow;
window.updateTotal = updateTotal;
window.updateValue = updateValue;
window.saveItem = saveItem;
window.appendPaymentDetail = appendPaymentDetail;
window.pay = pay;
window.validatePaymentAmount = validatePaymentAmount;
window.normalizeValue = normalizeValue;
window.changeToThousand = changeToThousand;
window.openProofofPayment = openProofofPayment;