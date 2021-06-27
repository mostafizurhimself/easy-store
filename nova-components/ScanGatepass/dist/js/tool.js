/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);
module.exports = __webpack_require__(11);


/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

Nova.booting(function (Vue, router, store) {
  router.addRoutes([{
    name: 'scan-gatepass',
    path: '/scan-gatepass',
    component: __webpack_require__(2)
  }]);
});

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(3)
}
var normalizeComponent = __webpack_require__(8)
/* script */
var __vue_script__ = __webpack_require__(9)
/* template */
var __vue_template__ = __webpack_require__(10)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/Tool.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-68ff5483", Component.options)
  } else {
    hotAPI.reload("data-v-68ff5483", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(4);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(6)("290c3e45", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../node_modules/css-loader/index.js!../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-68ff5483\",\"scoped\":false,\"hasInlineConfig\":true}!../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Tool.vue", function() {
     var newContent = require("!!../../../node_modules/css-loader/index.js!../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-68ff5483\",\"scoped\":false,\"hasInlineConfig\":true}!../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Tool.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(5)(false);
// imports


// module
exports.push([module.i, "\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n/* Scoped Styles */\n", ""]);

// exports


/***/ }),
/* 5 */
/***/ (function(module, exports) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
module.exports = function(useSourceMap) {
	var list = [];

	// return the list of modules as css string
	list.toString = function toString() {
		return this.map(function (item) {
			var content = cssWithMappingToString(item, useSourceMap);
			if(item[2]) {
				return "@media " + item[2] + "{" + content + "}";
			} else {
				return content;
			}
		}).join("");
	};

	// import a list of modules into the list
	list.i = function(modules, mediaQuery) {
		if(typeof modules === "string")
			modules = [[null, modules, ""]];
		var alreadyImportedModules = {};
		for(var i = 0; i < this.length; i++) {
			var id = this[i][0];
			if(typeof id === "number")
				alreadyImportedModules[id] = true;
		}
		for(i = 0; i < modules.length; i++) {
			var item = modules[i];
			// skip already imported module
			// this implementation is not 100% perfect for weird media query combinations
			//  when a module is imported multiple times with different media queries.
			//  I hope this will never occur (Hey this way we have smaller bundles)
			if(typeof item[0] !== "number" || !alreadyImportedModules[item[0]]) {
				if(mediaQuery && !item[2]) {
					item[2] = mediaQuery;
				} else if(mediaQuery) {
					item[2] = "(" + item[2] + ") and (" + mediaQuery + ")";
				}
				list.push(item);
			}
		}
	};
	return list;
};

function cssWithMappingToString(item, useSourceMap) {
	var content = item[1] || '';
	var cssMapping = item[3];
	if (!cssMapping) {
		return content;
	}

	if (useSourceMap && typeof btoa === 'function') {
		var sourceMapping = toComment(cssMapping);
		var sourceURLs = cssMapping.sources.map(function (source) {
			return '/*# sourceURL=' + cssMapping.sourceRoot + source + ' */'
		});

		return [content].concat(sourceURLs).concat([sourceMapping]).join('\n');
	}

	return [content].join('\n');
}

// Adapted from convert-source-map (MIT)
function toComment(sourceMap) {
	// eslint-disable-next-line no-undef
	var base64 = btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap))));
	var data = 'sourceMappingURL=data:application/json;charset=utf-8;base64,' + base64;

	return '/*# ' + data + ' */';
}


/***/ }),
/* 6 */
/***/ (function(module, exports, __webpack_require__) {

/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
  Modified by Evan You @yyx990803
*/

var hasDocument = typeof document !== 'undefined'

if (typeof DEBUG !== 'undefined' && DEBUG) {
  if (!hasDocument) {
    throw new Error(
    'vue-style-loader cannot be used in a non-browser environment. ' +
    "Use { target: 'node' } in your Webpack config to indicate a server-rendering environment."
  ) }
}

var listToStyles = __webpack_require__(7)

/*
type StyleObject = {
  id: number;
  parts: Array<StyleObjectPart>
}

type StyleObjectPart = {
  css: string;
  media: string;
  sourceMap: ?string
}
*/

var stylesInDom = {/*
  [id: number]: {
    id: number,
    refs: number,
    parts: Array<(obj?: StyleObjectPart) => void>
  }
*/}

var head = hasDocument && (document.head || document.getElementsByTagName('head')[0])
var singletonElement = null
var singletonCounter = 0
var isProduction = false
var noop = function () {}
var options = null
var ssrIdKey = 'data-vue-ssr-id'

// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
// tags it will allow on a page
var isOldIE = typeof navigator !== 'undefined' && /msie [6-9]\b/.test(navigator.userAgent.toLowerCase())

module.exports = function (parentId, list, _isProduction, _options) {
  isProduction = _isProduction

  options = _options || {}

  var styles = listToStyles(parentId, list)
  addStylesToDom(styles)

  return function update (newList) {
    var mayRemove = []
    for (var i = 0; i < styles.length; i++) {
      var item = styles[i]
      var domStyle = stylesInDom[item.id]
      domStyle.refs--
      mayRemove.push(domStyle)
    }
    if (newList) {
      styles = listToStyles(parentId, newList)
      addStylesToDom(styles)
    } else {
      styles = []
    }
    for (var i = 0; i < mayRemove.length; i++) {
      var domStyle = mayRemove[i]
      if (domStyle.refs === 0) {
        for (var j = 0; j < domStyle.parts.length; j++) {
          domStyle.parts[j]()
        }
        delete stylesInDom[domStyle.id]
      }
    }
  }
}

function addStylesToDom (styles /* Array<StyleObject> */) {
  for (var i = 0; i < styles.length; i++) {
    var item = styles[i]
    var domStyle = stylesInDom[item.id]
    if (domStyle) {
      domStyle.refs++
      for (var j = 0; j < domStyle.parts.length; j++) {
        domStyle.parts[j](item.parts[j])
      }
      for (; j < item.parts.length; j++) {
        domStyle.parts.push(addStyle(item.parts[j]))
      }
      if (domStyle.parts.length > item.parts.length) {
        domStyle.parts.length = item.parts.length
      }
    } else {
      var parts = []
      for (var j = 0; j < item.parts.length; j++) {
        parts.push(addStyle(item.parts[j]))
      }
      stylesInDom[item.id] = { id: item.id, refs: 1, parts: parts }
    }
  }
}

function createStyleElement () {
  var styleElement = document.createElement('style')
  styleElement.type = 'text/css'
  head.appendChild(styleElement)
  return styleElement
}

function addStyle (obj /* StyleObjectPart */) {
  var update, remove
  var styleElement = document.querySelector('style[' + ssrIdKey + '~="' + obj.id + '"]')

  if (styleElement) {
    if (isProduction) {
      // has SSR styles and in production mode.
      // simply do nothing.
      return noop
    } else {
      // has SSR styles but in dev mode.
      // for some reason Chrome can't handle source map in server-rendered
      // style tags - source maps in <style> only works if the style tag is
      // created and inserted dynamically. So we remove the server rendered
      // styles and inject new ones.
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  if (isOldIE) {
    // use singleton mode for IE9.
    var styleIndex = singletonCounter++
    styleElement = singletonElement || (singletonElement = createStyleElement())
    update = applyToSingletonTag.bind(null, styleElement, styleIndex, false)
    remove = applyToSingletonTag.bind(null, styleElement, styleIndex, true)
  } else {
    // use multi-style-tag mode in all other cases
    styleElement = createStyleElement()
    update = applyToTag.bind(null, styleElement)
    remove = function () {
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  update(obj)

  return function updateStyle (newObj /* StyleObjectPart */) {
    if (newObj) {
      if (newObj.css === obj.css &&
          newObj.media === obj.media &&
          newObj.sourceMap === obj.sourceMap) {
        return
      }
      update(obj = newObj)
    } else {
      remove()
    }
  }
}

var replaceText = (function () {
  var textStore = []

  return function (index, replacement) {
    textStore[index] = replacement
    return textStore.filter(Boolean).join('\n')
  }
})()

function applyToSingletonTag (styleElement, index, remove, obj) {
  var css = remove ? '' : obj.css

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = replaceText(index, css)
  } else {
    var cssNode = document.createTextNode(css)
    var childNodes = styleElement.childNodes
    if (childNodes[index]) styleElement.removeChild(childNodes[index])
    if (childNodes.length) {
      styleElement.insertBefore(cssNode, childNodes[index])
    } else {
      styleElement.appendChild(cssNode)
    }
  }
}

function applyToTag (styleElement, obj) {
  var css = obj.css
  var media = obj.media
  var sourceMap = obj.sourceMap

  if (media) {
    styleElement.setAttribute('media', media)
  }
  if (options.ssrId) {
    styleElement.setAttribute(ssrIdKey, obj.id)
  }

  if (sourceMap) {
    // https://developer.chrome.com/devtools/docs/javascript-debugging
    // this makes source maps inside style tags work properly in Chrome
    css += '\n/*# sourceURL=' + sourceMap.sources[0] + ' */'
    // http://stackoverflow.com/a/26603875
    css += '\n/*# sourceMappingURL=data:application/json;base64,' + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + ' */'
  }

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = css
  } else {
    while (styleElement.firstChild) {
      styleElement.removeChild(styleElement.firstChild)
    }
    styleElement.appendChild(document.createTextNode(css))
  }
}


/***/ }),
/* 7 */
/***/ (function(module, exports) {

/**
 * Translates the list format produced by css-loader into something
 * easier to manipulate.
 */
module.exports = function listToStyles (parentId, list) {
  var styles = []
  var newStyles = {}
  for (var i = 0; i < list.length; i++) {
    var item = list[i]
    var id = item[0]
    var css = item[1]
    var media = item[2]
    var sourceMap = item[3]
    var part = {
      id: parentId + ':' + i,
      css: css,
      media: media,
      sourceMap: sourceMap
    }
    if (!newStyles[id]) {
      styles.push(newStyles[id] = { id: id, parts: [part] })
    } else {
      newStyles[id].parts.push(part)
    }
  }
  return styles
}


/***/ }),
/* 8 */
/***/ (function(module, exports) {

/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file.
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = injectStyles
  }

  if (hook) {
    var functional = options.functional
    var existing = functional
      ? options.render
      : options.beforeCreate

    if (!functional) {
      // inject component registration as beforeCreate hook
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    } else {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return existing(h, context)
      }
    }
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),
/* 9 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    metaInfo: function metaInfo() {
        return {
            title: "ScanGatepass"
        };
    },
    data: function data() {
        return {
            errors: null,
            pass: "",
            details: null
        };
    },

    methods: {
        submitPass: function submitPass() {
            this.errors = null;
            this.details = null;
            this.getDetailsData(this.pass);
        },
        getDetailsData: function getDetailsData(pass) {
            var _this = this;

            Nova.request().get("/nova-vendor/scan-gatepass/passes?pass=" + pass).then(function (response) {
                console.log("res data", response.data);
                _this.details = response.data;
            }).catch(function (err) {
                if (err.response.status == 422) {
                    _this.details = null;
                    console.log(_this.errors);
                    _this.errors = err.response.data.errors.pass[0];
                } else if (err.response.status == 404) {
                    _this.errors = err.response.data.message;
                } else {
                    console.log(err.response.data);
                }
            });
        },
        passDetailData: function passDetailData() {
            var _this2 = this;

            Nova.request().post("/nova-vendor/scan-gatepass/passes", {
                pass: this.pass
            }).then(function (response) {
                //   console.log("res data", response.data);
                _this2.$toasted.show(response.data.message, {
                    type: "success"
                });
                _this2.details = null;
                _this2.pass = "";
                _this2.$refs.pass.focus();
            }).catch(function (err) {
                if (err.response.status == 422) {
                    // console.log(this.errors);
                    _this2.errors = err.response.data.errors.pass[0];
                    _this2.$toasted.show(err.response.data.errors.pass[0], {
                        type: "error"
                    });
                } else if (err.response.status == 404) {
                    _this2.$toasted.show(err.response.data.message, {
                        type: "error"
                    });
                } else {
                    console.log(err.response.data);
                }
            });
        },
        addShotcuts: function addShotcuts(e) {
            if (e.key == "Enter") {
                this.passDetailData();
            }
        }
    },
    mounted: function mounted() {
        // Listening for window Keyboard press
        window.addEventListener("keyup", this.addShotcuts);
    }
});

/***/ }),
/* 10 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    [
      _c("heading", { staticClass: "mb-6" }, [_vm._v("Scan Gatepass")]),
      _vm._v(" "),
      _c(
        "card",
        {
          staticClass: "bg-white mb-6 py-4 px-6",
          staticStyle: { "min-height": "300px" }
        },
        [
          _c("div", { staticClass: "flex justify-center pb-2" }, [
            _c("div", [
              _c("input", {
                directives: [
                  {
                    name: "model",
                    rawName: "v-model.trim",
                    value: _vm.pass,
                    expression: "pass",
                    modifiers: { trim: true }
                  }
                ],
                ref: "pass",
                staticClass:
                  "rounded-lg py-3 px-4 border border-70 outline-none mt-8",
                attrs: {
                  type: "text",
                  placeholder: "Enter Gatepass",
                  autofocus: ""
                },
                domProps: { value: _vm.pass },
                on: {
                  keyup: _vm.submitPass,
                  input: function($event) {
                    if ($event.target.composing) {
                      return
                    }
                    _vm.pass = $event.target.value.trim()
                  },
                  blur: function($event) {
                    return _vm.$forceUpdate()
                  }
                }
              }),
              _vm._v(" "),
              _vm.errors
                ? _c("div", { staticClass: "text-danger mt-1" }, [
                    _vm._v(_vm._s(_vm.errors))
                  ])
                : _vm._e()
            ])
          ]),
          _vm._v(" "),
          _vm.details
            ? _c("div", [
                _vm.details.type == "goods"
                  ? _c("div", { staticClass: "my-4 border border-40" }, [
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Number")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80 text-primary" }, [
                            _vm._v(_vm._s(_vm.details.readableId))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Total Bag")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(_vm._s(_vm.details.details.total_bag))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Total Cartoon")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(_vm._s(_vm.details.details.total_ctn))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Total Poly")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(_vm._s(_vm.details.details.total_poly))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Status")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c(
                            "h5",
                            { staticClass: "text-80 text-primary capitalize" },
                            [_vm._v(_vm._s(_vm.details.status))]
                          )
                        ])
                      ])
                    ])
                  : _vm._e(),
                _vm._v(" "),
                _vm.details.type == "visitor"
                  ? _c("div", { staticClass: "my-4 border border-40" }, [
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Number")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80 text-primary" }, [
                            _vm._v(_vm._s(_vm.details.readableId))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Card No:")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(_vm._s(_vm.details.cardNo))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Name")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(_vm._s(_vm.details.visitorName))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Mobile")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(_vm._s(_vm.details.mobile))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Purpose")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(_vm._s(_vm.details.purpose))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Status")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c(
                            "h5",
                            { staticClass: "text-80 text-primary capitalize" },
                            [_vm._v(_vm._s(_vm.details.status))]
                          )
                        ])
                      ])
                    ])
                  : _vm._e(),
                _vm._v(" "),
                _vm.details.type == "employee"
                  ? _c("div", { staticClass: "my-4 border border-40" }, [
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Number")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80 text-primary" }, [
                            _vm._v(_vm._s(_vm.details.readableId))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Employee ID")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(_vm._s(_vm.details.employeeId))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Gender")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80 capitalize" }, [
                            _vm._v(_vm._s(_vm.details.employee.gender))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Name")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(
                              _vm._s(_vm.details.employee.firstName) +
                                " " +
                                _vm._s(_vm.details.employee.lastName)
                            )
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Mobile")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(_vm._s(_vm.details.employee.mobile))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Approved In")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(_vm._s(_vm.details.approvedInReadable))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Approved Out")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(_vm._s(_vm.details.approvedOutReadable))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Out")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(_vm._s(_vm.details.outTimeReadable))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("In")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(_vm._s(_vm.details.inTimeReadable))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Early Leave")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(
                              _vm._s(
                                "" +
                                  (_vm.details.earlyLeave == 0 ? "No" : "Yes")
                              )
                            )
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Reason")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(_vm._s(_vm.details.reason))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Status")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c(
                            "h5",
                            { staticClass: "text-80 text-primary capitalize" },
                            [_vm._v(_vm._s(_vm.details.status))]
                          )
                        ])
                      ])
                    ])
                  : _vm._e(),
                _vm._v(" "),
                _vm.details.type == "manual"
                  ? _c("div", { staticClass: "my-4 border border-40" }, [
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Number")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80 text-primary" }, [
                            _vm._v(_vm._s(_vm.details.readableId))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Items")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c(
                            "div",
                            {
                              staticClass:
                                "overflow-hidden relative rounded-lg bg-white shadow border border-60"
                            },
                            [
                              _c(
                                "table",
                                {
                                  staticClass:
                                    "table w-full table-default nova-resource-table"
                                },
                                [
                                  _c("thead", [
                                    _c("tr", { staticClass: "font-normal" }, [
                                      _c("th", [_vm._v("DESCRIPTION")]),
                                      _vm._v(" "),
                                      _c("th", [_vm._v("QUANTITY")])
                                    ])
                                  ]),
                                  _vm._v(" "),
                                  _vm._l(_vm.details.items, function(item) {
                                    return _c("tbody", { key: item.index }, [
                                      _c(
                                        "tr",
                                        {
                                          staticClass: "nova-resource-table-row"
                                        },
                                        [
                                          _c(
                                            "td",
                                            {
                                              staticClass: "text-sm",
                                              staticStyle: { height: "2rem" }
                                            },
                                            [_vm._v(_vm._s(item.description))]
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "td",
                                            {
                                              staticClass: "text-sm",
                                              staticStyle: { height: "2rem" }
                                            },
                                            [_vm._v(_vm._s(item.quantity))]
                                          )
                                        ]
                                      )
                                    ])
                                  })
                                ],
                                2
                              )
                            ]
                          )
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Total Quantity")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(_vm._s(_vm.details.totalQuantity))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Total Bag")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(_vm._s(_vm.details.summary.total_bag))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Total Ctn")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(_vm._s(_vm.details.summary.total_ctn))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Total Poly")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(_vm._s(_vm.details.summary.total_poly))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Note")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c("h5", { staticClass: "text-80" }, [
                            _vm._v(_vm._s(_vm.details.note))
                          ])
                        ])
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "flex border-b border-40" }, [
                        _c("div", { staticClass: "w-1/4 p-4 bg-30" }, [
                          _c("h5", { staticClass: "font-normal text-80" }, [
                            _vm._v("Status")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-3/4 p-4 break-words" }, [
                          _c(
                            "h5",
                            { staticClass: "text-80 text-primary capitalize" },
                            [_vm._v(_vm._s(_vm.details.status))]
                          )
                        ])
                      ])
                    ])
                  : _vm._e(),
                _vm._v(" "),
                _vm.details.type == "employee" &&
                _vm.details.status == "passed" &&
                _vm.details.in == null
                  ? _c("div", { staticClass: "text-center" }, [
                      _c(
                        "a",
                        {
                          staticClass:
                            "btn btn-default btn-primary cursor-pointer",
                          on: { click: _vm.passDetailData }
                        },
                        [_vm._v("CheckIn")]
                      )
                    ])
                  : _vm._e(),
                _vm._v(" "),
                _vm.details.status == "confirmed"
                  ? _c("div", { staticClass: "text-center" }, [
                      _c(
                        "a",
                        {
                          staticClass:
                            "btn btn-default btn-primary cursor-pointer",
                          on: { click: _vm.passDetailData }
                        },
                        [_vm._v("Pass")]
                      )
                    ])
                  : _vm._e()
              ])
            : _vm._e()
        ]
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-68ff5483", module.exports)
  }
}

/***/ }),
/* 11 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);