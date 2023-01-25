const { default: Swal } = require("sweetalert2");

function createProposal(text) {
    openGlobalModal(
        base_url + '/proposals/create',
        null,
        'saveProposal()',
        false,
        text,
    );
}

function updateValue(e) {
    let val = e.value;
    val = numberWithCommas(val);
    $('#budget_total').val(val);
}

function saveProposal(id = null) {
    let checked = $('#status-proposal-form').prop('checked');
    if (checked) {
        Swal.fire({
            title: i18n.view.publish,
            icon: 'info',
            html:
              `<p>${i18n.view.confirm_publish}</p>`,
            showCloseButton: true,
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText:
              `${i18n.view.yes}`,
            cancelButtonText:
              `${i18n.view.close}`,
        }).then((result) => {
            if (result.isConfirmed) {
                submitProposal(id);
            }
        })
    } else {
        submitProposal(id);
    }
}

function submitProposal(edit = null) {
    let url = base_url + '/proposals';
    if (edit) {
        url = base_url + '/proposals/' + edit + '/update';
    }
    let form = $('#form-proposal');
    let data = new FormData($('#form-proposal')[0]);
    let ckEditor = CKEDITOR.instances.ckeditor_description_proposal.getData();
    data.append('message', ckEditor);
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        contentType: false,
        processData: false,
        beforeSend: function() {
            loadingPage(true, i18n.view.saving);
        },
        success: function(res) {
            console.log('res', res);
            loadingPage(false);
            showNotif(false, res.message);
            closeModal('global-modal');
            dt_proposal.ajax.reload();

        },
        error: function(err) {
            showNotif(true, err);
            loadingPage(false);
        }
    });
}

function updateForm(id, text) {
    openGlobalModal(
        base_url + '/proposals/' + id + '/edit',
        null,
        `saveProposal(${id})`,
        false,
        text,
    );
}

function detailProposal(id, text) {
    openGlobalModal(
        base_url + '/proposals/' + id,
        null,
        null,
        false,
        text,
    );
}

function approveProposal(proposalId) {
    Swal.fire({
        title: i18n.view.approve,
        icon: 'info',
        html:
          `<p>${i18n.view.confirm_approve}</p>`,
        showCloseButton: true,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText:
          `${i18n.view.yes}`,
        cancelButtonText:
          `${i18n.view.close}`,
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "GET",
                url: base_url + '/proposals/approve/' + proposalId,
                beforeSend: function() {
                    loadingPage(true, i18n.view.processing);
                },
                success: function(res) {
                    loadingPage(false);
                    showNotif(false, res.message);
                    dt_proposal.ajax.reload();
                },
                error: function(err) {
                    showNotif(true, err);
                }
            })
        }
    })
}

function fundingProposal(proposalId, total) {
    Swal.fire({
        title: i18n.view.cash_out,
        icon: 'info',
        html:
          `<p>${i18n.view.cash_out_confirm}</p>`,
        showCloseButton: true,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText:
          `${i18n.view.yes}`,
        cancelButtonText:
          `${i18n.view.close}`,
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-funding').reset();
            $('#modal-funding').modal('show');
            $('#modal-funding .btn-save').attr('onclick', `cashOut(${proposalId})`);
            $('.select2').select2();
            $('#amount-cash-out').val(numberWithCommas(total));
        }
    })
}

function updateValueAmount(e) {
    let val = e.value;
    val = numberWithCommas(val);
    $('#amount-cash-out').val(val);
}

function cashOut(proposalId) {
    let form = $('#form-funding');
    let data = form.serialize();
    $.ajax({
        type: 'POST',
        url: base_url + '/proposals/funding/' + proposalId,
        data: data,
        beforeSend: function() {
            loadingPage(true, i18n.view.processing);
        },
        success: function(res) {
            loadingPage(false);
            showNotif(false, res.message);
            $('#modal-funding').modal('hide');
            dt_proposal.ajax.reload();
        },
        error: function(err) {
            showNotif(true, err);
            loadingPage(false);
        }
    });
}

function publishProposal(proposalId) {
    Swal.fire({
        title: i18n.view.publish,
        icon: 'info',
        html:
          `<p>${i18n.view.confirm_publish}</p>`,
        showCloseButton: true,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText:
          `${i18n.view.yes}`,
        cancelButtonText:
          `${i18n.view.close}`,
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "GET",
                url: base_url + '/proposals/publish/' + proposalId,
                beforeSend: function() {
                    loadingPage(true, i18n.view.publising);
                },
                success: function(res) {
                    loadingPage(false);
                    showNotif(false, res.message);
                    dt_proposal.ajax.reload();
                },
                error: function(err) {
                    showNotif(true, err);
                    loadingPage(false);
                }
            })
        }
    })
}

window.createProposal = createProposal;
window.updateValue = updateValue;
window.saveProposal = saveProposal;
window.updateForm = updateForm;
window.submitProposal = submitProposal;
window.detailProposal = detailProposal;
window.approveProposal = approveProposal;
window.fundingProposal = fundingProposal;
window.cashOut = cashOut;
window.updateValueAmount = updateValueAmount;
window.publishProposal = publishProposal;