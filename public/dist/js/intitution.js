/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!************************************!*\
  !*** ./resources/js/intitution.js ***!
  \************************************/
var baseUrl = window.location.origin;
function saveItem() {
  var form = $('#form-intitution');
  var data = form.serialize();
  var url = form.attr('action');
  var method = form.attr('method');
  var classWrapper = $('.class-wrapper');
  for (var a = 0; a < classWrapper.length; a++) {
    var classInput = $(".class-input-".concat(a)).val();
    var levelInput = $(".level-input-".concat(a)).val();
    if (levelInput != '' && classInput == '') {
      return App.setNotif(true, 'Class cannot be empty if level of class is declare');
    }
  }
  $.ajax({
    type: method,
    url: url,
    data: data,
    beforeSend: function beforeSend() {
      $('#btn-save').prop('disabled', true);
      $('#btn-cancel').prop('disabled', true);
    },
    success: function success(res) {
      console.log(res);
      $('#btn-save').prop('disabled', false);
      $('#btn-cancel').prop('disabled', false);
      dttable.ajax.reload();
      App.setNotif(false, res.message);
      closeModal('modalIntitution');
    },
    error: function error(err) {
      console.log('err', err);
      $('#btn-save').prop('disabled', false);
      $('#btn-cancel').prop('disabled', false);
      App.setNotif(true, err);
    }
  });
}
function hasClass() {
  var elem = $('input[name="has_class"]')[0].checked;
  if (elem) {
    $('.class-container').removeClass('d-none');
    var all = $('.class-wrapper');
    for (var a = 0; a < all.length; a++) {
      if (a != 0) {
        var id = all[a].id;
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
  var url = baseUrl + '/intitutions/create';
  var storeUrl = baseUrl + '/intitutions';
  $.ajax({
    type: "GET",
    url: url,
    dataType: 'json',
    success: function success(res) {
      $('#modalIntitutionLabel').text(createText);
      $('#modalIntitution .modal-body').html(res.view);
      $('#modalIntitution').modal('show');
      $('#form-intitution').attr('action', storeUrl);
      $('#form-intitution').attr('method', "POST");
    },
    error: function error(err) {
      App.setNotif(true, err);
    }
  });
}
function appendLevel(classId) {
  var current = $('.level-helper-s');
  var classWrapper = $('.class-wrapper');
  var levelWrapper = $('.level-wrapper-' + classId);
  var classLen = classWrapper.length;
  var len = levelWrapper.length;
  var elem = "\n        <div class=\"col-md-6 level-wrapper-".concat(classId, "\" id=\"level-helper-f-").concat(len, "-").concat(classLen, "\"></div>\n        <div class=\"col-md-6\" id=\"level-helper-s-").concat(len, "-").concat(classLen, "\">\n            <div class=\"input-group\">\n                <input type=\"text\" id=\"class_level-").concat(len, "-").concat(classId, "\" name=\"ins[").concat(classId, "][class][").concat(len, "][level]\" class=\"form-control form-control-sm level-input-").concat(classId, "\" placeholder=\"A / B / C / etc\" required>\n                <span class=\"input-group-addon\"><i class=\"gi gi-remove_2\" onclick=\"deleteLevel(").concat(len, ", ").concat(classLen, ")\" style=\"color: red; cursor: pointer;\"></i></span>\n            </div>\n        </div>\n    ");
  $("#target-class-level-".concat(classId)).append(elem);
  $("#class_level-".concat(len, "-").concat(classId)).focus();
}
function appendClass(labelName, labelLevel) {
  var elems = $('.class-wrapper');
  var len = elems.length;
  var form = "\n        <div class=\"border p-3 mb-3 class-wrapper\" id=\"class-wrapper-".concat(len, "\" style=\"position: relative; width: 100%;\">\n            <span class=\"gi gi-remove text-danger\" onclick=\"deleteClassRow(").concat(len, ")\" style=\"position: absolute; top: -4px; right: -2px; font-size: 18px; cursor: pointer;\"></span>\n            <div class=\"row\">\n                <div class=\"col-md-6 col-sm-12 level-wrapper-").concat(len, "\">\n                    <div class=\"form-group mb-3\">\n                        <label for=\"class_name\" class=\"control-label\">").concat(labelName, "</label>\n                        <input type=\"text\" name=\"ins[").concat(len, "][class_name]\" placeholder=\"").concat(labelName, "\" class=\"form-control form-control-sm class-input-").concat(len, "\" id=\"class_name-").concat(len, "\">\n                    </div>\n                </div>\n                <div class=\"col-md-6 col-sm-12\">\n                    <div class=\"form-group\" style=\"margin-bottom: 0;\">\n                        <label for=\"class_level\" class=\"control-label\">").concat(labelLevel, "</label>\n                        <div class=\"input-group\">\n                            <input type=\"text\" id=\"class_level\" name=\"ins[").concat(len, "][class][0][level]\" class=\"form-control form-control-sm level-input-").concat(len, "\" placeholder=\"A / B / C / etc\" required>\n                            <span class=\"input-group-addon\"><i class=\"gi gi-plus\" onclick=\"appendLevel(").concat(len, ")\" style=\"cursor: pointer;\"></i></span>\n                        </div>\n                    </div>\n                </div>\n                <div id=\"target-class-level-").concat(len, "\"></div>\n            </div>\n        </div>\n    ");
  $('#target-class').append(form);
  $("#class_name-".concat(len)).focus();
}
function deleteClassRow(id) {
  $("#class-wrapper-".concat(id)).remove();
}
function deleteLevel(id, classId) {
  $("#level-helper-f-".concat(id, "-").concat(classId)).remove();
  $("#level-helper-s-".concat(id, "-").concat(classId)).remove();
}
function updateForm(id) {
  var createText = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'Update institution';
  var url = baseUrl + "/intitutions/".concat(id, "/edit");
  var storeUrl = baseUrl + "/intitutions/".concat(id);
  $.ajax({
    type: "GET",
    url: url,
    dataType: 'json',
    success: function success(res) {
      $('#modalIntitutionLabel').text(createText);
      $('#modalIntitution .modal-body').html(res.view);
      $('#modalIntitution').modal('show');
      $('#form-intitution').attr('action', storeUrl);
      $('#form-intitution').attr('method', "PUT");
    },
    error: function error(err) {
      App.setNotif(true, err);
    }
  });
}
window.createForm = createForm;
window.saveItem = saveItem;
window.appendLevel = appendLevel;
window.deleteLevel = deleteLevel;
window.appendClass = appendClass;
window.updateForm = updateForm;
window.deleteClassRow = deleteClassRow;
window.hasClass = hasClass;
/******/ })()
;