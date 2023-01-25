const { default: Swal2 } = require("sweetalert2");
const { default: tippy } = require("tippy.js");

base_url = window.location.origin;

updateUserSaldo();

function openGlobalModal(
    urlToGenerateBody,
    payloadToGenerateBody = null,
    actionSave = null,
    fullscreen = false,
    modalTitle = 'Title',
    closeText = i18n.view.close,
    saveText = i18n.view.save,
) {
    $('#global-modal').modal('show');

    /**
     * Set size of modal
     */
    if (fullscreen) {
        $('#global-modal .modal-dialog').addClass('fullscreen');
    } else {
        $('#global-modal .modal-dialog').removeClass('fullscreen');
    }

    /**
     * Set header attributes
     */
    $('#global-modal .modal-title').text(modalTitle);

    /**
     * Set modal body
     * For the first time, insert loading animation while waiting response from backend
     */
    $('#global-modal .modal-body').html(`
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
        method = 'POST';
    }
    
    $.ajax({
        type: method,
        url: urlToGenerateBody,
        data: payloadToGenerateBody,
        beforeSend: function() {
            $('.select-chosen').select2('destroy');
            $('#global-modal .modal-footer').addClass('d-none');
        },
        success: function(res) {
            
            $('#global-modal .modal-body').html(res.view)
            .css({
                'display': 'block',
            });
            
            /**
             * Init chosen in select option
             */
            $('.select-chosen').select2();
            $('.select-date').daterangepicker({
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: true,
                timePickerIncrement: 5,
                locale: {
                    format: 'YYYY-MM-DD HH:mm',
                }
            })

            $('#global-modal .modal-footer').removeClass('d-none');
            
            $('#global-modal .btn-save').attr('onclick', `${actionSave}`)
                .addClass('d-none');
            if (actionSave) {
                $('#global-modal .btn-save').attr('onclick', `${actionSave}`)
                    .removeClass('d-none');
            }

        }
    });

    /**
     * Set footer attributes
     */
    $('#global-modal .btn-close').text(closeText);
    $('#global-modal .btn-save').text(saveText);
}

function createDataTables(tableId, columns, route, param = null) {
    if (param) {
        $('#' + tableId).DataTable().destroy();
    }
    let dt = $('#' + tableId).DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        ajax: {
            url: route,
            data: param
        },
        columns: columns,
        drawCallback: function(settings, json) {
            tippy('[data-tippy-content]');
        },
        order: [
            [0, 'desc']
        ],
    });
    return dt;
}

function DataTableIntegration() {
    $.extend(true, $.fn.dataTable.defaults, {
        "sDom": "<'row'<'col-sm-6 col-xs-5'l><'col-sm-6 col-xs-7'f>r>t<'row'<'col-sm-5 hidden-xs'i><'col-sm-7 col-xs-12 clearfix'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_",
            "sSearch": "<div class=\"input-group\">_INPUT_<span class=\"input-group-addon\"><i class=\"fa fa-search\"></i></span></div>",
            "sInfo": "<strong>_START_</strong>-<strong>_END_</strong> of <strong>_TOTAL_</strong>",
            "oPaginate": {
                "sPrevious": "",
                "sNext": ""
            }
        }
    });
    $.extend($.fn.dataTableExt.oStdClasses, {
        "sWrapper": "dataTables_wrapper form-inline",
        "sFilterInput": "form-control",
        "sLengthSelect": "form-control"
    });
}

function openModalWithValue(
    method,
    formId,
    modalId,
    modalLabel,
    textLabel,
    urlReq,
    urlRes = null,
    needToOpenTab = false
) {
    $.ajax({
        type: method,
        url: urlReq,
        beforeSend: function() {

        },
        success: function(res) {
            buildModalBodyGeneral(
                textLabel,
                res.url,
                res.view,
                res.method,
                modalLabel,
                formId,
                modalId
            );

            if (needToOpenTab) {
                openTab('general');
            }
        },
        error: function(err) {
            showNotif(true, err);
        }
    })
}

function buildModalBodyGeneral(
    text, url, view, method,
    modalLabel, formId, modalId
) {
    $(`#${modalLabel}`).text(text);
    $(`#${formId}`).attr('action', base_url + url);
    $(`#${formId}`).attr('method', method);
    $(`#${modalId} .modal-body`).html(view);
    $(`#${modalId}`).modal('show');
}

function regexNumber(e) {
    let val = e.value;
    val = val.replace(/\D+/, '');
    e.value = val;
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
            $('#district_id').chosen({
                width: "100%"
            });
        },
        error: function(err) {
            showNotif(true, err);
        }
    })
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
            $('#city_id').chosen({
                width: "100%"
            });
        },
        error: function(err) {
            showNotif(true, err);
        }
    })
}

function delay(callback, ms) {
    var timer = 0;
    return function() {
        var context = this,
            args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function() {
            callback.apply(context, args);
        }, ms || 0);
    };
}

function disableButton(id, isDisable = true, text = i18n.view.save_changes) {
    let loading = `<i class="fa fa-spinner fa fa-spin"></i>`;
    $(`#${id}`).prop('disabled', isDisable);
    if (id == 'btn-save' || id == 'btn-add-payment') {
        if (isDisable) {
            $('#' + id).html(loading);
        } else {
            $('#' + id).html(text);
        }
    }
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function loadingPage(show = true, text = 'Checking data') {
    let loading = Swal2.mixin({
        html: '<i class="fa fa-spinner fa-2x fa-spin"></i> <br> ' + text,
        showConfirmButton: false,
        showCancelButton: false,
        showDenyButton: false,
        allowOutsideClick: false,
    });

    if (show) {
        loading.fire();
    } else {
        loading.close();
    }
}

function updateUserSaldo() {
    $.ajax({
        type: 'GET',
        url: base_url + '/user/update-saldo',
        success: function(res) {
            console.log('res',res);
            $('#user-saldo').html(res.view);
        }
    })
}

function reloadWalletDetail(incomeCategoryId) {
    // let columns, dt_route, dt_param;
    dt_route = base_url + '/users/wallet/ajax';
    param = {
        income_category_id: incomeCategoryId,
    };
    if (incomeCategoryId != 0) {
        columns = [
            {data: 'checkbox', name: 'checkbox', className: 'text-center', sortable: false,},
            {data: 'invoice_number', name: 'invoice_number'},
            {data: 'user', name: 'user'},
            {data: 'amount', name: 'amount'},
            // {data: 'action', name: 'action', className: 'text-center', orderable: false},
        ];
    } else {
        columns = [
            {data: 'id',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                width: '5%',
                className: 'text-center'
            },
            {data: 'proposal', name: 'proposal'},
            {data: 'budget', name: 'budget'},
            {data: 'approved_at', name: 'approved_at'},
            {data: 'approved_budget', name: 'approved_budget'},
        ];
    }
    createDataTables(
        'table-wallet',
        columns,
        dt_route,
        param
    );

    /**
     * Manipulate buttons
     */
    let elems = $('.widget-wallet');
    for (let a = 0; a < elems.length; a++) {
        let id = elems[a].id;
        $('#' + id).removeClass('themed-background-flatie themed-color-white');
    }
    $('#widget-wallet-' + incomeCategoryId).addClass('themed-background-flatie themed-color-white');

    /**
     * Manipulate 'data' attribute in wallet tab
     */
    $('#wallet-tab-active').attr('data-active', incomeCategoryId);
}

function chooseAllWallet(e) {
    let id = e.id;
    var checkedStatus   = $('#' + id).prop('checked');
    var table           = $('#' + id).closest('table');

    $('.check-wallet-item').each(function(ee) {
        $(this).prop('checked', checkedStatus);
    });
}

function openSendingWalletForm() {
    let maps = [];
    let val = [];
    $('.check-wallet-item').each(function(ee) {
        let prop = $(this).prop('checked');
        maps.push(prop);
        if (prop) {
            val.push($(this).val());
        }
    });

    let filter = maps.filter(Boolean);
    if (filter.length == 0) {
        return showNotif(true, i18n.view.choose_wallet_item);
    }

    val = JSON.stringify(val);
    let incomeCategoryId = $('#wallet-tab-active').data('active');
    $.ajax({
        type: 'POST',
        url: base_url + '/users/wallet/form/send',
        data: {
            ids: val,
            income_category_id: incomeCategoryId,
        },
        beforeSend: function() {
            loadingPage(true, i18n.view.generate_data);
            $('.select-chosen').select2('destroy');
        },
        success: function(res) {
            loadingPage(false);
            $('#modal-send-wallet').modal('show');
            $('#modal-send-wallet .modal-body').html(res.view);
            $('.select-chosen').select2();
        },
        error: function(err) {
            loadingPage(false);
            showNotif(true, err);
        }
    })
}

function openTransferFoundForm() {
    let maps = [];
    let val = [];
    $('.check-wallet-item').each(function(ee) {
        let prop = $(this).prop('checked');
        maps.push(prop);
        if (prop) {
            val.push($(this).val());
        }
    });

    let filter = maps.filter(Boolean);
    if (filter.length == 0) {
        return showNotif(true, i18n.view.choose_wallet_item);
    }

    val = JSON.stringify(val);
    let incomeCategoryId = $('#wallet-tab-active').data('active');
    $.ajax({
        type: 'POST',
        url: base_url + '/users/wallet/form-transfer-fund/send',
        data: {
            ids: val,
            income_category_id: incomeCategoryId,
        },
        beforeSend: function() {
            loadingPage(true, i18n.view.generate_data);
            $('.select-chosen').select2('destroy');
        },
        success: function(res) {
            loadingPage(false);
            $('#modal-send-wallet').modal('show');
            $('#modal-send-wallet .modal-body').html(res.view);
            $('.select-chosen').select2();
        },
        error: function(err) {
            loadingPage(false);
            showNotif(true, err);
        }
    });
}

function sendWallet() {
    let incomeCategoryId = $('#wallet-tab-active').data('active');
    let form = $('#form-send-wallet');
    let data = new FormData($('#form-send-wallet')[0]);
    let ckEditor = CKEDITOR.instances.ckeditor_message_send_wallet.getData();
    data.append('message', ckEditor);
    $.ajax({
        type: "POST",
        url: base_url + '/users/wallet/send/global',
        data: data,
        contentType: false,
        processData: false,
        beforeSend() {
            loadingPage(true, i18n.view.saving);
        },
        success: function(res) {
            loadingPage(false);
            updateUserSaldo();
            $('#modal-send-wallet').modal('hide');
            document.getElementById('form-send-wallet').reset();
            reloadWalletDetail(incomeCategoryId);
            showNotif(false, res.message);

            /**
             * Manipulate wallet tab value
             */
            $('#amount_wallet_tab_' + incomeCategoryId).text(res.data.wallet_category_amount);
        },
        error: function(err) {
            loadingPage(false);
            showNotif(true, err);
        }
    })
}

window.createDataTables = createDataTables;
window.DataTableIntegration = DataTableIntegration;
window.openModalWithValue = openModalWithValue;
window.buildModalBodyGeneral = buildModalBodyGeneral;
window.regexNumber = regexNumber;
window.getDistrict = getDistrict;
window.getCity = getCity;
window.delay = delay;
window.disableButton = disableButton;
window.numberWithCommas = numberWithCommas;
window.loadingPage = loadingPage;
window.openGlobalModal = openGlobalModal;
window.updateUserSaldo = updateUserSaldo;
window.reloadWalletDetail = reloadWalletDetail;
window.chooseAllWallet = chooseAllWallet;
window.openSendingWalletForm = openSendingWalletForm;
window.openTransferFoundForm = openTransferFoundForm;
window.sendWallet = sendWallet;