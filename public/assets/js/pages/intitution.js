var Intitution = function() {
    var baseUrl = window.location.origin;

    var saveItem = function() {
        let form = $('#form-intitution');
        let data = form.serialize();
        let url = form.attr('action');
        let method = form.attr('method');

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
                return false;
                dttable.ajax.reload();
                showNotif(false, res.message);
                closeModal('modalIntitution');
            },
            error: function(err) {
                console.log('err',err);
                $('#btn-save').prop('disabled', false);
                $('#btn-cancel').prop('disabled', false);
                showNotif(true, err);
            }
        })
    }

    var createForm = function(
        createText
    ) {
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
                setNotif(true, err);
            }
        })
    }

    var updateForm = function(createText, id) {
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
                showNotif(true, err);
            }
        })
    }

    var chnageClass = function(e) {
        let elem = $('input[name="has_class"]')[0].checked;
        if (elem) {
            
        }
    }

    var appendLvl = function() {
        let current = $('.level-helper');
        let classWrapper = $('.class-wrapper');
        let classLen = classWrapper.length;
        let len = current.length;
        let elem = `
            <div class="col-md-6 level-helper" id="level-helper-f-${len + 1}-${classLen - 1}"></div>
            <div class="col-md-6" id="level-helper-s-${len + 1}-${classLen - 1}">
                <div class="input-group">
                    <input type="text" id="class_level" name="ins[${classLen - 1}][class][${len + 1}][level]" class="form-control form-control-sm" placeholder="A / B / C / etc" required>
                    <span class="input-group-addon"><i class="gi gi-remove_2" onclick="deleteLevel(${len + 1}, ${classLen - 1})" style="color: red; cursor: pointer;"></i></span>
                </div>
            </div>
        `;
        $('#target-class-level-1').append(elem);
    }

    var appendCls = function() {
        let elems = $('.class-wrapper');
        let len = elems.length;
        let form = `
            <div class="border p-3 mb-3 class-wrapper" style="position: relative; width: 100%;">
                <span class="gi gi-circle_plus text-primary" onclick="appendClass()" style="position: absolute; top: -4px; right: -2px; font-size: 18px; cursor: pointer;"></span>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="class_name" class="control-label">{{ __('view.class_name') }}</label>
                            <input type="text" name="ins[0][class_name]" placeholder="{{ __('view.class_name') }}" class="form-control form-control-sm" id="class_name">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="class_level" class="control-label">{{ __('view.class_level') }}</label>
                            <div class="input-group">
                                <input type="text" id="class_level" name="ins[0][class][0][level]" class="form-control form-control-sm" placeholder="A / B / C / etc" required>
                                <span class="input-group-addon"><i class="gi gi-plus" onclick="appendLevel()" style="cursor: pointer;"></i></span>
                            </div>
                        </div>
                    </div>
                    <div id="target-class-level-1"></div>
                </div>
            </div>
        `;
        $('#target-class').append(form);
    }

    var deleteLvl = function(id, classId) {
        $(`#level-helper-f-${id}-${classId}`).remove();
        $(`#level-helper-s-${id}-${classId}`).remove();
    }

    return  {
        save: function() {
            saveItem();
        },
        addItem: function(createText) {
            createForm(createText)
        },
        updateItem: function(createText, id) {
            updateForm(createText, id);
        },
        hasClass: function(e) {
            chnageClass(e);
        },
        appendLevel: function() {
            appendLvl();
        },
        deleteLevel: function(id) {
            deleteLvl(id);
        },
        appendClass: function() {
            appendCls();
        }
    }
}();

$(function(){ Intitution.addItem('create'); });