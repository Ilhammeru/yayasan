/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**********************************!*\
  !*** ./resources/js/position.js ***!
  \**********************************/
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
  data: 'role_id',
  name: 'role_id'
}, {
  data: 'action',
  name: 'action',
  className: 'text-center',
  orderable: false
}];
var dt_route = base_url + '/positions/ajax';
var dt_position = setDataTable('table-positions', columns, dt_route);
function createPosition(text) {
  openModalWithValue('GET', 'form-position', 'modalPosition', 'modalPositionLabel', text, base_url + '/positions/create');
}
function updateForm(id, text) {
  openModalWithValue('GET', 'form-position', 'modalPosition', 'modalPositionLabel', text, base_url + '/positions/' + id);
}
function saveItem() {
  var form = $('#form-position');
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
      disableButton('btn-save', false);
      disableButton('btn-cancel', false);
      showNotif(false, res.message);
      closeModal('modalPosition');
      dt_position.ajax.reload();
    },
    error: function error(err) {
      showNotif(true, err);
    }
  });
}
function deleteItem(id, text) {
  var url = base_url + "/positions/".concat(id);
  deleteMaster(text, 'Yes! Delete it', 'Cancel', url, dt_position);
}
window.createPosition = createPosition;
window.saveItem = saveItem;
window.deleteItem = deleteItem;
window.updateForm = updateForm;
/******/ })()
;