window.createCategory=function(e){openModalWithValue("GET","form-income-category","modalIncomeCategory","modalIncomeCategoryLabel",e,base_url+"/income/category/create")},window.updateForm=function(e,o){openModalWithValue("GET","form-income-category","modalIncomeCategory","modalIncomeCategoryLabel",o,base_url+"/income/category/"+e+"/edit")},window.saveItem=function(){var e=$("#form-income-category"),o=e.serialize(),t=e.attr("method"),a=e.attr("action");$("#status").prop("checked"),$.ajax({type:t,url:a,data:o,beforeSend:function(){disableButton("btn-save"),disableButton("btn-cancel")},success:function(e){disableButton("btn-save",!1),disableButton("btn-cancel",!1),showNotif(!1,e.message),closeModal("modalIncomeCategory"),dt_income_category.ajax.reload()},error:function(e){disableButton("btn-save",!1),disableButton("btn-cancel",!1),showNotif(!0,e)}})},window.deleteItem=function(e,o){var t=base_url+"/income/category/".concat(e);deleteMaster(o,"Yes! Delete it","Cancel",t,dt_income_category)};