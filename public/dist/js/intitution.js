/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/countup.js/dist/countUp.min.js":
/*!*****************************************************!*\
  !*** ./node_modules/countup.js/dist/countUp.min.js ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "CountUp": () => (/* binding */ CountUp)
/* harmony export */ });
var __assign=undefined&&undefined.__assign||function(){return(__assign=Object.assign||function(t){for(var i,n=1,s=arguments.length;n<s;n++)for(var a in i=arguments[n])Object.prototype.hasOwnProperty.call(i,a)&&(t[a]=i[a]);return t}).apply(this,arguments)},CountUp=function(){function t(t,i,n){var s=this;this.endVal=i,this.options=n,this.version="2.3.2",this.defaults={startVal:0,decimalPlaces:0,duration:2,useEasing:!0,useGrouping:!0,smartEasingThreshold:999,smartEasingAmount:333,separator:",",decimal:".",prefix:"",suffix:"",enableScrollSpy:!1,scrollSpyDelay:200,scrollSpyOnce:!1},this.finalEndVal=null,this.useEasing=!0,this.countDown=!1,this.error="",this.startVal=0,this.paused=!0,this.once=!1,this.count=function(t){s.startTime||(s.startTime=t);var i=t-s.startTime;s.remaining=s.duration-i,s.useEasing?s.countDown?s.frameVal=s.startVal-s.easingFn(i,0,s.startVal-s.endVal,s.duration):s.frameVal=s.easingFn(i,s.startVal,s.endVal-s.startVal,s.duration):s.frameVal=s.startVal+(s.endVal-s.startVal)*(i/s.duration);var n=s.countDown?s.frameVal<s.endVal:s.frameVal>s.endVal;s.frameVal=n?s.endVal:s.frameVal,s.frameVal=Number(s.frameVal.toFixed(s.options.decimalPlaces)),s.printValue(s.frameVal),i<s.duration?s.rAF=requestAnimationFrame(s.count):null!==s.finalEndVal?s.update(s.finalEndVal):s.callback&&s.callback()},this.formatNumber=function(t){var i,n,a,e,r=t<0?"-":"";i=Math.abs(t).toFixed(s.options.decimalPlaces);var o=(i+="").split(".");if(n=o[0],a=o.length>1?s.options.decimal+o[1]:"",s.options.useGrouping){e="";for(var l=0,h=n.length;l<h;++l)0!==l&&l%3==0&&(e=s.options.separator+e),e=n[h-l-1]+e;n=e}return s.options.numerals&&s.options.numerals.length&&(n=n.replace(/[0-9]/g,function(t){return s.options.numerals[+t]}),a=a.replace(/[0-9]/g,function(t){return s.options.numerals[+t]})),r+s.options.prefix+n+a+s.options.suffix},this.easeOutExpo=function(t,i,n,s){return n*(1-Math.pow(2,-10*t/s))*1024/1023+i},this.options=__assign(__assign({},this.defaults),n),this.formattingFn=this.options.formattingFn?this.options.formattingFn:this.formatNumber,this.easingFn=this.options.easingFn?this.options.easingFn:this.easeOutExpo,this.startVal=this.validateValue(this.options.startVal),this.frameVal=this.startVal,this.endVal=this.validateValue(i),this.options.decimalPlaces=Math.max(this.options.decimalPlaces),this.resetDuration(),this.options.separator=String(this.options.separator),this.useEasing=this.options.useEasing,""===this.options.separator&&(this.options.useGrouping=!1),this.el="string"==typeof t?document.getElementById(t):t,this.el?this.printValue(this.startVal):this.error="[CountUp] target is null or undefined","undefined"!=typeof window&&this.options.enableScrollSpy&&(this.error?console.error(this.error,t):(window.onScrollFns=window.onScrollFns||[],window.onScrollFns.push(function(){return s.handleScroll(s)}),window.onscroll=function(){window.onScrollFns.forEach(function(t){return t()})},this.handleScroll(this)))}return t.prototype.handleScroll=function(t){if(t&&window&&!t.once){var i=window.innerHeight+window.scrollY,n=t.el.getBoundingClientRect(),s=n.top+n.height+window.pageYOffset;s<i&&s>window.scrollY&&t.paused?(t.paused=!1,setTimeout(function(){return t.start()},t.options.scrollSpyDelay),t.options.scrollSpyOnce&&(t.once=!0)):window.scrollY>s&&!t.paused&&t.reset()}},t.prototype.determineDirectionAndSmartEasing=function(){var t=this.finalEndVal?this.finalEndVal:this.endVal;this.countDown=this.startVal>t;var i=t-this.startVal;if(Math.abs(i)>this.options.smartEasingThreshold&&this.options.useEasing){this.finalEndVal=t;var n=this.countDown?1:-1;this.endVal=t+n*this.options.smartEasingAmount,this.duration=this.duration/2}else this.endVal=t,this.finalEndVal=null;null!==this.finalEndVal?this.useEasing=!1:this.useEasing=this.options.useEasing},t.prototype.start=function(t){this.error||(this.callback=t,this.duration>0?(this.determineDirectionAndSmartEasing(),this.paused=!1,this.rAF=requestAnimationFrame(this.count)):this.printValue(this.endVal))},t.prototype.pauseResume=function(){this.paused?(this.startTime=null,this.duration=this.remaining,this.startVal=this.frameVal,this.determineDirectionAndSmartEasing(),this.rAF=requestAnimationFrame(this.count)):cancelAnimationFrame(this.rAF),this.paused=!this.paused},t.prototype.reset=function(){cancelAnimationFrame(this.rAF),this.paused=!0,this.resetDuration(),this.startVal=this.validateValue(this.options.startVal),this.frameVal=this.startVal,this.printValue(this.startVal)},t.prototype.update=function(t){cancelAnimationFrame(this.rAF),this.startTime=null,this.endVal=this.validateValue(t),this.endVal!==this.frameVal&&(this.startVal=this.frameVal,null==this.finalEndVal&&this.resetDuration(),this.finalEndVal=null,this.determineDirectionAndSmartEasing(),this.rAF=requestAnimationFrame(this.count))},t.prototype.printValue=function(t){var i=this.formattingFn(t);"INPUT"===this.el.tagName?this.el.value=i:"text"===this.el.tagName||"tspan"===this.el.tagName?this.el.textContent=i:this.el.innerHTML=i},t.prototype.ensureNumber=function(t){return"number"==typeof t&&!isNaN(t)},t.prototype.validateValue=function(t){var i=Number(t);return this.ensureNumber(i)?i:(this.error="[CountUp] invalid start or end value: ".concat(t),null)},t.prototype.resetDuration=function(){this.startTime=null,this.duration=1e3*Number(this.options.duration),this.remaining=this.duration},t}();

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!************************************!*\
  !*** ./resources/js/intitution.js ***!
  \************************************/
var _require = __webpack_require__(/*! countup.js */ "./node_modules/countup.js/dist/countUp.min.js"),
  CountUp = _require.CountUp;
initDetailData();
function playCountUp(number, target) {
  var duration = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 1;
  var countUpOption = {
    startVal: 0,
    duration: duration,
    separator: '.'
  };
  var countUp = new CountUp(target, number, countUpOption);
  countUp.start();
}
function updateForm(id) {
  var createText = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'Update institution';
  var url = base_url + "/intitutions/".concat(id, "/edit");
  var storeUrl = base_url + "/intitutions/".concat(id);
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
      showNotif(true, err);
    }
  });
}
function deleteLevelB(id, classId) {
  var className = $("input[name=\"ins[".concat(classId, "][class_name]\"]")).val();
  var levelName = $("input[name=\"ins[".concat(classId, "][class][").concat(id, "][level]\"]")).val();
  var format = {
    class_name: className,
    level: [levelName]
  };
  $("#level-helper-f-".concat(id, "-").concat(classId)).remove();
  $("#level-helper-s-".concat(id, "-").concat(classId)).remove();
}
function deleteClassRow(id) {
  var institutionId = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
  if (institutionId != 0) {
    sweetAlert({
      text: i18n.view.delete_text,
      icon: "warning",
      buttons: true,
      dangerMode: true,
      confirmButtonText: i18n.view.confirm_delete
    }).then(function (willDelete) {
      if (willDelete) {
        var levelWrapper = $('.level-wrapper-' + id);
        var className = $("input[name=\"ins[".concat(id, "][class_name]\"]")).val();
        var format = {
          class_name: className,
          level: []
        };
        if (className) {
          for (var a = 0; a < levelWrapper.length; a++) {
            var ids = levelWrapper[a].id;
            var levelId = $('#' + ids).data('key');
            var levelName = $("input[name=\"ins[".concat(id, "][class][").concat(levelId, "][level]\"]")).val();
            format.level.push(levelName);
          }
        }
        var ins_id = $('#current_id').val();
        format.id = ins_id;
        $.ajax({
          type: 'POST',
          url: base_url + '/intitutions/delete-class',
          data: format,
          beforeSend: function beforeSend() {
            loadingPage();
          },
          success: function success(res) {
            loadingPage(false);
            showNotif(false, res.message);
            $('#class-wrapper-' + id).remove();
          },
          error: function error(err) {
            loadingPage(false);
            showNotif(true, err);
          }
        });
      }
    });
  } else {
    $('#class-wrapper-' + id).remove();
  }
}
function deleteLevel(id, classId) {
  var institutionId = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 0;
  if (institutionId != 0) {
    sweetAlert({
      text: i18n.view.delete_text,
      icon: "warning",
      buttons: true,
      dangerMode: true,
      confirmButtonText: i18n.view.confirm_delete
    }).then(function (willDelete) {
      if (willDelete) {
        var levelWrapper = $('.level-wrapper-' + id);
        var className = $("input[name=\"ins[".concat(classId, "][class_name]\"]")).val();
        var format = {
          class_name: className,
          level: []
        };
        if (className) {
          var levelName = $("input[name=\"ins[".concat(classId, "][class][").concat(id, "][level]\"]")).val();
          format.level.push(levelName);
        }
        var ins_id = $('#current_id').val();
        format.id = ins_id;
        $.ajax({
          type: 'POST',
          url: base_url + '/intitutions/delete-level',
          data: format,
          beforeSend: function beforeSend() {
            loadingPage();
          },
          success: function success(res) {
            loadingPage(false);
            showNotif(false, res.message);
            $('#level-helper-s-' + id + '-' + classId).remove();
          },
          error: function error(err) {
            loadingPage(false);
            showNotif(true, err);
          }
        });
      }
    });
  } else {
    $('#level-helper-s-' + id + '-' + classId).remove();
  }
}
function showDetail(param) {
  var targetLoad = 'target-detail-data';
  if (param.update) {
    targetLoad = 'target-detail-table-data';
  }
  $.ajax({
    type: 'POST',
    url: base_url + '/intitutions/detail-data/intitutions',
    data: param,
    beforeSend: function beforeSend() {
      $('#' + targetLoad).html("\n                <i class=\"fa fa-spinner fa-2x fa-spin\"></i> <br>\n                ".concat(i18n.view.generate_data, "\n            ")).css({
        'textAlign': 'center'
      });
    },
    success: function success(res) {
      console.log(res);
      $('#' + targetLoad).html(res.view).css({
        'textAlign': 'unset'
      });
      if (param.update == undefined || !param.update) {
        // set filter class active
        $('.filter-class').removeClass('themed-border-default').removeClass('themed-background-default').removeClass('themed-color-white').removeClass('btn-default').addClass('btn-default');
        $('#filter-class-' + res.data.data.classes[0].id).removeClass('btn-default').addClass('themed-border-default').addClass('themed-background-default').addClass('themed-color-white');
      }
      if (param.update) {
        $('.filter-level').removeClass('themed-border-default').removeClass('themed-background-default').removeClass('themed-color-white').removeClass('btn-default').addClass('btn-default');
        $('#filter-level-' + res.data.level_id).removeClass('btn-default').addClass('themed-border-default').addClass('themed-background-default').addClass('themed-color-white');
      }
    },
    error: function error(err) {}
  });
}
function chooseHomeroomTeacher(classId, levelId, institutionId) {
  $.ajax({
    type: 'GET',
    url: base_url + '/intitutions/show-homeroom-teacher?class_id=' + classId + '&level_id=' + levelId + '&institution_id=' + institutionId,
    success: function success(res) {
      $('#modalChooseHomeroom .modal-body #target-form').html(res.view);
      $('#modalChooseHomeroom').modal('show');
      $('#homeroom').chosen({
        width: '100%'
      });
    },
    error: function error(err) {
      showNotif(true, err);
    }
  });
}
function saveHomeroom() {
  var form = $('#form-select-homeroom');
  var data = form.serialize();
  $.ajax({
    type: 'POST',
    url: base_url + '/intitutions/store-homeroom',
    data: data,
    beforeSend: function beforeSend() {
      loadingPage(true, i18n.view.saving);
    },
    success: function success(res) {
      loadingPage(false);
      showNotif(false, res.message);
      $('#modalChooseHomeroom').modal('hide');
      $('#target-homeroom-teacher').html("\n            <b>".concat(res.data.homeroom, "</b>\n            <a style=\"cursor: pointer;\" onclick=\"chooseHomeroomTeacher(").concat(res.data.class_id, ", ").concat(res.data.level_id, ", ").concat(res.data.institution_id, ")\">").concat(i18n.view.change_homeroom, " <i class=\"fa fa-share\"></i></a>\n            "));
    },
    error: function error(err) {
      showNotif(true, err);
      loadingPage(false);
    }
  });
}
function initDetailData() {
  var institutionId = $('#df_institution_id').val();
  var classId = $('#df_class_id').val();
  var levelId = $('#df_level_id').val();
  var param = {
    institution_id: institutionId,
    class_id: classId,
    level_id: levelId
  };
  showDetail(param);
}
function changeLevel(institutionId, levelId, classId) {
  // target-detail-table-data
  var param = {
    institution_id: institutionId,
    class_id: classId,
    level_id: levelId,
    update: true
  };
  showDetail(param);
}
function changeClass(classId, institutionId) {
  var param = {
    institution_id: institutionId,
    class_id: classId,
    level_id: 0
  };
  showDetail(param);
}
function appendLevel(classId) {
  var current = $('.level-helper-s');
  var classWrapper = $('.class-wrapper');
  var levelWrapper = $('.level-wrapper-' + classId);
  var classLen = classWrapper.length;
  var len = levelWrapper.length;
  var elem = "\n        <div class=\"col-md-6 level-wrapper-".concat(classId, "\" data-key=\"").concat(len, "\" id=\"level-helper-f-").concat(len, "-").concat(classLen, "\"></div>\n        <div class=\"col-md-6\" id=\"level-helper-s-").concat(len, "-").concat(classLen, "\">\n            <div class=\"input-group\">\n                <input type=\"text\" id=\"class_level-").concat(len, "-").concat(classId, "\" data-key=\"").concat(len, "\" name=\"ins[").concat(classId, "][class][").concat(len, "][level]\" class=\"form-control form-control-sm level-input-").concat(classId, "\" placeholder=\"A / B / C / etc\" required>\n                <span class=\"input-group-addon\"><i class=\"gi gi-remove_2\" onclick=\"deleteLevel(").concat(len, ", ").concat(classLen, ")\" style=\"color: red; cursor: pointer;\"></i></span>\n            </div>\n        </div>\n    ");
  $("#target-class-level-".concat(classId)).append(elem);
  $("#class_level-".concat(len, "-").concat(classId)).focus();
}
function appendClass(labelName, labelLevel) {
  var elems = $('.class-wrapper');
  var len = elems.length;
  var form = "\n        <div class=\"border p-3 mb-3 class-wrapper\" id=\"class-wrapper-".concat(len, "\" style=\"position: relative; width: 100%;\">\n            <span class=\"gi gi-remove text-danger\" onclick=\"deleteClassRow(").concat(len, ")\" style=\"position: absolute; top: -4px; right: -2px; font-size: 18px; cursor: pointer;\"></span>\n            <div class=\"row\">\n                <div class=\"col-md-6 col-sm-12 level-wrapper-").concat(len, "\" data-key=\"0\" id=\"level-helper-f-0-").concat(len, "\">\n                    <div class=\"form-group mb-3\">\n                        <label for=\"class_name\" class=\"control-label\">").concat(labelName, "</label>\n                        <input type=\"text\" name=\"ins[").concat(len, "][class_name]\" data-key=\"").concat(len, "\" placeholder=\"").concat(labelName, "\" class=\"form-control form-control-sm class-input-").concat(len, "\" id=\"class_name-").concat(len, "\">\n                    </div>\n                </div>\n                <div class=\"col-md-6 col-sm-12\">\n                    <div class=\"form-group\" style=\"margin-bottom: 0;\">\n                        <label for=\"class_level\" class=\"control-label\">").concat(labelLevel, "</label>\n                        <div class=\"input-group\">\n                            <input type=\"text\" id=\"class_level\" data-key=\"0\" name=\"ins[").concat(len, "][class][0][level]\" class=\"form-control form-control-sm level-input-").concat(len, "\" placeholder=\"A / B / C / etc\" required>\n                            <span class=\"input-group-addon\"><i class=\"gi gi-plus\" onclick=\"appendLevel(").concat(len, ")\" style=\"cursor: pointer;\"></i></span>\n                        </div>\n                    </div>\n                </div>\n                <div id=\"target-class-level-").concat(len, "\"></div>\n            </div>\n        </div>\n    ");
  $('#target-class').append(form);
  $("#class_name-".concat(len)).focus();
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

    /**
     * manipulate form when user in edit mode
     * default value for current_id is 0
     * so, when current_id is not null, this function should be run
     */
    var current_id = $('#current_id').val();
    if (current_id != 0) {
      $.ajax({
        type: 'POST',
        url: base_url + '/intitutions/show-class-level-form',
        data: {
          id: current_id
        },
        beforeSend: function beforeSend() {
          $('#target-class-level').html("\n                        <i class=\"fa fa-spinner fa-2x fa-spin\"></i> <br>\n                        ".concat(i18n.view.generate_form, "\n                    ")).css({
            'textAlign': 'center'
          });
        },
        success: function success(res) {
          $('#target-class-level').html(res.view);
        }
      });
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
})();

/******/ })()
;