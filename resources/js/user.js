function createUser(text, type) {
    openModalWithValue(
        'GET',
        'form-user',
        'modalUser',
        'modalUserLabel',
        text,
        base_url + '/users/create/' + type
    );
}

function updateForm(id, text, type) {
    openModalWithValue(
        'GET',
        'form-user',
        'modalUser',
        'modalUserLabel',
        text,
        base_url + '/users/' + id + '/edit' + '/' + type
    );
}

function appendTableFilter(allText, userTypeText, userStatusText) {
    $('#table-users_filter').html(`
            <div class="row">
                <div class="col-md-4">
                    <div class="">
                        <input class="form-control" id="all-search" placeholder="${i18n.view.search_anything}" style="width: 100%;">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="">
                        <select class="form-control" data-placeholder="${i18n.view.search_user_type}" id="user-type-search"
                            style="width: 100%;">
                            <option></option>
                            <option value="all">${i18n.view.all}</option>
                            <option value="2">${i18n.view.goverment}</option>
                            <option value="1">${i18n.view.public}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="">
                        <select class="form-control" data-placeholder="${i18n.view.search_user_status}" id="user-status-search"
                            style="width: 100%;">
                            <option></option>
                            <option value="all">${i18n.view.all}</option>
                            <option value="active">${i18n.view.active}</option>
                            <option value="inactive">${i18n.view.inactive}</option>
                        </select>
                    </div>
                </div>
            </div>
        `);
}

function saveItem() {
    let form = $('#form-user');
    let data = new FormData($('#form-user')[0]);
    let method = form.attr('method');
    let url = form.attr('action');
    let status = 0;
    if ($('#status').prop('checked')) {
        status = 1;
    }
    data.append('status', status);

    $.ajax({
        type: method,
        url: url,
        data: data,
        contentType: false,
        processData: false,
        beforeSend: function() {
            disableButton('btn-save');
            disableButton('btn-cancel');
        },
        success: function(res) {
            disableButton('btn-save', false);
            disableButton('btn-cancel', false);
            showNotif(false, res.message);
            closeModal('modalUser');
            dt_user.ajax.reload();
        },
        error: function(err) {
            disableButton('btn-save', false);
            disableButton('btn-cancel', false);
            showNotif(true, err);
        }
    })
}

function getClasses(e) {
    let val = e.value;
    $.ajax({
        type: 'POST',
        url: base_url + '/get-class',
        data: {
            institution_id: val
        },
        beforeSend: function() {
            $('#institution_class_id').chosen('destroy');
            $('#institution_class_id').prop('disabled', true);
            $('#institution_class_id').html('');
            $('#institution_class_level_id').chosen('destroy');
            $('#institution_class_level_id').html('');
            $('#institution_class_level_id').prop('disabled', true);
        },
        success: function(res) {
            console.log('rssses', res);
            let data = res.data;
            let option = `<option></option>`;
            for (let a = 0; a < data.length; a++) {
                option += `<option value="${data[a].id}">${data[a].name}</option>`;
            }
            $('#institution_class_id').html(option);
            $('#institution_class_id').prop('disabled', false);
            $('#institution_class_id').chosen({
                width: '100%'
            });
        },
        error: function(err) {
            showNotif(true, err);
        }
    })
}

function getLevels(e) {
    let val = e.value;
    $.ajax({
        type: 'POST',
        url: base_url + '/get-level',
        data: {
            class_id: val
        },
        beforeSend: function() {
            $('#institution_class_level_id').chosen('destroy');
            $('#institution_class_level_id').html('');
            $('#institution_class_level_id').prop('disabled', true);
        },
        success: function(res) {
            let data = res.data;
            let option = `<option></option>`;
            for (let a = 0; a < data.length; a++) {
                option += `<option value="${data[a].id}">${data[a].name}</option>`;
            }
            $('#institution_class_level_id').prop('disabled', false);
            $('#institution_class_level_id').html(option);
            $('#institution_class_level_id').chosen({
                width: '100%'
            });
        },
        error: function(err) {
            setNotif(true, err.responseJSON);
        }
    })
}

function selectImage() {
    $('#user-image').click();
}

function showImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        $('#preview-image').css({
            'backgroundImage': `url(${reader.result})`
        });
    }
    reader.readAsDataURL(event.target.files[0]);
    $('#icon-action-image').removeClass("fa-camera");
    $('#icon-action-image').addClass("fa-times");
    $('#icon-action-image').css({
        'color': 'red'
    });
    $('#icon-action-image').attr('onclick', 'removePreviewImage()');
}

function removePreviewImage(edit = null) {
    $('#preview-image').css({
        'backgroundImage': 'url(../../assets/img/blank.png)'
    });
    $('#icon-action-image').addClass("fa-camera");
    $('#icon-action-image').removeClass("fa-times");
    $('#icon-action-image').attr('onclick', 'selectImage()');
    $('#icon-action-image').css({
        'color': 'black'
    });
    $('#user-image').val('');
    if (edit) {
        $('#is_delete_image').val(1);
    }
}

function showProfile(type, id, text) {
    let url = base_url + '/users/' + id + '/' + type + '/show';
    openModalWithValue(
        'GET',
        'form-user',
        'modalUser',
        'modalUserLabel',
        text,
        url,
        null,
        true
    );
}

function deleteItem(id, text, type) {
    let url = base_url + `/users/${id}/${type}`;
    deleteMaster(
        text,
        'Yes! Delete it',
        'Cancel',
        url,
        dt_user
    );
}

function searchUserType(type) {
    let elems = $('.user-type-option');
    for (let a = 0; a < elems.length; a++) {
        elems[a].classList.remove('active');
    }
    $(`#search-type-${type}`).addClass('active');

    dt_user = createDataTables(
        'table-users',
        columns,
        dt_route, {
            user_type: type,
            status: $('#search-user-status').val(),
            name: $('#search-all').val()
        }
    );
}

function searchStatus(e) {
    let val = e.value;
    let activeType = $('.user-type-option.active').data('type');
    dt_user = createDataTables(
        'table-users',
        columns,
        dt_route, {
            user_type: activeType,
            status: val,
            name: $('#search-all').val()
        }
    );
}

function searchAll(e) {
    let val = e.value;
    let activeType = $('.user-type-option.active').data('type');
    dt_user = createDataTables(
        'table-users',
        columns,
        dt_route, {
            user_type: activeType,
            status: $('#search-user-status').val(),
            name: val
        }
    );
}

window.createUser = createUser;
window.updateForm = updateForm;
window.saveItem = saveItem;
window.getClasses = getClasses;
window.getLevels = getLevels;
window.selectImage = selectImage;
window.showImage = showImage;
window.removePreviewImage = removePreviewImage;
window.showProfile = showProfile;
window.deleteItem = deleteItem;
window.appendTableFilter = appendTableFilter;
window.searchUserType = searchUserType;
window.searchStatus = searchStatus;
window.searchAll = searchAll;