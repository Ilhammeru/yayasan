const { default: tippy } = require("tippy.js");

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

$('#transaction_start_date').daterangepicker({
    singleDatePicker: true,
    autoUpdateInput: false,
    locale: {
        cancelLabel: 'Clear'
    }
});

$('#transaction_start_date').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD'));
});

$('#transaction_start_date').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
});

$('#transaction_end_date').daterangepicker({
    singleDatePicker: true,
    autoUpdateInput: false,
    locale: {
        cancelLabel: 'Clear'
    }
});

$('#transaction_end_date').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD'));
});

$('#transaction_end_date').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
});

let instanceFilter;

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

function addItem(incomeCategoryId, isEnable) {
    let table = $('.table-item');

    // append new row
    let tr = $('.tr-item');
    let len = tr.length;
    $.ajax({
        type: "POST",
        url: base_url + '/incomes/build-item-row',
        data: {
            len: len,
            income_category_id: incomeCategoryId,
            is_enable: isEnable,
        },
        success: function(res) {
            $('.table-item tbody').append(res.view);
            let deleteElem = $('.td-delete');
            for (let a = 0; a < deleteElem.length; a++) {
                deleteElem[a].classList.remove('d-none');
            }
            $('.th-additional').removeClass('d-none');

            // init select2
            let select2add = $('.select-chosen').select2();
            select2add.data('select2').$selection.css('height', '54px');

            let d = new Date();
            let year = d.getFullYear();
            // init month picker
            $('.select-month').MonthPicker({
                StartYear: year,
                MonthFormat: 'mm-yy',
                Button: function(options) {
                    // this refers to the associated input field.
                    return $(`<span class="input-group-addon" style="display: table-cell !important;"><i class="fa fa-calendar"></i></span>`)
                        .button({
                            text: true,
                            icons: 'i-icon-calculator'
                        });
                }
            });
            $('.select-date').daterangepicker({
                singleDatePicker: true,
                locale: {
                    format: 'YYYY-MM-DD',
                }
            })
        }
    })
}

function deleteRow(id) {
    let tr = $('.tr-item');
    $('#tr-item-' + id).remove();
    if (tr.length == 2) {
        // get latest row attributes
        let lastElem = $('.tr-item');
        let lastId = lastElem[0].id;
        let lastKey = $('#' + lastId).data('key');
        $('.td-delete-' + lastKey).addClass('d-none');
        $('.th-additional').addClass('d-none');
    }
}

function updateTotalBackup(e) {
    let currentVal = e.value;
    let currentId = e.id;
    let inputs = $('.price_item_shadow');
    let val = [];
    for (let a = 0; a < inputs.length; a++) {
        let id = inputs[a].id;
        val.push(currentVal);
    }
    // const sum = val.reduce((partialSum, a) => parseInt(partialSum) + parseInt(a), 0);
    // res = numberWithCommas(sum);

    // $('#amount_total').val(sum);
    // $('#amount_total_shadow').val(res);
    // $('#remaining_bill').val(res);
}

function updateTotal(e) {
    let selfValue = e.value;
    selfValue = selfValue.replaceAll(',','');
    if (selfValue == '') {
        selfValue = 0;
    }
    let selfId = e.id;
    // set shadow value
    $('#' + selfId + '_shadow').val(selfValue);
    
    let val = [];
    let inputs = $('.price_item_shadow');
    for (let a = 0; a < inputs.length; a++) {
        let arrayVal = inputs[a].value;
        val.push(arrayVal);
    }
    // sum all value
    let sum = val.reduce((partialSum, a) => parseInt(partialSum) + parseInt(a), 0);
    
    // manipulate views
    let res = numberWithCommas(sum);
    $('#amount_total').val(sum);
    $('#amount_total_shadow').val(res);
    $('#remaining_bill').val(res);
}

function updateValue(e) {
    let val = e.value;
    val = val.replaceAll(',','');
    let id = e.id;
    if (val == '') {
        val = 0;
    }
    e.value = numberWithCommas(val);
    $('#' + id + '_shadow').val(val);
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

function detailInvoiceMonthly(
    institutionId,
    classId,
    levelId,
    userId,
    month,
    incomeCategoryId,
    incomeTypeId,
    incomeCategoryName,
) {
    let payload = {
        institution_id: institutionId,
        class_id: classId,
        level_id: levelId,
        user_id: userId,
        month: month,
        income_category_id: incomeCategoryId,
        income_type_id: incomeTypeId,
        user_type: 1,
    };

    openModalInvoice(
        base_url + '/incomes/invoice/monthly/form',
        payload,
        'payInvoiceMonthly()',
        true,
        i18n.view.invoice + ' ' + incomeCategoryName,
    );
}

function detailPaidInvoice(paymentId) {
    openModalInvoice(
        base_url + `/incomes/${paymentId}`,
        null,
        `printPaidInvoice(${paymentId})`,
        true,
        i18n.view.detail_invoice,
        i18n.view.close,
        i18n.view.print,
    );
}

function printPaidInvoice(paymentId) {
    window.open(
        base_url + '/incomes/' + paymentId + '?print=true',
        '_blank'
    );
}

function createInvoiceNonPeriod(
    institutionId,
    classId,
    levelId,
    incomeCategoryId,
    incomeTypeId,
    incomeCategoryName,
) {
    let payload = {
        institution_id: institutionId,
        class_id: classId,
        level_id: levelId,
        income_category_id: incomeCategoryId,
        income_type_id: incomeTypeId,
    };
    openModalInvoice(
        base_url + '/incomes/invoice-non-period',
        payload,
        'saveInvoiceNonPeriod()',
        i18n.view.create_invoice,
    );
}

function openModalInvoice(
    urlToGenerateBody,
    payloadToGenerateBody = null,
    actionSave = null,
    fullscreen = false,
    modalTitle = 'Title',
    closeText = i18n.view.close,
    saveText = i18n.view.save,
) {
    $('#invoice-modal').modal('show');

    if (!closeText) {
        closeText = i18n.view.close;
    }

    /**
     * Set size of modal
     */
    if (fullscreen) {
        $('#invoice-modal .modal-dialog').addClass('fullscreen');
    } else {
        $('#invoice-modal .modal-dialog').removeClass('fullscreen');
    }

    /**
     * Set header attributes
     */
    $('#invoice-modal .modal-title').text(modalTitle);

    /**
     * Set modal body
     * For the first time, insert loading animation while waiting response from backend
     */
    $('#invoice-modal .modal-body').html(`
        <div class="text-center">
            <i class="fa fa-spinner fa-3x fa-spin"></i>
        </div>
    `).css({
        'display': 'flex',
        'alignItems': 'center',
        'justifyContent': 'center',
    });

    let method = 'GET';
    if (payloadToGenerateBody) {
        method = "POST";
    }
    
    $.ajax({
        type: method,
        url: urlToGenerateBody,
        data: payloadToGenerateBody,
        success: function(res) {
            $('#invoice-modal .modal-body').html(res.view)
                .css({
                    'display': 'block',
                });
            
            /**
             * Manipulate modal footer
             */
            $('#invoice-modal .modal-footer').removeClass('d-none');
            
            if (actionSave) {
                $('#invoice-modal .btn-save').attr('onclick', `${actionSave}`);
            }

            let d = new Date();
            let year = d.getFullYear();
            /**
             * Init select2 in select option
             */
            $('.select-chosen').select2({
                disabled: false,
                dropdownParent: '#invoice-modal'
            });
            $('.select-month').MonthPicker({
                StartYear: year,
                MonthFormat: 'mm-yy',
                Button: function(options) {
                    // this refers to the associated input field.
                    return $(`<span class="input-group-addon" style="display: table-cell !important;"><i class="fa fa-calendar"></i></span>`)
                        .button({
                            text: true,
                            icons: 'i-icon-calculator'
                        });
                }
            });
            $('.select-date').daterangepicker({
                singleDatePicker: true,
                locale: {
                    format: 'YYYY-MM-DD',
                }
            })
        }
    });

    /**
     * Set footer attributes
     */
    $('#invoice-modal .btn-close').text(closeText);
    $('#invoice-modal .btn-save').text(saveText);
}

function payInvoiceMonthly() {
    let form = $('#form-payment');
    let data = new FormData($('#form-payment')[0]);
    // let ckEditor = CKEDITOR.instances.ckeditor_message.getData();
    // data.append('message', ckEditor);
    $.ajax({
        type: "POST",
        url: base_url + '/incomes/pay',
        data: data,
        contentType: false,
        processData: false,
        beforeSend: function() {
            loadingPage(true, i18n.view.saving);
        },
        success: function(res) {
            loadingPage(false);
            $('.main-content-incomes').html(res.data.view);
            closeModal('invoice-modal');
            showNotif(false, res.message);
            updateUserSaldo();
        },
        error: function(err) {
            showNotif(true, err);
            loadingPage(false);
        }
    });
}

function saveInvoiceNonPeriod() {
    let form = $('#form-payment');
    let data = new FormData($('#form-payment')[0]);
    // let ckEditor = CKEDITOR.instances.ckeditor_message.getData();
    // data.append('message', ckEditor);
    $.ajax({
        type: "POST",
        url: base_url + '/incomes/pay-non-period',
        data: data,
        contentType: false,
        processData: false,
        beforeSend: function() {
            loadingPage(true, i18n.view.saving);
        },
        success: function(res) {
            loadingPage(false);
            $('.main-content-incomes').html(res.data.view);
            closeModal('invoice-modal');
            showNotif(false, res.message);
            updateUserSaldo();
        },
        error: function(err) {
            showNotif(true, err);
            loadingPage(false);
        }
    })
}

function changeIncomeByLevel(
    levelId,
    classId,
    institutionId,
    incomeCategoryId,
    incomeTypeId,
    incomeTypePeriod,
    incomeCategoryName
) {
    let payload = {
        level_id: levelId,
        class_id: classId,
        institution_id : institutionId,
        income_category_id: incomeCategoryId,
        income_type_id: incomeTypeId,
        income_category_name: incomeCategoryName,
        income_type_period: incomeTypePeriod,
    };
    $.ajax({
        type: "POST",
        url: base_url + '/incomes/change/monthly-income/by-level',
        data: payload,
        beforeSend: function() {
            loadingPage(true, i18n.view.generate_data);
        },
        success: function(res) {
            loadingPage(false);
            $('.main-content-incomes').html(res.view);
        },
        error: function(err) {
            showNotif(true, err);
            loadingPage(false);
        }
    })
}

function previewImage(path) {
    $('#preview-modal-image').modal('show');
    $('#preview-modal-image .modal-body').html(`
        <img src="${path}" style="width: 100%; height: auto;" />
    `).css({
        'display': 'flex',
        'alignItems': 'center',
        'justifyContent': 'center',
    });
}

function filterIncome() {
    let form = $('#form-filter-income');
    let data = form.serialize();
    $.ajax({
        type: "POST",
        url: base_url + '/incomes/filter',
        data: data,
        beforeSend: function() {
            loadingPage(true, i18n.view.generate_data);
        },
        success: function(res) {
            loadingPage(false);
            $('.main-content-incomes').html(res.view);
        },
        error: function(err) {
            loadingPage(false);
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
window.detailInvoiceMonthly = detailInvoiceMonthly;
window.openModalInvoice = openModalInvoice;
window.payInvoiceMonthly = payInvoiceMonthly;
window.saveInvoiceNonPeriod = saveInvoiceNonPeriod;
window.changeIncomeByLevel = changeIncomeByLevel;
window.detailPaidInvoice = detailPaidInvoice;
window.printPaidInvoice = printPaidInvoice;
window.previewImage = previewImage;
window.filterIncome = filterIncome;
window.createInvoiceNonPeriod = createInvoiceNonPeriod;