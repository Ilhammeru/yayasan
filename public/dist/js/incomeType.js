/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!************************************!*\
  !*** ./resources/js/incomeType.js ***!
  \************************************/
function createType(text) {
  openModalWithValue('GET', 'form-income-type', 'modalIncomeType', 'modalIncomeTypeLabel', text, base_url + '/income/type/create');
}
function updateForm(id, text) {
  openModalWithValue('GET', 'form-income-type', 'modalIncomeType', 'modalIncomeTypeLabel', text, base_url + '/income/type/' + id + '/edit');
}
function saveItem() {
  var form = $('#form-income-type');
  var data = form.serialize();
  var method = form.attr('method');
  var url = form.attr('action');
  var status = 0;
  if ($('#status').prop('checked')) {
    status = 1;
  }
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
      closeModal('modalIncomeType');
      dt_income_type.ajax.reload();
    },
    error: function error(err) {
      ;
      disableButton('btn-save', false);
      disableButton('btn-cancel', false);
      showNotif(true, err);
    }
  });
}
function deleteItem(id, text) {
  var url = base_url + "/income/type/".concat(id);
  deleteMaster(text, 'Yes! Delete it', 'Cancel', url, dt_income_type);
}
window.createType = createType;
window.updateForm = updateForm;
window.saveItem = saveItem;
window.deleteItem = deleteItem;
/******/ })()
;