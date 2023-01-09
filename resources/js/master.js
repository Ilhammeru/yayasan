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