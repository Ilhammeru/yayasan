const { CountUp } = require("countup.js");

initDetailData();

function playCountUp(number, target, duration = 1) {
    let countUpOption = {
        startVal: 0,
        duration: duration,
        separator: '.',
    };
    let countUp = new CountUp(target, number, countUpOption);
    countUp.start();
}

function updateForm(id, createText = 'Update institution') {
    let url = base_url + `/intitutions/${id}/edit`;
    let storeUrl = base_url + `/intitutions/${id}`;
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
            showNotif(true, err);
        }
    })
}

function deleteLevelB(id, classId) {
    let className = $(`input[name="ins[${classId}][class_name]"]`).val();
    let levelName = $(`input[name="ins[${classId}][class][${id}][level]"]`).val();
    let format = {
        class_name: className,
        level: [levelName]
    };
    $(`#level-helper-f-${id}-${classId}`).remove();
    $(`#level-helper-s-${id}-${classId}`).remove();
}

function deleteClassRow(id, institutionId = 0) {
    if (institutionId != 0) {
        sweetAlert({
            text: i18n.view.delete_text,
            icon: "warning",
            buttons: true,
            dangerMode: true,
            confirmButtonText: i18n.view.confirm_delete,
        }).then((willDelete) => {
            if (willDelete) {
                let levelWrapper = $('.level-wrapper-' + id);
                let className = $(`input[name="ins[${id}][class_name]"]`).val();
                let format = {
                    class_name: className,
                    level: []
                };
                if (className) {
                    for (let a = 0; a < levelWrapper.length; a++) {
                        let ids = levelWrapper[a].id;
                        let levelId = $('#' + ids).data('key');
                        let levelName = $(`input[name="ins[${id}][class][${levelId}][level]"]`).val();
                        format.level.push(levelName);
                    }
                }
                let ins_id = $('#current_id').val();
                format.id = ins_id;
            
                $.ajax({
                    type: 'POST',
                    url: base_url + '/intitutions/delete-class',
                    data: format,
                    beforeSend: function() {
                        loadingPage();
                    },
                    success: function(res) {
                        loadingPage(false);
                        showNotif(false, res.message);
                        $('#class-wrapper-' + id).remove();
                    },
                    error: function(err) {
                        loadingPage(false);
                        showNotif(true, err);
                    }
                })
            }
        });
    } else {
        $('#class-wrapper-' + id).remove();
    }

}

function deleteLevel(id, classId, institutionId = 0) {
    if (institutionId != 0) {
        sweetAlert({
            text: i18n.view.delete_text,
            icon: "warning",
            buttons: true,
            dangerMode: true,
            confirmButtonText: i18n.view.confirm_delete,
        }).then((willDelete) => {
            if (willDelete) {
                let levelWrapper = $('.level-wrapper-' + id);
                let className = $(`input[name="ins[${classId}][class_name]"]`).val();
                let format = {
                    class_name: className,
                    level: []
                };
                if (className) {
                    let levelName = $(`input[name="ins[${classId}][class][${id}][level]"]`).val();
                    format.level.push(levelName);
                }
                let ins_id = $('#current_id').val();
                format.id = ins_id;
            
                $.ajax({
                    type: 'POST',
                    url: base_url + '/intitutions/delete-level',
                    data: format,
                    beforeSend: function() {
                        loadingPage();
                    },
                    success: function(res) {
                        loadingPage(false);
                        showNotif(false, res.message);
                        $('#level-helper-s-' + id + '-' + classId).remove();
                    },
                    error: function(err) {
                        loadingPage(false);
                        showNotif(true, err);
                    }
                })
            }
        });
    } else {
        $('#level-helper-s-' + id + '-' + classId).remove();
    }
}

function showDetail(param) {
    let targetLoad = 'target-detail-data';
    if (param.update) {
        targetLoad = 'target-detail-table-data';
    }

    $.ajax({
        type: 'POST',
        url: base_url + '/intitutions/detail-data/intitutions',
        data: param,
        beforeSend: function() {
            $('#' + targetLoad).html(`
                <i class="fa fa-spinner fa-2x fa-spin"></i> <br>
                ${i18n.view.generate_data}
            `).css({
                'textAlign': 'center'
            });
        },
        success: function(res) {
            console.log(res);
            $('#' + targetLoad)
                .html(res.view)
                .css({
                    'textAlign': 'unset',
                });

            if (param.update == undefined || !param.update) {
                // set filter class active
                $('.filter-class').removeClass('themed-border-default')
                    .removeClass('themed-background-default')
                    .removeClass('themed-color-white')
                    .removeClass('btn-default')
                    .addClass('btn-default');
    
                $('#filter-class-' + res.data.data.classes[0].id).removeClass('btn-default')
                    .addClass('themed-border-default')
                    .addClass('themed-background-default')
                    .addClass('themed-color-white');
            }
            if (param.update) {
                $('.filter-level').removeClass('themed-border-default')
                    .removeClass('themed-background-default')
                    .removeClass('themed-color-white')
                    .removeClass('btn-default')
                    .addClass('btn-default');
    
                $('#filter-level-' + res.data.level_id).removeClass('btn-default')
                    .addClass('themed-border-default')
                    .addClass('themed-background-default')
                    .addClass('themed-color-white');
            }
        },
        error: function(err) {
        }
    })
}

function chooseHomeroomTeacher(classId, levelId, institutionId) {
    $.ajax({
        type: 'GET',
        url: base_url + '/intitutions/show-homeroom-teacher?class_id=' + classId + '&level_id=' + levelId + '&institution_id=' + institutionId,
        success: function(res) {
            $('#modalChooseHomeroom .modal-body #target-form').html(res.view);
            $('#modalChooseHomeroom').modal('show');
            $('#homeroom').chosen({width: '100%'});
        },
        error: function(err) {
            showNotif(true, err);
        }
    })
}

function saveHomeroom() {
    let form = $('#form-select-homeroom');
    let data = form.serialize();
    $.ajax({
        type: 'POST',
        url: base_url + '/intitutions/store-homeroom',
        data: data,
        beforeSend: function() {
            loadingPage(true, i18n.view.saving);
        },
        success: function(res) {
            console.log('res',res);
            loadingPage(false);
            showNotif(false, res.message);
            $('#modalChooseHomeroom').modal('hide');
            $('#target-homeroom-teacher').html(`
                <b>${res.data.homeroom}</b>
                <a style="cursor: pointer;" onclick="chooseHomeroomTeacher(${res.data.class_id}, ${res.data.level_id}, ${res.data.institution_id})">${i18n.view.change_homeroom} <i class="fa fa-share"></i></a>
            `);
        },
        error: function(err) {
            showNotif(true, err);
        }
    });
}

function initDetailData() {
    let institutionId = $('#df_institution_id').val();
    let classId = $('#df_class_id').val();
    let levelId = $('#df_level_id').val();
    let param = {
        institution_id: institutionId,
        class_id: classId,
        level_id: levelId,
    }
    showDetail(param);
}

function changeLevel(institutionId, levelId, classId) {
    // target-detail-table-data
    let param = {
        institution_id: institutionId,
        class_id: classId,
        level_id: levelId,
        update: true,
    };
    showDetail(param);
}

function changeClass(classId, institutionId) {
    let param = {
        institution_id: institutionId,
        class_id: classId,
        level_id: 0,
    };
    showDetail(param);
}

function appendLevel(classId) {
    let current = $('.level-helper-s');
    let classWrapper = $('.class-wrapper');
    let levelWrapper = $('.level-wrapper-' + classId);
    let classLen = classWrapper.length;
    let len = levelWrapper.length;
    let elem = `
        <div class="col-md-6 level-wrapper-${classId}" data-key="${len}" id="level-helper-f-${len}-${classLen}"></div>
        <div class="col-md-6" id="level-helper-s-${len}-${classLen}">
            <div class="input-group">
                <input type="text" id="class_level-${len}-${classId}" data-key="${len}" name="ins[${classId}][class][${len}][level]" class="form-control form-control-sm level-input-${classId}" placeholder="A / B / C / etc" required>
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
                <div class="col-md-6 col-sm-12 level-wrapper-${len}" data-key="0" id="level-helper-f-0-${len}">
                    <div class="form-group mb-3">
                        <label for="class_name" class="control-label">${labelName}</label>
                        <input type="text" name="ins[${len}][class_name]" data-key="${len}" placeholder="${labelName}" class="form-control form-control-sm class-input-${len}" id="class_name-${len}">
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="class_level" class="control-label">${labelLevel}</label>
                        <div class="input-group">
                            <input type="text" id="class_level" data-key="0" name="ins[${len}][class][0][level]" class="form-control form-control-sm level-input-${len}" placeholder="A / B / C / etc" required>
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

        /**
         * manipulate form when user in edit mode
         * default value for current_id is 0
         * so, when current_id is not null, this function should be run
         */
        let current_id = $('#current_id').val();
        if (current_id != 0) {
            $.ajax({
                type: 'POST',
                url: base_url + '/intitutions/show-class-level-form',
                data: {
                    id: current_id
                },
                beforeSend: function() {
                    $('#target-class-level').html(`
                        <i class="fa fa-spinner fa-2x fa-spin"></i> <br>
                        ${i18n.view.generate_form}
                    `).css({
                        'textAlign': 'center'
                    });
                },
                success: function(res) {
                    $('#target-class-level').html(res.view);
                }
            })
        }
    } else {
        $('.class-container').addClass('d-none');
    }
}

window.updateForm = updateForm;
window.deleteClassRow = deleteClassRow;
window.deleteLevel = deleteLevel;
window.playCountUp = playCountUp;
window.chooseHomeroomTeacher = chooseHomeroomTeacher;
window.initDetailData = initDetailData;
window.saveHomeroom = saveHomeroom;
window.changeLevel = changeLevel;
window.changeClass = changeClass;
window.appendLevel = appendLevel;
window.appendClass = appendClass;
window.showDetail = showDetail;
window.hasClass = hasClass;