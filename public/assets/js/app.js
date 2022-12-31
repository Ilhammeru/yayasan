/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/izitoast/dist/js/iziToast.js":
/*!***************************************************!*\
  !*** ./node_modules/izitoast/dist/js/iziToast.js ***!
  \***************************************************/
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*
* iziToast | v1.4.0
* http://izitoast.marcelodolce.com
* by Marcelo Dolce.
*/
(function (root, factory) {
	if(true) {
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory(root)),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {}
})(typeof __webpack_require__.g !== 'undefined' ? __webpack_require__.g : window || this.window || this.global, function (root) {

	'use strict';

	//
	// Variables
	//
	var $iziToast = {},
		PLUGIN_NAME = 'iziToast',
		BODY = document.querySelector('body'),
		ISMOBILE = (/Mobi/.test(navigator.userAgent)) ? true : false,
		ISCHROME = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor),
		ISFIREFOX = typeof InstallTrigger !== 'undefined',
		ACCEPTSTOUCH = 'ontouchstart' in document.documentElement,
		POSITIONS = ['bottomRight','bottomLeft','bottomCenter','topRight','topLeft','topCenter','center'],
		THEMES = {
			info: {
				color: 'blue',
				icon: 'ico-info'
			},
			success: {
				color: 'green',
				icon: 'ico-success'
			},
			warning: {
				color: 'orange',
				icon: 'ico-warning'
			},
			error: {
				color: 'red',
				icon: 'ico-error'
			},
			question: {
				color: 'yellow',
				icon: 'ico-question'
			}
		},
		MOBILEWIDTH = 568,
		CONFIG = {};

	$iziToast.children = {};

	// Default settings
	var defaults = {
		id: null, 
		class: '',
		title: '',
		titleColor: '',
		titleSize: '',
		titleLineHeight: '',
		message: '',
		messageColor: '',
		messageSize: '',
		messageLineHeight: '',
		backgroundColor: '',
		theme: 'light', // dark
		color: '', // blue, red, green, yellow
		icon: '',
		iconText: '',
		iconColor: '',
		iconUrl: null,
		image: '',
		imageWidth: 50,
		maxWidth: null,
		zindex: null,
		layout: 1,
		balloon: false,
		close: true,
		closeOnEscape: false,
		closeOnClick: false,
		displayMode: 0,
		position: 'bottomRight', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter, center
		target: '',
		targetFirst: true,
		timeout: 5000,
		rtl: false,
		animateInside: true,
		drag: true,
		pauseOnHover: true,
		resetOnHover: false,
		progressBar: true,
		progressBarColor: '',
		progressBarEasing: 'linear',
		overlay: false,
		overlayClose: false,
		overlayColor: 'rgba(0, 0, 0, 0.6)',
		transitionIn: 'fadeInUp', // bounceInLeft, bounceInRight, bounceInUp, bounceInDown, fadeIn, fadeInDown, fadeInUp, fadeInLeft, fadeInRight, flipInX
		transitionOut: 'fadeOut', // fadeOut, fadeOutUp, fadeOutDown, fadeOutLeft, fadeOutRight, flipOutX
		transitionInMobile: 'fadeInUp',
		transitionOutMobile: 'fadeOutDown',
		buttons: {},
		inputs: {},
		onOpening: function () {},
		onOpened: function () {},
		onClosing: function () {},
		onClosed: function () {}
	};

	//
	// Methods
	//


	/**
	 * Polyfill for remove() method
	 */
	if(!('remove' in Element.prototype)) {
	    Element.prototype.remove = function() {
	        if(this.parentNode) {
	            this.parentNode.removeChild(this);
	        }
	    };
	}

	/*
     * Polyfill for CustomEvent for IE >= 9
     * https://developer.mozilla.org/en-US/docs/Web/API/CustomEvent/CustomEvent#Polyfill
     */
    if(typeof window.CustomEvent !== 'function') {
        var CustomEventPolyfill = function (event, params) {
            params = params || { bubbles: false, cancelable: false, detail: undefined };
            var evt = document.createEvent('CustomEvent');
            evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
            return evt;
        };

        CustomEventPolyfill.prototype = window.Event.prototype;

        window.CustomEvent = CustomEventPolyfill;
    }

	/**
	 * A simple forEach() implementation for Arrays, Objects and NodeLists
	 * @private
	 * @param {Array|Object|NodeList} collection Collection of items to iterate
	 * @param {Function} callback Callback function for each iteration
	 * @param {Array|Object|NodeList} scope Object/NodeList/Array that forEach is iterating over (aka `this`)
	 */
	var forEach = function (collection, callback, scope) {
		if(Object.prototype.toString.call(collection) === '[object Object]') {
			for (var prop in collection) {
				if(Object.prototype.hasOwnProperty.call(collection, prop)) {
					callback.call(scope, collection[prop], prop, collection);
				}
			}
		} else {
			if(collection){
				for (var i = 0, len = collection.length; i < len; i++) {
					callback.call(scope, collection[i], i, collection);
				}
			}
		}
	};

	/**
	 * Merge defaults with user options
	 * @private
	 * @param {Object} defaults Default settings
	 * @param {Object} options User options
	 * @returns {Object} Merged values of defaults and options
	 */
	var extend = function (defaults, options) {
		var extended = {};
		forEach(defaults, function (value, prop) {
			extended[prop] = defaults[prop];
		});
		forEach(options, function (value, prop) {
			extended[prop] = options[prop];
		});
		return extended;
	};


	/**
	 * Create a fragment DOM elements
	 * @private
	 */
	var createFragElem = function(htmlStr) {
		var frag = document.createDocumentFragment(),
			temp = document.createElement('div');
		temp.innerHTML = htmlStr;
		while (temp.firstChild) {
			frag.appendChild(temp.firstChild);
		}
		return frag;
	};


	/**
	 * Generate new ID
	 * @private
	 */
	var generateId = function(params) {
		var newId = btoa(encodeURIComponent(params));
		return newId.replace(/=/g, "");
	};


	/**
	 * Check if is a color
	 * @private
	 */
	var isColor = function(color){
		if( color.substring(0,1) == '#' || color.substring(0,3) == 'rgb' || color.substring(0,3) == 'hsl' ){
			return true;
		} else {
			return false;
		}
	};


	/**
	 * Check if is a Base64 string
	 * @private
	 */
	var isBase64 = function(str) {
	    try {
	        return btoa(atob(str)) == str;
	    } catch (err) {
	        return false;
	    }
	};


	/**
	 * Drag method of toasts
	 * @private
	 */
	var drag = function() {
	    
	    return {
	        move: function(toast, instance, settings, xpos) {

	        	var opacity,
	        		opacityRange = 0.3,
	        		distance = 180;
	            
	            if(xpos !== 0){
	            	
	            	toast.classList.add(PLUGIN_NAME+'-dragged');

	            	toast.style.transform = 'translateX('+xpos + 'px)';

		            if(xpos > 0){
		            	opacity = (distance-xpos) / distance;
		            	if(opacity < opacityRange){
							instance.hide(extend(settings, { transitionOut: 'fadeOutRight', transitionOutMobile: 'fadeOutRight' }), toast, 'drag');
						}
		            } else {
		            	opacity = (distance+xpos) / distance;
		            	if(opacity < opacityRange){
							instance.hide(extend(settings, { transitionOut: 'fadeOutLeft', transitionOutMobile: 'fadeOutLeft' }), toast, 'drag');
						}
		            }
					toast.style.opacity = opacity;
			
					if(opacity < opacityRange){

						if(ISCHROME || ISFIREFOX)
							toast.style.left = xpos+'px';

						toast.parentNode.style.opacity = opacityRange;

		                this.stopMoving(toast, null);
					}
	            }

				
	        },
	        startMoving: function(toast, instance, settings, e) {

	            e = e || window.event;
	            var posX = ((ACCEPTSTOUCH) ? e.touches[0].clientX : e.clientX),
	                toastLeft = toast.style.transform.replace('px)', '');
	                toastLeft = toastLeft.replace('translateX(', '');
	            var offsetX = posX - toastLeft;

				if(settings.transitionIn){
					toast.classList.remove(settings.transitionIn);
				}
				if(settings.transitionInMobile){
					toast.classList.remove(settings.transitionInMobile);
				}
				toast.style.transition = '';

	            if(ACCEPTSTOUCH) {
	                document.ontouchmove = function(e) {
	                    e.preventDefault();
	                    e = e || window.event;
	                    var posX = e.touches[0].clientX,
	                        finalX = posX - offsetX;
                        drag.move(toast, instance, settings, finalX);
	                };
	            } else {
	                document.onmousemove = function(e) {
	                    e.preventDefault();
	                    e = e || window.event;
	                    var posX = e.clientX,
	                        finalX = posX - offsetX;
                        drag.move(toast, instance, settings, finalX);
	                };
	            }

	        },
	        stopMoving: function(toast, e) {

	            if(ACCEPTSTOUCH) {
	                document.ontouchmove = function() {};
	            } else {
	            	document.onmousemove = function() {};
	            }

				toast.style.opacity = '';
				toast.style.transform = '';

	            if(toast.classList.contains(PLUGIN_NAME+'-dragged')){
	            	
	            	toast.classList.remove(PLUGIN_NAME+'-dragged');

					toast.style.transition = 'transform 0.4s ease, opacity 0.4s ease';
					setTimeout(function() {
						toast.style.transition = '';
					}, 400);
	            }

	        }
	    };

	}();





	$iziToast.setSetting = function (ref, option, value) {

		$iziToast.children[ref][option] = value;

	};


	$iziToast.getSetting = function (ref, option) {

		return $iziToast.children[ref][option];

	};


	/**
	 * Destroy the current initialization.
	 * @public
	 */
	$iziToast.destroy = function () {

		forEach(document.querySelectorAll('.'+PLUGIN_NAME+'-overlay'), function(element, index) {
			element.remove();
		});

		forEach(document.querySelectorAll('.'+PLUGIN_NAME+'-wrapper'), function(element, index) {
			element.remove();
		});

		forEach(document.querySelectorAll('.'+PLUGIN_NAME), function(element, index) {
			element.remove();
		});

		this.children = {};

		// Remove event listeners
		document.removeEventListener(PLUGIN_NAME+'-opened', {}, false);
		document.removeEventListener(PLUGIN_NAME+'-opening', {}, false);
		document.removeEventListener(PLUGIN_NAME+'-closing', {}, false);
		document.removeEventListener(PLUGIN_NAME+'-closed', {}, false);
		document.removeEventListener('keyup', {}, false);

		// Reset variables
		CONFIG = {};
	};

	/**
	 * Initialize Plugin
	 * @public
	 * @param {Object} options User settings
	 */
	$iziToast.settings = function (options) {

		// Destroy any existing initializations
		$iziToast.destroy();

		CONFIG = options;
		defaults = extend(defaults, options || {});
	};


	/**
	 * Building themes functions.
	 * @public
	 * @param {Object} options User settings
	 */
	forEach(THEMES, function (theme, name) {

		$iziToast[name] = function (options) {

			var settings = extend(CONFIG, options || {});
			settings = extend(theme, settings || {});

			this.show(settings);
		};

	});


	/**
	 * Do the calculation to move the progress bar
	 * @private
	 */
	$iziToast.progress = function (options, $toast, callback) {


		var that = this,
			ref = $toast.getAttribute('data-iziToast-ref'),
			settings = extend(this.children[ref], options || {}),
			$elem = $toast.querySelector('.'+PLUGIN_NAME+'-progressbar div');

	    return {
	        start: function() {

	        	if(typeof settings.time.REMAINING == 'undefined'){

	        		$toast.classList.remove(PLUGIN_NAME+'-reseted');

		        	if($elem !== null){
						$elem.style.transition = 'width '+ settings.timeout +'ms '+settings.progressBarEasing;
						$elem.style.width = '0%';
					}

		        	settings.time.START = new Date().getTime();
		        	settings.time.END = settings.time.START + settings.timeout;
					settings.time.TIMER = setTimeout(function() {

						clearTimeout(settings.time.TIMER);

						if(!$toast.classList.contains(PLUGIN_NAME+'-closing')){

							that.hide(settings, $toast, 'timeout');

							if(typeof callback === 'function'){
								callback.apply(that);
							}
						}

					}, settings.timeout);			
		        	that.setSetting(ref, 'time', settings.time);
	        	}
	        },
	        pause: function() {

	        	if(typeof settings.time.START !== 'undefined' && !$toast.classList.contains(PLUGIN_NAME+'-paused') && !$toast.classList.contains(PLUGIN_NAME+'-reseted')){

        			$toast.classList.add(PLUGIN_NAME+'-paused');

					settings.time.REMAINING = settings.time.END - new Date().getTime();

					clearTimeout(settings.time.TIMER);

					that.setSetting(ref, 'time', settings.time);

					if($elem !== null){
						var computedStyle = window.getComputedStyle($elem),
							propertyWidth = computedStyle.getPropertyValue('width');

						$elem.style.transition = 'none';
						$elem.style.width = propertyWidth;					
					}

					if(typeof callback === 'function'){
						setTimeout(function() {
							callback.apply(that);						
						}, 10);
					}
        		}
	        },
	        resume: function() {

				if(typeof settings.time.REMAINING !== 'undefined'){

					$toast.classList.remove(PLUGIN_NAME+'-paused');

		        	if($elem !== null){
						$elem.style.transition = 'width '+ settings.time.REMAINING +'ms '+settings.progressBarEasing;
						$elem.style.width = '0%';
					}

		        	settings.time.END = new Date().getTime() + settings.time.REMAINING;
					settings.time.TIMER = setTimeout(function() {

						clearTimeout(settings.time.TIMER);

						if(!$toast.classList.contains(PLUGIN_NAME+'-closing')){

							that.hide(settings, $toast, 'timeout');

							if(typeof callback === 'function'){
								callback.apply(that);
							}
						}


					}, settings.time.REMAINING);

					that.setSetting(ref, 'time', settings.time);
				} else {
					this.start();
				}
	        },
	        reset: function(){

				clearTimeout(settings.time.TIMER);

				delete settings.time.REMAINING;

				that.setSetting(ref, 'time', settings.time);

				$toast.classList.add(PLUGIN_NAME+'-reseted');

				$toast.classList.remove(PLUGIN_NAME+'-paused');

				if($elem !== null){
					$elem.style.transition = 'none';
					$elem.style.width = '100%';
				}

				if(typeof callback === 'function'){
					setTimeout(function() {
						callback.apply(that);						
					}, 10);
				}
	        }
	    };

	};


	/**
	 * Close the specific Toast
	 * @public
	 * @param {Object} options User settings
	 */
	$iziToast.hide = function (options, $toast, closedBy) {

		if(typeof $toast != 'object'){
			$toast = document.querySelector($toast);
		}		

		var that = this,
			settings = extend(this.children[$toast.getAttribute('data-iziToast-ref')], options || {});
			settings.closedBy = closedBy || null;

		delete settings.time.REMAINING;

		$toast.classList.add(PLUGIN_NAME+'-closing');

		// Overlay
		(function(){

			var $overlay = document.querySelector('.'+PLUGIN_NAME+'-overlay');
			if($overlay !== null){
				var refs = $overlay.getAttribute('data-iziToast-ref');		
					refs = refs.split(',');
				var index = refs.indexOf(String(settings.ref));

				if(index !== -1){
					refs.splice(index, 1);			
				}
				$overlay.setAttribute('data-iziToast-ref', refs.join());

				if(refs.length === 0){
					$overlay.classList.remove('fadeIn');
					$overlay.classList.add('fadeOut');
					setTimeout(function() {
						$overlay.remove();
					}, 700);
				}
			}

		})();

		if(settings.transitionIn){
			$toast.classList.remove(settings.transitionIn);
		} 

		if(settings.transitionInMobile){
			$toast.classList.remove(settings.transitionInMobile);
		}

		if(ISMOBILE || window.innerWidth <= MOBILEWIDTH){
			if(settings.transitionOutMobile)
				$toast.classList.add(settings.transitionOutMobile);
		} else {
			if(settings.transitionOut)
				$toast.classList.add(settings.transitionOut);
		}
		var H = $toast.parentNode.offsetHeight;
				$toast.parentNode.style.height = H+'px';
				$toast.style.pointerEvents = 'none';
		
		if(!ISMOBILE || window.innerWidth > MOBILEWIDTH){
			$toast.parentNode.style.transitionDelay = '0.2s';
		}

		try {
			var event = new CustomEvent(PLUGIN_NAME+'-closing', {detail: settings, bubbles: true, cancelable: true});
			document.dispatchEvent(event);
		} catch(ex){
			console.warn(ex);
		}

		setTimeout(function() {
			
			$toast.parentNode.style.height = '0px';
			$toast.parentNode.style.overflow = '';

			setTimeout(function(){
				
				delete that.children[settings.ref];

				$toast.parentNode.remove();

				try {
					var event = new CustomEvent(PLUGIN_NAME+'-closed', {detail: settings, bubbles: true, cancelable: true});
					document.dispatchEvent(event);
				} catch(ex){
					console.warn(ex);
				}

				if(typeof settings.onClosed !== 'undefined'){
					settings.onClosed.apply(null, [settings, $toast, closedBy]);
				}

			}, 1000);
		}, 200);


		if(typeof settings.onClosing !== 'undefined'){
			settings.onClosing.apply(null, [settings, $toast, closedBy]);
		}
	};

	/**
	 * Create and show the Toast
	 * @public
	 * @param {Object} options User settings
	 */
	$iziToast.show = function (options) {

		var that = this;

		// Merge user options with defaults
		var settings = extend(CONFIG, options || {});
			settings = extend(defaults, settings);
			settings.time = {};

		if(settings.id === null){
			settings.id = generateId(settings.title+settings.message+settings.color);
		}

		if(settings.displayMode === 1 || settings.displayMode == 'once'){
			try {
				if(document.querySelectorAll('.'+PLUGIN_NAME+'#'+settings.id).length > 0){
					return false;
				}
			} catch (exc) {
				console.warn('['+PLUGIN_NAME+'] Could not find an element with this selector: '+'#'+settings.id+'. Try to set an valid id.');
			}
		}

		if(settings.displayMode === 2 || settings.displayMode == 'replace'){
			try {
				forEach(document.querySelectorAll('.'+PLUGIN_NAME+'#'+settings.id), function(element, index) {
					that.hide(settings, element, 'replaced');
				});
			} catch (exc) {
				console.warn('['+PLUGIN_NAME+'] Could not find an element with this selector: '+'#'+settings.id+'. Try to set an valid id.');
			}
		}

		settings.ref = new Date().getTime() + Math.floor((Math.random() * 10000000) + 1);

		$iziToast.children[settings.ref] = settings;

		var $DOM = {
			body: document.querySelector('body'),
			overlay: document.createElement('div'),
			toast: document.createElement('div'),
			toastBody: document.createElement('div'),
			toastTexts: document.createElement('div'),
			toastCapsule: document.createElement('div'),
			cover: document.createElement('div'),
			buttons: document.createElement('div'),
			inputs: document.createElement('div'),
			icon: !settings.iconUrl ? document.createElement('i') : document.createElement('img'),
			wrapper: null
		};

		$DOM.toast.setAttribute('data-iziToast-ref', settings.ref);
		$DOM.toast.appendChild($DOM.toastBody);
		$DOM.toastCapsule.appendChild($DOM.toast);

		// CSS Settings
		(function(){

			$DOM.toast.classList.add(PLUGIN_NAME);
			$DOM.toast.classList.add(PLUGIN_NAME+'-opening');
			$DOM.toastCapsule.classList.add(PLUGIN_NAME+'-capsule');
			$DOM.toastBody.classList.add(PLUGIN_NAME + '-body');
			$DOM.toastTexts.classList.add(PLUGIN_NAME + '-texts');

			if(ISMOBILE || window.innerWidth <= MOBILEWIDTH){
				if(settings.transitionInMobile)
					$DOM.toast.classList.add(settings.transitionInMobile);
			} else {
				if(settings.transitionIn)
					$DOM.toast.classList.add(settings.transitionIn);
			}

			if(settings.class){
				var classes = settings.class.split(' ');
				forEach(classes, function (value, index) {
					$DOM.toast.classList.add(value);
				});
			}

			if(settings.id){ $DOM.toast.id = settings.id; }

			if(settings.rtl){
				$DOM.toast.classList.add(PLUGIN_NAME + '-rtl');
				$DOM.toast.setAttribute('dir', 'rtl');
			}

			if(settings.layout > 1){ $DOM.toast.classList.add(PLUGIN_NAME+'-layout'+settings.layout); }

			if(settings.balloon){ $DOM.toast.classList.add(PLUGIN_NAME+'-balloon'); }

			if(settings.maxWidth){
				if( !isNaN(settings.maxWidth) ){
					$DOM.toast.style.maxWidth = settings.maxWidth+'px';
				} else {
					$DOM.toast.style.maxWidth = settings.maxWidth;
				}
			}

			if(settings.theme !== '' || settings.theme !== 'light') {

				$DOM.toast.classList.add(PLUGIN_NAME+'-theme-'+settings.theme);
			}

			if(settings.color) { //#, rgb, rgba, hsl
				
				if( isColor(settings.color) ){
					$DOM.toast.style.background = settings.color;
				} else {
					$DOM.toast.classList.add(PLUGIN_NAME+'-color-'+settings.color);
				}
			}

			if(settings.backgroundColor) {
				$DOM.toast.style.background = settings.backgroundColor;
				if(settings.balloon){
					$DOM.toast.style.borderColor = settings.backgroundColor;				
				}
			}
		})();

		// Cover image
		(function(){
			if(settings.image) {
				$DOM.cover.classList.add(PLUGIN_NAME + '-cover');
				$DOM.cover.style.width = settings.imageWidth + 'px';

				if(isBase64(settings.image.replace(/ /g,''))){
					$DOM.cover.style.backgroundImage = 'url(data:image/png;base64,' + settings.image.replace(/ /g,'') + ')';
				} else {
					$DOM.cover.style.backgroundImage = 'url(' + settings.image + ')';
				}

				if(settings.rtl){
					$DOM.toastBody.style.marginRight = (settings.imageWidth + 10) + 'px';
				} else {
					$DOM.toastBody.style.marginLeft = (settings.imageWidth + 10) + 'px';				
				}
				$DOM.toast.appendChild($DOM.cover);
			}
		})();

		// Button close
		(function(){
			if(settings.close){
				
				$DOM.buttonClose = document.createElement('button');
				$DOM.buttonClose.type = 'button';
				$DOM.buttonClose.classList.add(PLUGIN_NAME + '-close');
				$DOM.buttonClose.addEventListener('click', function (e) {
					var button = e.target;
					that.hide(settings, $DOM.toast, 'button');
				});
				$DOM.toast.appendChild($DOM.buttonClose);
			} else {
				if(settings.rtl){
					$DOM.toast.style.paddingLeft = '18px';
				} else {
					$DOM.toast.style.paddingRight = '18px';
				}
			}
		})();

		// Progress Bar & Timeout
		(function(){

			if(settings.progressBar){
				$DOM.progressBar = document.createElement('div');
				$DOM.progressBarDiv = document.createElement('div');
				$DOM.progressBar.classList.add(PLUGIN_NAME + '-progressbar');
				$DOM.progressBarDiv.style.background = settings.progressBarColor;
				$DOM.progressBar.appendChild($DOM.progressBarDiv);
				$DOM.toast.appendChild($DOM.progressBar);
			}

			if(settings.timeout) {

				if(settings.pauseOnHover && !settings.resetOnHover){
					
					$DOM.toast.addEventListener('mouseenter', function (e) {
						that.progress(settings, $DOM.toast).pause();
					});
					$DOM.toast.addEventListener('mouseleave', function (e) {
						that.progress(settings, $DOM.toast).resume();
					});
				}

				if(settings.resetOnHover){

					$DOM.toast.addEventListener('mouseenter', function (e) {
						that.progress(settings, $DOM.toast).reset();
					});
					$DOM.toast.addEventListener('mouseleave', function (e) {
						that.progress(settings, $DOM.toast).start();
					});
				}
			}
		})();

		// Icon
		(function(){

			if(settings.iconUrl) {

				$DOM.icon.setAttribute('class', PLUGIN_NAME + '-icon');
				$DOM.icon.setAttribute('src', settings.iconUrl);

			} else if(settings.icon) {
				$DOM.icon.setAttribute('class', PLUGIN_NAME + '-icon ' + settings.icon);
				
				if(settings.iconText){
					$DOM.icon.appendChild(document.createTextNode(settings.iconText));
				}
				
				if(settings.iconColor){
					$DOM.icon.style.color = settings.iconColor;
				}				
			}

			if(settings.icon || settings.iconUrl) {

				if(settings.rtl){
					$DOM.toastBody.style.paddingRight = '33px';
				} else {
					$DOM.toastBody.style.paddingLeft = '33px';				
				}

				$DOM.toastBody.appendChild($DOM.icon);
			}

		})();

		// Title & Message
		(function(){
			if(settings.title.length > 0) {

				$DOM.strong = document.createElement('strong');
				$DOM.strong.classList.add(PLUGIN_NAME + '-title');
				$DOM.strong.appendChild(createFragElem(settings.title));
				$DOM.toastTexts.appendChild($DOM.strong);

				if(settings.titleColor) {
					$DOM.strong.style.color = settings.titleColor;
				}
				if(settings.titleSize) {
					if( !isNaN(settings.titleSize) ){
						$DOM.strong.style.fontSize = settings.titleSize+'px';
					} else {
						$DOM.strong.style.fontSize = settings.titleSize;
					}
				}
				if(settings.titleLineHeight) {
					if( !isNaN(settings.titleSize) ){
						$DOM.strong.style.lineHeight = settings.titleLineHeight+'px';
					} else {
						$DOM.strong.style.lineHeight = settings.titleLineHeight;
					}
				}
			}

			if(settings.message.length > 0) {

				$DOM.p = document.createElement('p');
				$DOM.p.classList.add(PLUGIN_NAME + '-message');
				$DOM.p.appendChild(createFragElem(settings.message));
				$DOM.toastTexts.appendChild($DOM.p);

				if(settings.messageColor) {
					$DOM.p.style.color = settings.messageColor;
				}
				if(settings.messageSize) {
					if( !isNaN(settings.titleSize) ){
						$DOM.p.style.fontSize = settings.messageSize+'px';
					} else {
						$DOM.p.style.fontSize = settings.messageSize;
					}
				}
				if(settings.messageLineHeight) {
					
					if( !isNaN(settings.titleSize) ){
						$DOM.p.style.lineHeight = settings.messageLineHeight+'px';
					} else {
						$DOM.p.style.lineHeight = settings.messageLineHeight;
					}
				}
			}

			if(settings.title.length > 0 && settings.message.length > 0) {
				if(settings.rtl){
					$DOM.strong.style.marginLeft = '10px';
				} else if(settings.layout !== 2 && !settings.rtl) {
					$DOM.strong.style.marginRight = '10px';	
				}
			}
		})();

		$DOM.toastBody.appendChild($DOM.toastTexts);

		// Inputs
		var $inputs;
		(function(){
			if(settings.inputs.length > 0) {

				$DOM.inputs.classList.add(PLUGIN_NAME + '-inputs');

				forEach(settings.inputs, function (value, index) {
					$DOM.inputs.appendChild(createFragElem(value[0]));

					$inputs = $DOM.inputs.childNodes;

					$inputs[index].classList.add(PLUGIN_NAME + '-inputs-child');

					if(value[3]){
						setTimeout(function() {
							$inputs[index].focus();
						}, 300);
					}

					$inputs[index].addEventListener(value[1], function (e) {
						var ts = value[2];
						return ts(that, $DOM.toast, this, e);
					});
				});
				$DOM.toastBody.appendChild($DOM.inputs);
			}
		})();

		// Buttons
		(function(){
			if(settings.buttons.length > 0) {

				$DOM.buttons.classList.add(PLUGIN_NAME + '-buttons');

				forEach(settings.buttons, function (value, index) {
					$DOM.buttons.appendChild(createFragElem(value[0]));

					var $btns = $DOM.buttons.childNodes;

					$btns[index].classList.add(PLUGIN_NAME + '-buttons-child');

					if(value[2]){
						setTimeout(function() {
							$btns[index].focus();
						}, 300);
					}

					$btns[index].addEventListener('click', function (e) {
						e.preventDefault();
						var ts = value[1];
						return ts(that, $DOM.toast, this, e, $inputs);
					});
				});
			}
			$DOM.toastBody.appendChild($DOM.buttons);
		})();

		if(settings.message.length > 0 && (settings.inputs.length > 0 || settings.buttons.length > 0)) {
			$DOM.p.style.marginBottom = '0';
		}

		if(settings.inputs.length > 0 || settings.buttons.length > 0){
			if(settings.rtl){
				$DOM.toastTexts.style.marginLeft = '10px';
			} else {
				$DOM.toastTexts.style.marginRight = '10px';
			}
			if(settings.inputs.length > 0 && settings.buttons.length > 0){
				if(settings.rtl){
					$DOM.inputs.style.marginLeft = '8px';
				} else {
					$DOM.inputs.style.marginRight = '8px';
				}
			}
		}

		// Wrap
		(function(){
			$DOM.toastCapsule.style.visibility = 'hidden';
			setTimeout(function() {
				var H = $DOM.toast.offsetHeight;
				var style = $DOM.toast.currentStyle || window.getComputedStyle($DOM.toast);
				var marginTop = style.marginTop;
					marginTop = marginTop.split('px');
					marginTop = parseInt(marginTop[0]);
				var marginBottom = style.marginBottom;
					marginBottom = marginBottom.split('px');
					marginBottom = parseInt(marginBottom[0]);

				$DOM.toastCapsule.style.visibility = '';
				$DOM.toastCapsule.style.height = (H+marginBottom+marginTop)+'px';

				setTimeout(function() {
					$DOM.toastCapsule.style.height = 'auto';
					if(settings.target){
						$DOM.toastCapsule.style.overflow = 'visible';
					}
				}, 500);

				if(settings.timeout) {
					that.progress(settings, $DOM.toast).start();
				}
			}, 100);
		})();

		// Target
		(function(){
			var position = settings.position;

			if(settings.target){

				$DOM.wrapper = document.querySelector(settings.target);
				$DOM.wrapper.classList.add(PLUGIN_NAME + '-target');

				if(settings.targetFirst) {
					$DOM.wrapper.insertBefore($DOM.toastCapsule, $DOM.wrapper.firstChild);
				} else {
					$DOM.wrapper.appendChild($DOM.toastCapsule);
				}

			} else {

				if( POSITIONS.indexOf(settings.position) == -1 ){
					console.warn('['+PLUGIN_NAME+'] Incorrect position.\nIt can be › ' + POSITIONS);
					return;
				}

				if(ISMOBILE || window.innerWidth <= MOBILEWIDTH){
					if(settings.position == 'bottomLeft' || settings.position == 'bottomRight' || settings.position == 'bottomCenter'){
						position = PLUGIN_NAME+'-wrapper-bottomCenter';
					}
					else if(settings.position == 'topLeft' || settings.position == 'topRight' || settings.position == 'topCenter'){
						position = PLUGIN_NAME+'-wrapper-topCenter';
					}
					else {
						position = PLUGIN_NAME+'-wrapper-center';
					}
				} else {
					position = PLUGIN_NAME+'-wrapper-'+position;
				}
				$DOM.wrapper = document.querySelector('.' + PLUGIN_NAME + '-wrapper.'+position);

				if(!$DOM.wrapper) {
					$DOM.wrapper = document.createElement('div');
					$DOM.wrapper.classList.add(PLUGIN_NAME + '-wrapper');
					$DOM.wrapper.classList.add(position);
					document.body.appendChild($DOM.wrapper);
				}
				if(settings.position == 'topLeft' || settings.position == 'topCenter' || settings.position == 'topRight'){
					$DOM.wrapper.insertBefore($DOM.toastCapsule, $DOM.wrapper.firstChild);
				} else {
					$DOM.wrapper.appendChild($DOM.toastCapsule);
				}
			}

			if(!isNaN(settings.zindex)) {
				$DOM.wrapper.style.zIndex = settings.zindex;
			} else {
				console.warn('['+PLUGIN_NAME+'] Invalid zIndex.');
			}
		})();

		// Overlay
		(function(){

			if(settings.overlay) {

				if( document.querySelector('.'+PLUGIN_NAME+'-overlay.fadeIn') !== null ){

					$DOM.overlay = document.querySelector('.'+PLUGIN_NAME+'-overlay');
					$DOM.overlay.setAttribute('data-iziToast-ref', $DOM.overlay.getAttribute('data-iziToast-ref') + ',' + settings.ref);

					if(!isNaN(settings.zindex) && settings.zindex !== null) {
						$DOM.overlay.style.zIndex = settings.zindex-1;
					}

				} else {

					$DOM.overlay.classList.add(PLUGIN_NAME+'-overlay');
					$DOM.overlay.classList.add('fadeIn');
					$DOM.overlay.style.background = settings.overlayColor;
					$DOM.overlay.setAttribute('data-iziToast-ref', settings.ref);
					if(!isNaN(settings.zindex) && settings.zindex !== null) {
						$DOM.overlay.style.zIndex = settings.zindex-1;
					}
					document.querySelector('body').appendChild($DOM.overlay);
				}

				if(settings.overlayClose) {

					$DOM.overlay.removeEventListener('click', {});
					$DOM.overlay.addEventListener('click', function (e) {
						that.hide(settings, $DOM.toast, 'overlay');
					});
				} else {
					$DOM.overlay.removeEventListener('click', {});
				}
			}			
		})();

		// Inside animations
		(function(){
			if(settings.animateInside){
				$DOM.toast.classList.add(PLUGIN_NAME+'-animateInside');
			
				var animationTimes = [200, 100, 300];
				if(settings.transitionIn == 'bounceInLeft' || settings.transitionIn == 'bounceInRight'){
					animationTimes = [400, 200, 400];
				}

				if(settings.title.length > 0) {
					setTimeout(function(){
						$DOM.strong.classList.add('slideIn');
					}, animationTimes[0]);
				}

				if(settings.message.length > 0) {
					setTimeout(function(){
						$DOM.p.classList.add('slideIn');
					}, animationTimes[1]);
				}

				if(settings.icon || settings.iconUrl) {
					setTimeout(function(){
						$DOM.icon.classList.add('revealIn');
					}, animationTimes[2]);
				}

				var counter = 150;
				if(settings.buttons.length > 0 && $DOM.buttons) {

					setTimeout(function(){

						forEach($DOM.buttons.childNodes, function(element, index) {

							setTimeout(function(){
								element.classList.add('revealIn');
							}, counter);
							counter = counter + 150;
						});

					}, settings.inputs.length > 0 ? 150 : 0);
				}

				if(settings.inputs.length > 0 && $DOM.inputs) {
					counter = 150;
					forEach($DOM.inputs.childNodes, function(element, index) {

						setTimeout(function(){
							element.classList.add('revealIn');
						}, counter);
						counter = counter + 150;
					});
				}
			}
		})();

		settings.onOpening.apply(null, [settings, $DOM.toast]);

		try {
			var event = new CustomEvent(PLUGIN_NAME + '-opening', {detail: settings, bubbles: true, cancelable: true});
			document.dispatchEvent(event);
		} catch(ex){
			console.warn(ex);
		}

		setTimeout(function() {

			$DOM.toast.classList.remove(PLUGIN_NAME+'-opening');
			$DOM.toast.classList.add(PLUGIN_NAME+'-opened');

			try {
				var event = new CustomEvent(PLUGIN_NAME + '-opened', {detail: settings, bubbles: true, cancelable: true});
				document.dispatchEvent(event);
			} catch(ex){
				console.warn(ex);
			}

			settings.onOpened.apply(null, [settings, $DOM.toast]);
		}, 1000);

		if(settings.drag){

			if(ACCEPTSTOUCH) {

			    $DOM.toast.addEventListener('touchstart', function(e) {
			        drag.startMoving(this, that, settings, e);
			    }, false);

			    $DOM.toast.addEventListener('touchend', function(e) {
			        drag.stopMoving(this, e);
			    }, false);
			} else {

			    $DOM.toast.addEventListener('mousedown', function(e) {
			    	e.preventDefault();
			        drag.startMoving(this, that, settings, e);
			    }, false);

			    $DOM.toast.addEventListener('mouseup', function(e) {
			    	e.preventDefault();
			        drag.stopMoving(this, e);
			    }, false);
			}
		}

		if(settings.closeOnEscape) {

			document.addEventListener('keyup', function (evt) {
				evt = evt || window.event;
				if(evt.keyCode == 27) {
				    that.hide(settings, $DOM.toast, 'esc');
				}
			});
		}

		if(settings.closeOnClick) {
			$DOM.toast.addEventListener('click', function (evt) {
				that.hide(settings, $DOM.toast, 'toast');
			});
		}

		that.toast = $DOM.toast;		
	};
	

	return $iziToast;
});

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
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/global */
/******/ 	(() => {
/******/ 		__webpack_require__.g = (function() {
/******/ 			if (typeof globalThis === 'object') return globalThis;
/******/ 			try {
/******/ 				return this || new Function('return this')();
/******/ 			} catch (e) {
/******/ 				if (typeof window === 'object') return window;
/******/ 			}
/******/ 		})();
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!*******************************************!*\
  !*** ./resources/views/layouts/js/app.js ***!
  \*******************************************/
/*
 *  Document   : app.js
 *  Author     : pixelcave
 *  Description: Custom scripts and plugin initializations (available to all pages)
 *
 *  Feel free to remove the plugin initilizations from uiInit() if you would like to
 *  use them only in specific pages. Also, if you remove a js plugin you won't use, make
 *  sure to remove its initialization from uiInit().
 */

var _require = __webpack_require__(/*! izitoast */ "./node_modules/izitoast/dist/js/iziToast.js"),
  iziToast = _require["default"];
var App = function () {
  /* Helper variables - set in uiInit() */
  var page, pageContent, header, footer, sidebar, sScroll, sidebarAlt, sScrollAlt;

  /* Initialization UI Code */
  var uiInit = function uiInit() {
    // Set variables - Cache some often used Jquery objects in variables */
    page = $('#page-container');
    pageContent = $('#page-content');
    header = $('header');
    footer = $('#page-content + footer');
    sidebar = $('#sidebar');
    sScroll = $('#sidebar-scroll');
    sidebarAlt = $('#sidebar-alt');
    sScrollAlt = $('#sidebar-alt-scroll');

    // Initialize sidebars functionality
    handleSidebar('init');

    // Sidebar navigation functionality
    handleNav();

    // Interactive blocks functionality
    interactiveBlocks();

    // Scroll to top functionality
    scrollToTop();

    // Template Options, change features
    templateOptions();

    // Resize #page-content to fill empty space if exists (also add it to resize and orientationchange events)
    resizePageContent();
    $(window).resize(function () {
      resizePageContent();
    });
    $(window).bind('orientationchange', resizePageContent);

    // Add the correct copyright year at the footer
    var yearCopy = $('#year-copy'),
      d = new Date();
    if (d.getFullYear() === 2014) {
      yearCopy.html('2014');
    } else {
      yearCopy.html('2014-' + d.getFullYear().toString().substr(2, 2));
    }

    // Initialize chat demo functionality (in sidebar)
    chatUi();

    // Initialize tabs
    $('[data-toggle="tabs"] a, .enable-tabs a').click(function (e) {
      e.preventDefault();
      $(this).tab('show');
    });

    // Initialize Tooltips
    $('[data-toggle="tooltip"], .enable-tooltip').tooltip({
      container: 'body',
      animation: false
    });

    // Initialize Popovers
    $('[data-toggle="popover"], .enable-popover').popover({
      container: 'body',
      animation: true
    });

    // Initialize single image lightbox
    $('[data-toggle="lightbox-image"]').magnificPopup({
      type: 'image',
      image: {
        titleSrc: 'title'
      }
    });

    // Initialize image gallery lightbox
    $('[data-toggle="lightbox-gallery"]').each(function () {
      $(this).magnificPopup({
        delegate: 'a.gallery-link',
        type: 'image',
        gallery: {
          enabled: true,
          navigateByImgClick: true,
          arrowMarkup: '<button type="button" class="mfp-arrow mfp-arrow-%dir%" title="%title%"></button>',
          tPrev: 'Previous',
          tNext: 'Next',
          tCounter: '<span class="mfp-counter">%curr% of %total%</span>'
        },
        image: {
          titleSrc: 'title'
        }
      });
    });

    // Initialize Typeahead - Example with countries
    var exampleTypeheadData = ["Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "British Virgin Islands", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "CΓ΄te d'Ivoire", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Cook Islands", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Democratic Republic of the Congo", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Faeroe Islands", "Falkland Islands", "Fiji", "Finland", "Former Yugoslav Republic of Macedonia", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard Island and McDonald Islands", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "North Korea", "Northern Marianas", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn Islands", "Poland", "Portugal", "Puerto Rico", "Qatar", "RΓ©union", "Romania", "Russia", "Rwanda", "SΓ£o TomΓ© and PrΓ­ncipe", "Saint Helena", "Saint Kitts and Nevis", "Saint Lucia", "Saint Pierre and Miquelon", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "South Korea", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard and Jan Mayen", "Swaziland", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "The Bahamas", "The Gambia", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "US Virgin Islands", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Wallis and Futuna", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe"];
    $('.input-typeahead').typeahead({
      source: exampleTypeheadData
    });

    // Initialize Chosen
    $('.select-chosen').chosen({
      width: "100%"
    });

    // Initialize Select2
    // $('.select-select2').select2();

    // Initialize Bootstrap Colorpicker
    $('.input-colorpicker').colorpicker({
      format: 'hex'
    });
    $('.input-colorpicker-rgba').colorpicker({
      format: 'rgba'
    });

    // Initialize Slider for Bootstrap
    $('.input-slider').slider();

    // Initialize Tags Input
    $('.input-tags').tagsInput({
      width: 'auto',
      height: 'auto'
    });

    // Initialize Datepicker
    $('.input-datepicker, .input-daterange').datepicker({
      weekStart: 1
    });
    $('.input-datepicker-close').datepicker({
      weekStart: 1
    }).on('changeDate', function (e) {
      $(this).datepicker('hide');
    });

    // Initialize Timepicker
    $('.input-timepicker').timepicker({
      minuteStep: 1,
      showSeconds: true,
      showMeridian: true
    });
    $('.input-timepicker24').timepicker({
      minuteStep: 1,
      showSeconds: true,
      showMeridian: false
    });

    // Easy Pie Chart
    $('.pie-chart').easyPieChart({
      barColor: $(this).data('bar-color') ? $(this).data('bar-color') : '#777777',
      trackColor: $(this).data('track-color') ? $(this).data('track-color') : '#eeeeee',
      lineWidth: $(this).data('line-width') ? $(this).data('line-width') : 3,
      size: $(this).data('size') ? $(this).data('size') : '80',
      animate: 800,
      scaleColor: false
    });

    // Initialize Placeholder
    $('input, textarea').placeholder();
  };

  /* Page Loading functionality */
  var pageLoading = function pageLoading() {
    var pageWrapper = $('#page-wrapper');
    if (pageWrapper.hasClass('page-loading')) {
      pageWrapper.removeClass('page-loading');
    }
  };

  /* Gets window width cross browser */
  var getWindowWidth = function getWindowWidth() {
    return window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
  };

  /* Sidebar Navigation functionality */
  var handleNav = function handleNav() {
    // Animation Speed, change the values for different results
    var upSpeed = 250;
    var downSpeed = 250;

    // Get all vital links
    var menuLinks = $('.sidebar-nav-menu');
    var submenuLinks = $('.sidebar-nav-submenu');

    // Primary Accordion functionality
    menuLinks.click(function () {
      var link = $(this);
      if (page.hasClass('sidebar-mini') && page.hasClass('sidebar-visible-lg-mini') && getWindowWidth() > 991) {
        if (link.hasClass('open')) {
          link.removeClass('open').next().removeAttr('style');
        } else {
          $('.sidebar-nav-menu.open').removeClass('open').next().removeAttr('style');
          link.addClass('open').next().css('display', 'block');
        }
      } else if (!link.parent().hasClass('active')) {
        if (link.hasClass('open')) {
          link.removeClass('open').next().slideUp(upSpeed, function () {
            handlePageScroll(link, 200, 300);
          });

          // Resize #page-content to fill empty space if exists
          setTimeout(resizePageContent, upSpeed);
        } else {
          $('.sidebar-nav-menu.open').removeClass('open').next().slideUp(upSpeed);
          link.addClass('open').next().slideDown(downSpeed, function () {
            handlePageScroll(link, 150, 600);
          });

          // Resize #page-content to fill empty space if exists
          setTimeout(resizePageContent, upSpeed > downSpeed ? upSpeed : downSpeed);
        }
      }
      link.blur();
      return false;
    });

    // Submenu Accordion functionality
    submenuLinks.click(function () {
      var link = $(this);
      if (link.parent().hasClass('active') !== true) {
        if (link.hasClass('open')) {
          link.removeClass('open').next().slideUp(upSpeed, function () {
            handlePageScroll(link, 200, 300);
          });

          // Resize #page-content to fill empty space if exists
          setTimeout(resizePageContent, upSpeed);
        } else {
          link.closest('ul').find('.sidebar-nav-submenu.open').removeClass('open').next().slideUp(upSpeed);
          link.addClass('open').next().slideDown(downSpeed, function () {
            handlePageScroll(link, 150, 600);
          });

          // Resize #page-content to fill empty space if exists
          setTimeout(resizePageContent, upSpeed > downSpeed ? upSpeed : downSpeed);
        }
      }
      link.blur();
      return false;
    });
  };

  /* Scrolls the page (static layout) or the sidebar scroll element (fixed header/sidebars layout) to a specific position - Used when a submenu opens */
  var handlePageScroll = function handlePageScroll(sElem, sHeightDiff, sSpeed) {
    if (!page.hasClass('disable-menu-autoscroll')) {
      var elemScrollToHeight;

      // If we have a static layout scroll the page
      if (!header.hasClass('navbar-fixed-top') && !header.hasClass('navbar-fixed-bottom')) {
        var elemOffsetTop = sElem.offset().top;
        elemScrollToHeight = elemOffsetTop - sHeightDiff > 0 ? elemOffsetTop - sHeightDiff : 0;
        $('html, body').animate({
          scrollTop: elemScrollToHeight
        }, sSpeed);
      } else {
        // If we have a fixed header/sidebars layout scroll the sidebar scroll element
        var sContainer = sElem.parents('#sidebar-scroll');
        var elemOffsetCon = sElem.offset().top + Math.abs($('div:first', sContainer).offset().top);
        elemScrollToHeight = elemOffsetCon - sHeightDiff > 0 ? elemOffsetCon - sHeightDiff : 0;
        sContainer.animate({
          scrollTop: elemScrollToHeight
        }, sSpeed);
      }
    }
  };

  /* Sidebar Functionality */
  var handleSidebar = function handleSidebar(mode, extra) {
    if (mode === 'init') {
      // Init sidebars scrolling functionality
      handleSidebar('sidebar-scroll');
      handleSidebar('sidebar-alt-scroll');

      // Close the other sidebar if we hover over a partial one
      // In smaller screens (the same applies to resized browsers) two visible sidebars
      // could mess up our main content (not enough space), so we hide the other one :-)
      $('.sidebar-partial #sidebar').mouseenter(function () {
        handleSidebar('close-sidebar-alt');
      });
      $('.sidebar-alt-partial #sidebar-alt').mouseenter(function () {
        handleSidebar('close-sidebar');
      });
    } else {
      var windowW = getWindowWidth();
      if (mode === 'toggle-sidebar') {
        if (windowW > 991) {
          // Toggle main sidebar in large screens (> 991px)
          page.toggleClass('sidebar-visible-lg');
          if (page.hasClass('sidebar-mini')) {
            page.toggleClass('sidebar-visible-lg-mini');
          }
          if (page.hasClass('sidebar-visible-lg')) {
            handleSidebar('close-sidebar-alt');
          }

          // If 'toggle-other' is set, open the alternative sidebar when we close this one
          if (extra === 'toggle-other') {
            if (!page.hasClass('sidebar-visible-lg')) {
              handleSidebar('open-sidebar-alt');
            }
          }
        } else {
          // Toggle main sidebar in small screens (< 992px)
          page.toggleClass('sidebar-visible-xs');
          if (page.hasClass('sidebar-visible-xs')) {
            handleSidebar('close-sidebar-alt');
          }
        }

        // Handle main sidebar scrolling functionality
        handleSidebar('sidebar-scroll');
      } else if (mode === 'toggle-sidebar-alt') {
        if (windowW > 991) {
          // Toggle alternative sidebar in large screens (> 991px)
          page.toggleClass('sidebar-alt-visible-lg');
          if (page.hasClass('sidebar-alt-visible-lg')) {
            handleSidebar('close-sidebar');
          }

          // If 'toggle-other' is set open the main sidebar when we close the alternative
          if (extra === 'toggle-other') {
            if (!page.hasClass('sidebar-alt-visible-lg')) {
              handleSidebar('open-sidebar');
            }
          }
        } else {
          // Toggle alternative sidebar in small screens (< 992px)
          page.toggleClass('sidebar-alt-visible-xs');
          if (page.hasClass('sidebar-alt-visible-xs')) {
            handleSidebar('close-sidebar');
          }
        }
      } else if (mode === 'open-sidebar') {
        if (windowW > 991) {
          // Open main sidebar in large screens (> 991px)
          if (page.hasClass('sidebar-mini')) {
            page.removeClass('sidebar-visible-lg-mini');
          }
          page.addClass('sidebar-visible-lg');
        } else {
          // Open main sidebar in small screens (< 992px)
          page.addClass('sidebar-visible-xs');
        }

        // Close the other sidebar
        handleSidebar('close-sidebar-alt');
      } else if (mode === 'open-sidebar-alt') {
        if (windowW > 991) {
          // Open alternative sidebar in large screens (> 991px)
          page.addClass('sidebar-alt-visible-lg');
        } else {
          // Open alternative sidebar in small screens (< 992px)
          page.addClass('sidebar-alt-visible-xs');
        }

        // Close the other sidebar
        handleSidebar('close-sidebar');
      } else if (mode === 'close-sidebar') {
        if (windowW > 991) {
          // Close main sidebar in large screens (> 991px)
          page.removeClass('sidebar-visible-lg');
          if (page.hasClass('sidebar-mini')) {
            page.addClass('sidebar-visible-lg-mini');
          }
        } else {
          // Close main sidebar in small screens (< 992px)
          page.removeClass('sidebar-visible-xs');
        }
      } else if (mode === 'close-sidebar-alt') {
        if (windowW > 991) {
          // Close alternative sidebar in large screens (> 991px)
          page.removeClass('sidebar-alt-visible-lg');
        } else {
          // Close alternative sidebar in small screens (< 992px)
          page.removeClass('sidebar-alt-visible-xs');
        }
      } else if (mode === 'sidebar-scroll') {
        // Handle main sidebar scrolling
        if (page.hasClass('sidebar-mini') && page.hasClass('sidebar-visible-lg-mini') && windowW > 991) {
          // Destroy main sidebar scrolling when in mini sidebar mode
          if (sScroll.length && sScroll.parent('.slimScrollDiv').length) {
            sScroll.slimScroll({
              destroy: true
            });
            sScroll.attr('style', '');
          }
        } else if (page.hasClass('header-fixed-top') || page.hasClass('header-fixed-bottom')) {
          var sHeight = $(window).height();
          if (sScroll.length && !sScroll.parent('.slimScrollDiv').length) {
            // If scrolling does not exist init it..
            sScroll.slimScroll({
              height: sHeight,
              color: '#fff',
              size: '3px',
              touchScrollStep: 100
            });

            // Handle main sidebar's scrolling functionality on resize or orientation change
            var sScrollTimeout;
            $(window).on('resize orientationchange', function () {
              clearTimeout(sScrollTimeout);
              sScrollTimeout = setTimeout(function () {
                handleSidebar('sidebar-scroll');
              }, 150);
            });
          } else {
            // ..else resize scrolling height
            sScroll.add(sScroll.parent()).css('height', sHeight);
          }
        }
      } else if (mode === 'sidebar-alt-scroll') {
        // Init alternative sidebar scrolling
        if (page.hasClass('header-fixed-top') || page.hasClass('header-fixed-bottom')) {
          var sHeightAlt = $(window).height();
          if (sScrollAlt.length && !sScrollAlt.parent('.slimScrollDiv').length) {
            // If scrolling does not exist init it..
            sScrollAlt.slimScroll({
              height: sHeightAlt,
              color: '#fff',
              size: '3px',
              touchScrollStep: 100
            });

            // Resize alternative sidebar scrolling height on window resize or orientation change
            var sScrollAltTimeout;
            $(window).on('resize orientationchange', function () {
              clearTimeout(sScrollAltTimeout);
              sScrollAltTimeout = setTimeout(function () {
                handleSidebar('sidebar-alt-scroll');
              }, 150);
            });
          } else {
            // ..else resize scrolling height
            sScrollAlt.add(sScrollAlt.parent()).css('height', sHeightAlt);
          }
        }
      }
    }
    return false;
  };

  /* Resize #page-content to fill empty space if exists */
  var resizePageContent = function resizePageContent() {
    var windowH = $(window).height();
    var sidebarH = sidebar.outerHeight();
    var sidebarAltH = sidebarAlt.outerHeight();
    var headerH = header.outerHeight();
    var footerH = footer.outerHeight();

    // If we have a fixed sidebar/header layout or each sidebars’ height < window height
    if (header.hasClass('navbar-fixed-top') || header.hasClass('navbar-fixed-bottom') || sidebarH < windowH && sidebarAltH < windowH) {
      if (page.hasClass('footer-fixed')) {
        // if footer is fixed don't remove its height
        pageContent.css('min-height', windowH - headerH + 'px');
      } else {
        // else if footer is static, remove its height
        pageContent.css('min-height', windowH - (headerH + footerH) + 'px');
      }
    } else {
      // In any other case set #page-content height the same as biggest sidebar's height
      if (page.hasClass('footer-fixed')) {
        // if footer is fixed don't remove its height
        pageContent.css('min-height', (sidebarH > sidebarAltH ? sidebarH : sidebarAltH) - headerH + 'px');
      } else {
        // else if footer is static, remove its height
        pageContent.css('min-height', (sidebarH > sidebarAltH ? sidebarH : sidebarAltH) - (headerH + footerH) + 'px');
      }
    }
  };

  /* Interactive blocks functionality */
  var interactiveBlocks = function interactiveBlocks() {
    // Toggle block's content
    $('[data-toggle="block-toggle-content"]').on('click', function () {
      var blockContent = $(this).closest('.block').find('.block-content');
      if ($(this).hasClass('active')) {
        blockContent.slideDown();
      } else {
        blockContent.slideUp();
      }
      $(this).toggleClass('active');
    });

    // Toggle block fullscreen
    $('[data-toggle="block-toggle-fullscreen"]').on('click', function () {
      var block = $(this).closest('.block');
      if ($(this).hasClass('active')) {
        block.removeClass('block-fullscreen');
      } else {
        block.addClass('block-fullscreen');
      }
      $(this).toggleClass('active');
    });

    // Hide block
    $('[data-toggle="block-hide"]').on('click', function () {
      $(this).closest('.block').fadeOut();
    });
  };

  /* Scroll to top functionality */
  var scrollToTop = function scrollToTop() {
    // Get link
    var link = $('#to-top');
    $(window).scroll(function () {
      // If the user scrolled a bit (150 pixels) show the link in large resolutions
      if ($(this).scrollTop() > 150 && getWindowWidth() > 991) {
        link.fadeIn(100);
      } else {
        link.fadeOut(100);
      }
    });

    // On click get to top
    link.click(function () {
      $('html, body').animate({
        scrollTop: 0
      }, 400);
      return false;
    });
  };

  /* Demo chat functionality (in sidebar) */
  var chatUi = function chatUi() {
    var chatUsers = $('.chat-users');
    var chatTalk = $('.chat-talk');
    var chatMessages = $('.chat-talk-messages');
    var chatInput = $('#sidebar-chat-message');
    var chatMsg = '';

    // Initialize scrolling on chat talk list
    chatMessages.slimScroll({
      height: 210,
      color: '#fff',
      size: '3px',
      position: 'left',
      touchScrollStep: 100
    });

    // If a chat user is clicked show the chat talk
    $('a', chatUsers).click(function () {
      chatUsers.slideUp();
      chatTalk.slideDown();
      chatInput.focus();
      return false;
    });

    // If chat talk close button is clicked show the chat user list
    $('#chat-talk-close-btn').click(function () {
      chatTalk.slideUp();
      chatUsers.slideDown();
      return false;
    });

    // When the chat message form is submitted
    $('#sidebar-chat-form').submit(function (e) {
      // Get text from message input
      chatMsg = chatInput.val();

      // If the user typed a message
      if (chatMsg) {
        // Add it to the message list
        chatMessages.append('<li class="chat-talk-msg chat-talk-msg-highlight themed-border animation-slideLeft">' + $('<div />').text(chatMsg).html() + '</li>');

        // Scroll the message list to the bottom
        chatMessages.slimScroll({
          scrollTo: chatMessages[0].scrollHeight + 'px'
        });

        // Reset the message input
        chatInput.val('');
      }

      // Don't submit the message form
      e.preventDefault();
    });
  };

  /* Template Options, change features functionality */
  var templateOptions = function templateOptions() {
    /*
     * Color Themes
     */
    var colorList = $('.sidebar-themes');
    var themeLink = $('#theme-link');
    var themeColor = themeLink.length ? themeLink.attr('href') : 'default';
    var cookies = page.hasClass('enable-cookies') ? true : false;
    var themeColorCke;

    // If cookies have been enabled
    if (cookies) {
      themeColorCke = Cookies.get('optionThemeColor') ? Cookies.get('optionThemeColor') : false;

      // Update color theme
      if (themeColorCke) {
        if (themeColorCke === 'default') {
          if (themeLink.length) {
            themeLink.remove();
            themeLink = $('#theme-link');
          }
        } else {
          if (themeLink.length) {
            themeLink.attr('href', themeColorCke);
          } else {
            $('link[href="css/themes.css"]').before('<link id="theme-link" rel="stylesheet" href="' + themeColorCke + '">');
            themeLink = $('#theme-link');
          }
        }
      }
      themeColor = themeColorCke ? themeColorCke : themeColor;
    }

    // Set the active color theme link as active
    $('a[data-theme="' + themeColor + '"]', colorList).parent('li').addClass('active');

    // When a color theme link is clicked
    $('a', colorList).click(function (e) {
      // Get theme name
      themeColor = $(this).data('theme');
      $('li', colorList).removeClass('active');
      $(this).parent('li').addClass('active');
      if (themeColor === 'default') {
        if (themeLink.length) {
          themeLink.remove();
          themeLink = $('#theme-link');
        }
      } else {
        if (themeLink.length) {
          themeLink.attr('href', themeColor);
        } else {
          $('link[href="css/themes.css"]').before('<link id="theme-link" rel="stylesheet" href="' + themeColor + '">');
          themeLink = $('#theme-link');
        }
      }

      // If cookies have been enabled, save the new options
      if (cookies) {
        Cookies.set('optionThemeColor', themeColor, {
          expires: 7
        });
      }
    });

    // Prevent template options dropdown from closing on clicking options
    $('.dropdown-options a').click(function (e) {
      e.stopPropagation();
    });

    /* Page Style */
    var optMainStyle = $('#options-main-style');
    var optMainStyleAlt = $('#options-main-style-alt');
    if (page.hasClass('style-alt')) {
      optMainStyleAlt.addClass('active');
    } else {
      optMainStyle.addClass('active');
    }
    optMainStyle.click(function () {
      page.removeClass('style-alt');
      $(this).addClass('active');
      optMainStyleAlt.removeClass('active');
    });
    optMainStyleAlt.click(function () {
      page.addClass('style-alt');
      $(this).addClass('active');
      optMainStyle.removeClass('active');
    });

    /* Header options */
    var optHeaderDefault = $('#options-header-default');
    var optHeaderInverse = $('#options-header-inverse');
    if (header.hasClass('navbar-default')) {
      optHeaderDefault.addClass('active');
    } else {
      optHeaderInverse.addClass('active');
    }
    optHeaderDefault.click(function () {
      header.removeClass('navbar-inverse').addClass('navbar-default');
      $(this).addClass('active');
      optHeaderInverse.removeClass('active');
    });
    optHeaderInverse.click(function () {
      header.removeClass('navbar-default').addClass('navbar-inverse');
      $(this).addClass('active');
      optHeaderDefault.removeClass('active');
    });
  };

  /* Datatables basic Bootstrap integration (pagination integration included under the Datatables plugin in plugins.js) */
  var dtIntegration = function dtIntegration() {
    $.extend(true, $.fn.dataTable.defaults, {
      "sDom": "<'row'<'col-sm-6 col-xs-5'l><'col-sm-6 col-xs-7'f>r>t<'row'<'col-sm-5 hidden-xs'i><'col-sm-7 col-xs-12 clearfix'p>>",
      "sPaginationType": "bootstrap",
      "oLanguage": {
        "sLengthMenu": "_MENU_",
        "sSearch": "<div class=\"input-group\">_INPUT_<span class=\"input-group-addon\"><i class=\"fa fa-search\"></i></span></div>",
        "sInfo": "<strong>_START_</strong>-<strong>_END_</strong> of <strong>_TOTAL_</strong>",
        "oPaginate": {
          "sPrevious": "",
          "sNext": ""
        }
      }
    });
    $.extend($.fn.dataTableExt.oStdClasses, {
      "sWrapper": "dataTables_wrapper form-inline",
      "sFilterInput": "form-control",
      "sLengthSelect": "form-control"
    });
  };

  /* Print functionality - Hides all sidebars, prints the page and then restores them (To fix an issue with CSS print styles in webkit browsers)  */
  var handlePrint = function handlePrint() {
    // Store all #page-container classes
    var pageCls = page.prop('class');

    // Remove all classes from #page-container
    page.prop('class', '');

    // Print the page
    window.print();

    // Restore all #page-container classes
    page.prop('class', pageCls);
  };
  return {
    init: function init() {
      uiInit(); // Initialize UI Code
      pageLoading(); // Initialize Page Loading
    },

    sidebar: function sidebar(mode, extra) {
      handleSidebar(mode, extra); // Handle sidebars - access functionality from everywhere
    },

    datatables: function datatables() {
      dtIntegration(); // Datatables Bootstrap integration
    },

    pagePrint: function pagePrint() {
      handlePrint(); // Print functionality
    }
  };
}();

/* Initialize app when page loads */
$(function () {
  App.init();
});
})();

/******/ })()
;