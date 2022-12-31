var baseUrl = window.location.origin;

function saveItem() {
    let form = $('#form-intitution');
    let data = form.serialize();
    let url = form.attr('action');
    let method = form.attr('method');
    let classWrapper = $('.class-wrapper');
    for (let a = 0; a < classWrapper.length; a++) {
        let classInput = $(`.class-input-${a}`).val();
        let levelInput = $(`.level-input-${a}`).val();
        if (levelInput != '' && classInput == '') {
            return App.setNotif(true, 'Class cannot be empty if level of class is declare');
        }
    }

    $.ajax({
        type: method,
        url: url,
        data: data,
        beforeSend: function() {
            $('#btn-save').prop('disabled', true);
            $('#btn-cancel').prop('disabled', true);
        },
        success: function(res) {
            console.log(res);
            $('#btn-save').prop('disabled', false);
            $('#btn-cancel').prop('disabled', false);
            dttable.ajax.reload();
            App.setNotif(false, res.message);
            closeModal('modalIntitution');
        },
        error: function(err) {
            console.log('err',err);
            $('#btn-save').prop('disabled', false);
            $('#btn-cancel').prop('disabled', false);
            App.setNotif(true, err);
        }
    })
}

function hasClass() {
    let elem = $('input[name="has_class"]')[0].checked;
    if (elem) {
        $('.class-container').removeClass('d-none');
        let all = $('.class-wrapper');
        for (let a = 0; a < all.length; a++) {
            if (a != 0) {
                let id = all[a].id;
                $('#' + id).remove();
            }
        }
        $('input[name="ins[0][class_name]"]').val('');
        $('input[name="ins[0][class][0][level]"]').val('');
        $('#target-class-level-0').html('');
    } else {
        $('.class-container').addClass('d-none');
    }
}

function createForm(createText) {
    let url = baseUrl + '/intitutions/create';
    let storeUrl = baseUrl + '/intitutions';
    $.ajax({
        type: "GET",
        url: url,
        dataType: 'json',
        success: function(res) {
            $('#modalIntitutionLabel').text(createText);
            $('#modalIntitution .modal-body').html(res.view);
            $('#modalIntitution').modal('show');
            $('#form-intitution').attr('action', storeUrl);
            $('#form-intitution').attr('method', "POST");
        },
        error: function(err) {
            App.setNotif(true, err);
        }
    })
}

function appendLevel(classId) {
    let current = $('.level-helper-s');
    let classWrapper = $('.class-wrapper');
    let levelWrapper = $('.level-wrapper-' + classId);
    let classLen = classWrapper.length;
    let len = levelWrapper.length;
    let elem = `
        <div class="col-md-6 level-wrapper-${classId}" id="level-helper-f-${len}-${classLen}"></div>
        <div class="col-md-6" id="level-helper-s-${len}-${classLen}">
            <div class="input-group">
                <input type="text" id="class_level-${len}-${classId}" name="ins[${classId}][class][${len}][level]" class="form-control form-control-sm level-input-${classId}" placeholder="A / B / C / etc" required>
                <span class="input-group-addon"><i class="gi gi-remove_2" onclick="deleteLevel(${len}, ${classLen})" style="color: red; cursor: pointer;"></i></span>
            </div>
        </div>
    `;
    $(`#target-class-level-${classId}`).append(elem);
    $(`#class_level-${len}-${classId}`).focus();
}

function appendClass(labelName, labelLevel) {
    let elems = $('.class-wrapper');
    let len = elems.length;
    let form = `
        <div class="border p-3 mb-3 class-wrapper" id="class-wrapper-${len}" style="position: relative; width: 100%;">
            <span class="gi gi-remove text-danger" onclick="deleteClassRow(${len})" style="position: absolute; top: -4px; right: -2px; font-size: 18px; cursor: pointer;"></span>
            <div class="row">
                <div class="col-md-6 col-sm-12 level-wrapper-${len}">
                    <div class="form-group mb-3">
                        <label for="class_name" class="control-label">${labelName}</label>
                        <input type="text" name="ins[${len}][class_name]" placeholder="${labelName}" class="form-control form-control-sm class-input-${len}" id="class_name-${len}">
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="class_level" class="control-label">${labelLevel}</label>
                        <div class="input-group">
                            <input type="text" id="class_level" name="ins[${len}][class][0][level]" class="form-control form-control-sm level-input-${len}" placeholder="A / B / C / etc" required>
                            <span class="input-group-addon"><i class="gi gi-plus" onclick="appendLevel(${len})" style="cursor: pointer;"></i></span>
                        </div>
                    </div>
                </div>
                <div id="target-class-level-${len}"></div>
            </div>
        </div>
    `;
    $('#target-class').append(form);
    $(`#class_name-${len}`).focus();
}

function deleteClassRow(id) {
    $(`#class-wrapper-${id}`).remove();
}

function deleteLevel(id, classId) {
    $(`#level-helper-f-${id}-${classId}`).remove();
    $(`#level-helper-s-${id}-${classId}`).remove();
}

function updateForm(id, createText = 'Update institution') {
    let url = baseUrl + `/intitutions/${id}/edit`;
    let storeUrl = baseUrl + `/intitutions/${id}`;
    $.ajax({
        type: "GET",
        url: url,
        dataType: 'json',
        success: function(res) {
            $('#modalIntitutionLabel').text(createText);
            $('#modalIntitution .modal-body').html(res.view);
            $('#modalIntitution').modal('show');
            $('#form-intitution').attr('action', storeUrl);
            $('#form-intitution').attr('method', "PUT");
        },
        error: function(err) {
            App.setNotif(true, err);
        }
    })
}

window.createForm = createForm;
window.saveItem = saveItem;
window.appendLevel = appendLevel;
window.deleteLevel = deleteLevel;
window.appendClass = appendClass;
window.updateForm = updateForm;
window.deleteClassRow = deleteClassRow;
window.hasClass = hasClass;