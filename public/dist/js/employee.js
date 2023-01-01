/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**********************************!*\
  !*** ./resources/js/employee.js ***!
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
  data: 'position_id',
  name: 'position_id'
}, {
  data: 'institution_id',
  name: 'institution_id'
}, {
  data: 'nip',
  name: 'nip'
}, {
  data: 'phone',
  name: 'phone'
}, {
  data: 'email',
  name: 'email'
}, {
  data: 'status',
  name: 'status',
  orderable: false
}, {
  data: 'action',
  name: 'action',
  className: 'text-center',
  orderable: false
}];
var dt_route = base_url + '/employees/ajax';
var dt_employee = setDataTable('table-employee', columns, dt_route);
function createEmployee(text) {
  openModalWithValue('GET', 'form-employee', 'modalEmployee', 'modalEmployeeLabel', text, base_url + '/employees/create');
}
function updateForm(id, text) {
  openModalWithValue('GET', 'form-employee', 'modalEmployee', 'modalEmployeeLabel', text, base_url + '/employees/' + id + '/edit');
}
function saveItem() {
  var form = $('#form-employee');
  var data = form.serialize();
  var method = form.attr('method');
  var url = form.attr('action');
  var status = 0;
  if ($('#status').prop('checked')) {
    status = 1;
  }
  data = data + '&status=' + status;
  $.ajax({
    type: method,
    url: url,
    data: data,
    beforeSend: function beforeSend() {
      disableButton('btn-save');
      disableButton('btn-cancel');
    },
    success: function success(res) {
      console.log('res', res);
      disableButton('btn-save', false);
      disableButton('btn-cancel', false);
      showNotif(false, res.message);
      closeModal('modalEmployee');
      dt_employee.ajax.reload();
    },
    error: function error(err) {
      disableButton('btn-save', false);
      disableButton('btn-cancel', false);
      showNotif(true, err);
    }
  });
}
function deleteItem(id, text) {
  var url = base_url + "/employees/".concat(id);
  deleteMaster(text, 'Yes! Delete it', 'Cancel', url, dt_employee);
}
function getCity(e) {
  var fromEdit = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
  var val;
  if (!fromEdit) {
    val = e.value;
  } else {
    val = e;
  }
  $.ajax({
    type: 'POST',
    url: base_url + '/get-city',
    data: {
      province_id: val
    },
    beforeSend: function beforeSend() {
      $('#city_id').chosen('destroy');
      $('#city_id').html('');
      $('#city_id').prop('disabled', true);
      $('#district_id').chosen('destroy');
      $('#district_id').prop('disabled', true);
      $('#district_id').html('');
    },
    success: function success(res) {
      var data = res.data;
      var opt = '<option></option>';
      for (var a = 0; a < data.length; a++) {
        var selected = '';
        if (fromEdit) {
          if (fromEdit == data[a].id) {
            selected = 'selected';
          }
        }
        opt += "<option value=\"".concat(data[a].id, "\" ").concat(selected, ">").concat(data[a].name, "</option>");
      }
      $('#city_id').html(opt);
      $('#city_id').prop('disabled', false);
      $('#city_id').chosen({
        width: "100%"
      });
    },
    error: function error(err) {
      showNotif(true, err);
    }
  });
}
function getDistrict(e) {
  var fromEdit = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
  var val;
  if (!fromEdit) {
    val = e.value;
  } else {
    val = e;
  }
  $.ajax({
    type: 'POST',
    url: base_url + '/get-district',
    data: {
      city_id: val
    },
    beforeSend: function beforeSend() {
      $('#district_id').chosen('destroy');
      $('#district_id').html('');
      $('#district_id').prop('disabled', true);
    },
    success: function success(res) {
      var data = res.data;
      var opt = '<option></option>';
      for (var a = 0; a < data.length; a++) {
        var selected = '';
        if (fromEdit) {
          if (fromEdit == data[a].id) {
            selected = 'selected';
          }
        }
        opt += "<option value=\"".concat(data[a].id, "\" ").concat(selected, ">").concat(data[a].name, "</option>");
      }
      $('#district_id').html(opt);
      $('#district_id').prop('disabled', false);
      $('#district_id').chosen({
        width: "100%"
      });
    },
    error: function error(err) {
      showNotif(true, err);
    }
  });
}
window.createEmployee = createEmployee;
window.saveItem = saveItem;
window.deleteItem = deleteItem;
window.updateForm = updateForm;
window.getCity = getCity;
window.getDistrict = getDistrict;
/******/ })()
;