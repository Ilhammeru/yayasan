const { default: swal } = require("sweetalert");

function openModalWithValue(
    method,
    formId,
    modalId,
    modalLabel,
    textLabel,
    urlReq,
    urlRes = null
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
        },
        error: function(err) {
            setNotif(true, err);
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

function showNotif(isError, msg) {
    if (isError) {
        let message = msg.responseJSON ? msg.responseJSON.message : msg;
        iziToast.error({
            title: 'Error',
            message: message,
            position: 'topRight',
            timeout: 3000
        })
    } else {
        iziToast.success({
            title: 'Success',
            message: msg,
            position: 'topRight',
            timeout: 3000
        });
    }
}

function dtIntegration() {
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
};

function setDataTable(tableId, columns, route) {
    let dt = $('#' + tableId).DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        ajax: route,
        columns: columns,
        order: [[0, 'desc']]
    });
    return dt;
}

function deleteMaster(title, cancelText, confirmText, url, dt) {
    sweetAlert({
        text: title,
        icon: "warning",
        buttons: true,
        dangerMode: true,
        confirmButtonText: confirmText,
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                type: 'delete',
                url: url,
                success: function(res) {
                    showNotif(false, res.message);
                    dt.ajax.reload();
                },
                error: function(err) {
                    showNotif(true, err.responseJSON == undefined ? err.responseText : err.responseJSON);
                }
            })
        }
    });
}

window.openModalWithValue = openModalWithValue;
window.buildModalBodyGeneral = buildModalBodyGeneral;
window.showNotif = showNotif;
window.dtIntegration = dtIntegration;
window.setDataTable = setDataTable;
window.deleteMaster = deleteMaster;