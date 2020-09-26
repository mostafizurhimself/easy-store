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
  Vue.config.devtools = true;

  router.addRoutes([{
    name: 'invoice',
    path: '/invoice',
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
exports.push([module.i, "\n#invoice{\n    /* padding: 30px; */\n}\na{\n    text-decoration: none;\n    color: #222;\n}\n.row{\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n}\n.row .col{\n    -webkit-box-flex: 1;\n        -ms-flex-positive: 1;\n            flex-grow: 1;\n}\n.invoice {\n    position: relative;\n    background-color: #FFF;\n    min-height: 680px;\n    padding: 30px\n}\n.invoice header {\n    padding: 10px 0;\n    margin-bottom: 20px;\n    border-bottom: 1px solid #222\n}\n.invoice .company-details {\n    text-align: right\n}\n.invoice .company-details .name {\n    margin-top: 0;\n    margin-bottom: 0\n}\n.invoice .contacts {\n    margin-bottom: 20px\n}\n.invoice .invoice-to {\n    text-align: left\n}\n.invoice .invoice-to .to {\n    margin-top: 0;\n    margin-bottom: 0\n}\n.invoice .invoice-details {\n    text-align: right\n}\n.invoice .invoice-details .invoice-id {\n    margin-top: 0;\n    color: #222\n}\n.invoice main {\n    padding-bottom: 50px\n}\n.invoice main .thanks {\n    margin-top: -100px;\n    font-size: 2em;\n    margin-bottom: 50px\n}\n.invoice main .notices {\n    padding-left: 6px;\n    border-left: 6px solid #222\n}\n.invoice main .notices .notice {\n    font-size: 1.2em\n}\n.invoice table {\n    width: 100%;\n    border-collapse: collapse;\n    border-spacing: 0;\n    margin-bottom: 20px\n}\n.invoice table td,.invoice table th {\n    padding: 15px;\n    background: #eee;\n    border-bottom: 1px solid #fff\n}\n.invoice table th {\n    white-space: nowrap;\n    font-weight: 400;\n    font-size: 16px\n}\n.invoice table td h3 {\n    margin: 0;\n    font-weight: 400;\n    color: #222;\n    font-size: 1.2em\n}\n.invoice table .qty,.invoice table .total,.invoice table .unit {\n    text-align: right;\n    font-size: 1.2em\n}\n.invoice table .no {\n    color: #fff;\n    font-size: 1.6em;\n    background: #222\n}\n.invoice table .unit {\n    background: #ddd\n}\n.invoice table .total {\n    background: #222;\n    color: #fff\n}\n.invoice table tbody tr:last-child td {\n    border: none\n}\n.invoice table tfoot td {\n    background: 0 0;\n    border-bottom: none;\n    white-space: nowrap;\n    text-align: right;\n    padding: 10px 20px;\n    font-size: 1.2em;\n    border-top: 1px solid #aaa\n}\n.invoice table tfoot tr:first-child td {\n    border-top: none\n}\n.invoice table tfoot tr:last-child td {\n    color: #222;\n    font-size: 1.4em;\n    border-top: 1px solid #222\n}\n.invoice table tfoot tr td:first-child {\n    border: none\n}\n.invoice footer {\n    width: 100%;\n    text-align: center;\n    color: #777;\n    border-top: 1px solid #aaa;\n    padding: 8px 0\n}\n@media print {\n.invoice {\n        font-size: 11px!important;\n        overflow: hidden!important\n}\n.invoice footer {\n        position: absolute;\n        bottom: 10px;\n        page-break-after: always\n}\n.invoice>div:last-child {\n        page-break-before: always\n}\n}\n", ""]);

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

/* harmony default export */ __webpack_exports__["default"] = ({
    mounted: function mounted() {
        //
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
      _c("heading", { staticClass: "mb-6" }, [_vm._v("Finishing Invoice")]),
      _vm._v(" "),
      _vm._m(0)
    ],
    1
  )
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { attrs: { id: "invoice" } }, [
      _c("div", { staticClass: "invoice overflow-auto" }, [
        _c("div", { staticStyle: { "min-width": "600px" } }, [
          _c("header", [
            _c("div", { staticClass: "row" }, [
              _c("div", { staticClass: "col" }, [
                _c("a", { attrs: { target: "_blank", href: "#" } }, [
                  _c("img", {
                    attrs: {
                      src: __webpack_require__(15),
                      "data-holder-rendered": "true",
                      width: "100px"
                    }
                  })
                ])
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "col company-details" }, [
                _c("h2", { staticClass: "name" }, [
                  _c("a", { attrs: { target: "_blank", href: "#" } }, [
                    _vm._v(
                      "\n                                Easy Fashion Ltd.\n                            "
                    )
                  ])
                ]),
                _vm._v(" "),
                _c("div", [_vm._v("1, DIT Road, Hazipara")]),
                _vm._v(" "),
                _c("div", [_vm._v("01834507645")]),
                _vm._v(" "),
                _c("div", [_vm._v("easyfashionwears@gmail.com")])
              ])
            ])
          ]),
          _vm._v(" "),
          _c("main", [
            _c("div", { staticClass: "row contacts" }, [
              _c("div", { staticClass: "col invoice-to" }, [
                _c("div", { staticClass: "text-gray-light" }, [
                  _vm._v("INVOICE TO:")
                ]),
                _vm._v(" "),
                _c("h2", { staticClass: "to" }, [_vm._v("John Doe")]),
                _vm._v(" "),
                _c("div", { staticClass: "address" }, [
                  _vm._v("796 Silver Harbour, TX 79273, US")
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "email" }, [
                  _c("a", { attrs: { href: "mailto:john@example.com" } }, [
                    _vm._v("john@example.com")
                  ])
                ])
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "col invoice-details" }, [
                _c("h1", { staticClass: "invoice-id" }, [
                  _vm._v("INVOICE 3-2-1")
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "date" }, [
                  _vm._v("Date of Invoice: 01/10/2018")
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "date" }, [
                  _vm._v("Due Date: 30/10/2018")
                ])
              ])
            ]),
            _vm._v(" "),
            _c(
              "table",
              { attrs: { border: "0", cellspacing: "0", cellpadding: "0" } },
              [
                _c("thead", [
                  _c("tr", [
                    _c("th", [_vm._v("#")]),
                    _vm._v(" "),
                    _c("th", { staticClass: "text-left" }, [
                      _vm._v("DESCRIPTION")
                    ]),
                    _vm._v(" "),
                    _c("th", { staticClass: "text-right" }, [
                      _vm._v("HOUR PRICE")
                    ]),
                    _vm._v(" "),
                    _c("th", { staticClass: "text-right" }, [_vm._v("HOURS")]),
                    _vm._v(" "),
                    _c("th", { staticClass: "text-right" }, [_vm._v("TOTAL")])
                  ])
                ]),
                _vm._v(" "),
                _c("tbody", [
                  _c("tr", [
                    _c("td", { staticClass: "no" }, [_vm._v("04")]),
                    _vm._v(" "),
                    _c("td", { staticClass: "text-left" }, [
                      _c("h3", [
                        _c(
                          "a",
                          {
                            attrs: {
                              target: "_blank",
                              href:
                                "https://www.youtube.com/channel/UC_UMEcP_kF0z4E6KbxCpV1w"
                            }
                          },
                          [
                            _vm._v(
                              "\n                                Youtube channel\n                                "
                            )
                          ]
                        )
                      ]),
                      _vm._v(" "),
                      _c(
                        "a",
                        {
                          attrs: {
                            target: "_blank",
                            href:
                              "https://www.youtube.com/channel/UC_UMEcP_kF0z4E6KbxCpV1w"
                          }
                        },
                        [
                          _vm._v(
                            "\n                                   Useful videos\n                               "
                          )
                        ]
                      ),
                      _vm._v(
                        "\n                               to improve your Javascript skills. Subscribe and stay tuned :)\n                            "
                      )
                    ]),
                    _vm._v(" "),
                    _c("td", { staticClass: "unit" }, [_vm._v("$0.00")]),
                    _vm._v(" "),
                    _c("td", { staticClass: "qty" }, [_vm._v("100")]),
                    _vm._v(" "),
                    _c("td", { staticClass: "total" }, [_vm._v("$0.00")])
                  ]),
                  _vm._v(" "),
                  _c("tr", [
                    _c("td", { staticClass: "no" }, [_vm._v("01")]),
                    _vm._v(" "),
                    _c("td", { staticClass: "text-left" }, [
                      _c("h3", [_vm._v("Website Design")]),
                      _vm._v(
                        "Creating a recognizable design solution based on the company's existing visual identity"
                      )
                    ]),
                    _vm._v(" "),
                    _c("td", { staticClass: "unit" }, [_vm._v("$40.00")]),
                    _vm._v(" "),
                    _c("td", { staticClass: "qty" }, [_vm._v("30")]),
                    _vm._v(" "),
                    _c("td", { staticClass: "total" }, [_vm._v("$1,200.00")])
                  ]),
                  _vm._v(" "),
                  _c("tr", [
                    _c("td", { staticClass: "no" }, [_vm._v("02")]),
                    _vm._v(" "),
                    _c("td", { staticClass: "text-left" }, [
                      _c("h3", [_vm._v("Website Development")]),
                      _vm._v(
                        "Developing a Content Management System-based Website"
                      )
                    ]),
                    _vm._v(" "),
                    _c("td", { staticClass: "unit" }, [_vm._v("$40.00")]),
                    _vm._v(" "),
                    _c("td", { staticClass: "qty" }, [_vm._v("80")]),
                    _vm._v(" "),
                    _c("td", { staticClass: "total" }, [_vm._v("$3,200.00")])
                  ]),
                  _vm._v(" "),
                  _c("tr", [
                    _c("td", { staticClass: "no" }, [_vm._v("03")]),
                    _vm._v(" "),
                    _c("td", { staticClass: "text-left" }, [
                      _c("h3", [_vm._v("Search Engines Optimization")]),
                      _vm._v("Optimize the site for search engines (SEO)")
                    ]),
                    _vm._v(" "),
                    _c("td", { staticClass: "unit" }, [_vm._v("$40.00")]),
                    _vm._v(" "),
                    _c("td", { staticClass: "qty" }, [_vm._v("20")]),
                    _vm._v(" "),
                    _c("td", { staticClass: "total" }, [_vm._v("$800.00")])
                  ])
                ]),
                _vm._v(" "),
                _c("tfoot", [
                  _c("tr", [
                    _c("td", { attrs: { colspan: "2" } }),
                    _vm._v(" "),
                    _c("td", { attrs: { colspan: "2" } }, [_vm._v("SUBTOTAL")]),
                    _vm._v(" "),
                    _c("td", [_vm._v("$5,200.00")])
                  ]),
                  _vm._v(" "),
                  _c("tr", [
                    _c("td", { attrs: { colspan: "2" } }),
                    _vm._v(" "),
                    _c("td", { attrs: { colspan: "2" } }, [_vm._v("TAX 25%")]),
                    _vm._v(" "),
                    _c("td", [_vm._v("$1,300.00")])
                  ]),
                  _vm._v(" "),
                  _c("tr", [
                    _c("td", { attrs: { colspan: "2" } }),
                    _vm._v(" "),
                    _c("td", { attrs: { colspan: "2" } }, [
                      _vm._v("GRAND TOTAL")
                    ]),
                    _vm._v(" "),
                    _c("td", [_vm._v("$6,500.00")])
                  ])
                ])
              ]
            ),
            _vm._v(" "),
            _c("div", { staticClass: "thanks" }, [_vm._v("Thank you!")]),
            _vm._v(" "),
            _c("div", { staticClass: "notices" }, [
              _c("div", [_vm._v("NOTICE:")]),
              _vm._v(" "),
              _c("div", { staticClass: "notice" }, [
                _vm._v(
                  "A finance charge of 1.5% will be made on unpaid balances after 30 days."
                )
              ])
            ])
          ]),
          _vm._v(" "),
          _c("footer", [
            _vm._v(
              "\n                Invoice was created on a computer and is valid without the signature and seal.\n            "
            )
          ])
        ]),
        _vm._v(" "),
        _c("div")
      ])
    ])
  }
]
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

/***/ }),
/* 12 */,
/* 13 */,
/* 14 */,
/* 15 */
/***/ (function(module, exports) {

module.exports = "/images/logo.jpg?af859fef05a0c373260bdd7916070d7b";

/***/ })
/******/ ]);