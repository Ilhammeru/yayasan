/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!******************************!*\
  !*** ./resources/js/role.js ***!
  \******************************/
App.datatables();
var columns = [{
  data: 'id',
  render: function render(data, type, row, meta) {
    return meta.row + meta.settings._iDisplayStart + 1;
  },
  width: '5%',
  className: 'text-center'
}, {
  data: 'name',
  name: 'name'
}, {
  data: 'action',
  name: 'action',
  className: 'text-center',
  orderable: false
}];
var dt_route = base_url + '/roles/ajax';
var dt_role = App.setDataTable('table-roles', columns, dt_route);
function deleteItem(id, text) {
  var url = base_url + "/roles/".concat(id);
  App.deleteMaster(text, 'Yes! Delete it', 'Cancel', url, dt_role);
}
function createRole(text) {
  $.ajax({
    type: 'GET',
    url: base_url + '/roles/create',
    beforeSend: function beforeSend() {},
    success: function success(res) {
      $('#modalRoleLabel').text(text);
      $('#form-role').attr('action', base_url + '/roles');
      $('#form-role').attr('method', 'POST');
      $('#modalRole .modal-body').html(res.view);
      $('#modalRole').modal('show');
    },
    error: function error(err) {
      App.setNotif(true, err.responseJSON);
    }
  });
}
function updateForm(id, text) {
  var url = base_url + "/roles/".concat(id);
  $.ajax({
    type: 'GET',
    url: base_url + "/roles/".concat(id, "/edit"),
    beforeSend: function beforeSend() {},
    success: function success(res) {
      buildModalBody(text, url, res.view, 'PUT');
    },
    error: function error(err) {
      App.setNotif(true, err.responseJSON);
    }
  });
}
function buildModalBody(text, url, view, method) {
  $('#modalRoleLabel').text(text);
  $('#form-role').attr('action', url);
  $('#form-role').attr('method', method);
  $('#modalRole .modal-body').html(view);
  $('#modalRole').modal('show');
}
function saveItem() {
  var form = $('#form-role');
  var method = form.attr('method');
  var url = form.attr('action');
  var data = form.serialize();
  $.ajax({
    type: method,
    url: url,
    data: data,
    beforeSend: function beforeSend() {
      disableButton('btn-save');
      disableButton('btn-cancel');
    },
    success: function success(res) {
      disableButton('btn-save', false);
      disableButton('btn-cancel', false);
      App.setNotif(false, res.message);
      dt_role.ajax.reload();
      closeModal('modalRole');
    },
    error: function error(err) {
      disableButton('btn-save', false);
      disableButton('btn-cancel', false);
      App.setNotif(true, err.responseJSON);
    }
  });
}
window.deleteItem = deleteItem;
window.createRole = createRole;
window.updateForm = updateForm;
window.saveItem = saveItem;
window.buildModalBody = buildModalBody;
/******/ })()
;