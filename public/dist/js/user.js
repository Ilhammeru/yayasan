/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!******************************!*\
  !*** ./resources/js/user.js ***!
  \******************************/
function createUser(text, type) {
  openModalWithValue('GET', 'form-user', 'modalUser', 'modalUserLabel', text, base_url + '/users/create/' + type);
}
function updateForm(id, text, type) {
  openModalWithValue('GET', 'form-user', 'modalUser', 'modalUserLabel', text, base_url + '/users/' + id + '/edit' + '/' + type);
}
function appendTableFilter(allText, userTypeText, userStatusText) {
  $('#table-users_filter').html("\n            <div class=\"row\">\n                <div class=\"col-md-4\">\n                    <div class=\"\">\n                        <input class=\"form-control\" id=\"all-search\" placeholder=\"".concat(i18n.view.search_anything, "\" style=\"width: 100%;\">\n                    </div>\n                </div>\n                <div class=\"col-md-4\">\n                    <div class=\"\">\n                        <select class=\"form-control\" data-placeholder=\"").concat(i18n.view.search_user_type, "\" id=\"user-type-search\"\n                            style=\"width: 100%;\">\n                            <option></option>\n                            <option value=\"all\">").concat(i18n.view.all, "</option>\n                            <option value=\"2\">").concat(i18n.view.goverment, "</option>\n                            <option value=\"1\">").concat(i18n.view["public"], "</option>\n                        </select>\n                    </div>\n                </div>\n                <div class=\"col-md-4\">\n                    <div class=\"\">\n                        <select class=\"form-control\" data-placeholder=\"").concat(i18n.view.search_user_status, "\" id=\"user-status-search\"\n                            style=\"width: 100%;\">\n                            <option></option>\n                            <option value=\"all\">").concat(i18n.view.all, "</option>\n                            <option value=\"active\">").concat(i18n.view.active, "</option>\n                            <option value=\"inactive\">").concat(i18n.view.inactive, "</option>\n                        </select>\n                    </div>\n                </div>\n            </div>\n        "));
}
function saveItem() {
  var form = $('#form-user');
  var data = new FormData($('#form-user')[0]);
  var method = form.attr('method');
  var url = form.attr('action');
  var status = 0;
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
    beforeSend: function beforeSend() {
      disableButton('btn-save');
      disableButton('btn-cancel');
    },
    success: function success(res) {
      disableButton('btn-save', false);
      disableButton('btn-cancel', false);
      showNotif(false, res.message);
      closeModal('modalUser');
      dt_user.ajax.reload();
    },
    error: function error(err) {
      disableButton('btn-save', false);
      disableButton('btn-cancel', false);
      showNotif(true, err);
    }
  });
}
function getClasses(e) {
  var val = e.value;
  $.ajax({
    type: 'POST',
    url: base_url + '/get-class',
    data: {
      institution_id: val
    },
    beforeSend: function beforeSend() {
      $('#institution_class_id').chosen('destroy');
      $('#institution_class_id').prop('disabled', true);
      $('#institution_class_id').html('');
      $('#institution_class_level_id').chosen('destroy');
      $('#institution_class_level_id').html('');
      $('#institution_class_level_id').prop('disabled', true);
    },
    success: function success(res) {
      console.log('rssses', res);
      var data = res.data;
      var option = "<option></option>";
      for (var a = 0; a < data.length; a++) {
        option += "<option value=\"".concat(data[a].id, "\">").concat(data[a].name, "</option>");
      }
      $('#institution_class_id').html(option);
      $('#institution_class_id').prop('disabled', false);
      $('#institution_class_id').chosen({
        width: '100%'
      });
    },
    error: function error(err) {
      showNotif(true, err);
    }
  });
}
function getLevels(e) {
  var val = e.value;
  $.ajax({
    type: 'POST',
    url: base_url + '/get-level',
    data: {
      class_id: val
    },
    beforeSend: function beforeSend() {
      $('#institution_class_level_id').chosen('destroy');
      $('#institution_class_level_id').html('');
      $('#institution_class_level_id').prop('disabled', true);
    },
    success: function success(res) {
      var data = res.data;
      var option = "<option></option>";
      for (var a = 0; a < data.length; a++) {
        option += "<option value=\"".concat(data[a].id, "\">").concat(data[a].name, "</option>");
      }
      $('#institution_class_level_id').prop('disabled', false);
      $('#institution_class_level_id').html(option);
      $('#institution_class_level_id').chosen({
        width: '100%'
      });
    },
    error: function error(err) {
      setNotif(true, err.responseJSON);
    }
  });
}
function selectImage() {
  $('#user-image').click();
}
function showImage(event) {
  var reader = new FileReader();
  reader.onload = function () {
    $('#preview-image').css({
      'backgroundImage': "url(".concat(reader.result, ")")
    });
  };
  reader.readAsDataURL(event.target.files[0]);
  $('#icon-action-image').removeClass("fa-camera");
  $('#icon-action-image').addClass("fa-times");
  $('#icon-action-image').css({
    'color': 'red'
  });
  $('#icon-action-image').attr('onclick', 'removePreviewImage()');
}
function removePreviewImage() {
  var edit = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
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
  var url = base_url + '/users/' + id + '/' + type + '/show';
  openModalWithValue('GET', 'form-user', 'modalUser', 'modalUserLabel', text, url, null, true);
}
function deleteItem(id, text, type) {
  var url = base_url + "/users/".concat(id, "/").concat(type);
  deleteMaster(text, 'Yes! Delete it', 'Cancel', url, dt_user);
}
function searchUserType(type) {
  var elems = $('.user-type-option');
  for (var a = 0; a < elems.length; a++) {
    elems[a].classList.remove('active');
  }
  $("#search-type-".concat(type)).addClass('active');
  dt_user = createDataTables('table-users', columns, dt_route, {
    user_type: type,
    status: $('#search-user-status').val(),
    name: $('#search-all').val()
  });
}
function searchStatus(e) {
  var val = e.value;
  var activeType = $('.user-type-option.active').data('type');
  dt_user = createDataTables('table-users', columns, dt_route, {
    user_type: activeType,
    status: val,
    name: $('#search-all').val()
  });
}
function searchAll(e) {
  var val = e.value;
  var activeType = $('.user-type-option.active').data('type');
  dt_user = createDataTables('table-users', columns, dt_route, {
    user_type: activeType,
    status: $('#search-user-status').val(),
    name: val
  });
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
/******/ })()
;