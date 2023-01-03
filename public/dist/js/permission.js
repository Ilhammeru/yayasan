/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!************************************!*\
  !*** ./resources/js/permission.js ***!
  \************************************/
dtIntegration();
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
var dt_route = base_url + '/permissions/ajax';
var dt_permission = setDataTable('table-permissions', columns, dt_route);
function createPermission(text) {
  openModalWithValue('GET', 'form-permission', 'modalPermission', 'modalPermissionLabel', text, base_url + '/permissions/create');
}
function updateForm(id, text) {
  openModalWithValue('GET', 'form-permission', 'modalPermission', 'modalPermissionLabel', text, base_url + '/permissions/' + id + '/edit');
}
function saveItem() {
  var form = $('#form-permission');
  var data = form.serialize();
  var method = form.attr('method');
  var url = form.attr('action');
  $.ajax({
    type: method,
    url: url,
    data: data,
    beforeSend: function beforeSend() {
      disableButton('btn-save');
      disableButton('btn-cancel');
    },
    success: function success(res) {
        ;
      disableButton('btn-save', false);
      disableButton('btn-cancel', false);
      showNotif(false, res.message);
      closeModal('modalPermission');
      dt_permission.ajax.reload();
    },
    error: function error(err) {
      disableButton('btn-save', false);
      disableButton('btn-cancel', false);
      showNotif(true, err);
    }
  });
}
function deleteItem(id, text) {
  var url = base_url + "/permissions/".concat(id);
  deleteMaster(text, 'Yes! Delete it', 'Cancel', url, dt_permission);
}
window.createPermission = createPermission;
window.saveItem = saveItem;
window.updateForm = updateForm;
window.deleteItem = deleteItem;
/******/ })()
;