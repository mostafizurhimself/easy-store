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
module.exports = __webpack_require__(2);


/***/ }),
/* 1 */
/***/ (function(module, exports) {

Nova.booting(function (Vue, router, store) {

    router.beforeEach(function (to, from, next) {

        // Fabrics Section
        if (to.params.resourceName === 'fabric-purchase-orders' && to.params.lens === 'purchase-items') {
            router.push({ 'name': 'index', params: { resourceName: 'fabric-purchase-items' } });

            return;
        }

        if (to.params.resourceName === 'fabric-purchase-orders' && to.params.lens === 'receive-items') {
            router.push({ 'name': 'index', params: { resourceName: 'fabric-receive-items' } });

            return;
        }

        if (to.params.resourceName === 'fabric-return-invoices' && to.params.lens === 'return-items') {
            router.push({ 'name': 'index', params: { resourceName: 'fabric-return-items' } });

            return;
        }

        // Material Section
        if (to.params.resourceName === 'material-purchase-orders' && to.params.lens === 'purchase-items') {
            router.push({ 'name': 'index', params: { resourceName: 'material-purchase-items' } });

            return;
        }

        if (to.params.resourceName === 'material-purchase-orders' && to.params.lens === 'receive-items') {
            router.push({ 'name': 'index', params: { resourceName: 'material-receive-items' } });

            return;
        }

        if (to.params.resourceName === 'material-return-invoices' && to.params.lens === 'return-items') {
            router.push({ 'name': 'index', params: { resourceName: 'material-return-items' } });

            return;
        }

        // Asset Section
        if (to.params.resourceName === 'assets-purchase-orders' && to.params.lens === 'purchase-items') {
            router.push({ 'name': 'index', params: { resourceName: 'assets-purchase-items' } });

            return;
        }

        if (to.params.resourceName === 'assets-purchase-orders' && to.params.lens === 'receive-items') {
            router.push({ 'name': 'index', params: { resourceName: 'assets-receive-items' } });

            return;
        }

        if (to.params.resourceName === 'asset-return-invoices' && to.params.lens === 'return-items') {
            router.push({ 'name': 'index', params: { resourceName: 'asset-return-items' } });

            return;
        }

        if (to.params.resourceName === 'asset-requisitions' && to.params.lens === 'requisition-items') {
            router.push({ 'name': 'index', params: { resourceName: 'asset-requisition-items' } });

            return;
        }

        if (to.params.resourceName === 'asset-distribution-invoices' && to.params.lens === 'distribution-items') {
            router.push({ 'name': 'index', params: { resourceName: 'asset-distribution-items' } });

            return;
        }

        //Service Seciton
        if (to.params.resourceName === 'service-invoices' && to.params.lens === 'dispatch-items') {
            router.push({ 'name': 'index', params: { resourceName: 'service-dispatches' } });

            return;
        }

        if (to.params.resourceName === 'service-invoices' && to.params.lens === 'receive-items') {
            router.push({ 'name': 'index', params: { resourceName: 'service-receives' } });

            return;
        }

        // Product Section

        if (to.params.resourceName === 'product-requisitions' && to.params.lens === 'requisition-items') {
            router.push({ 'name': 'index', params: { resourceName: 'product-requisition-items' } });

            return;
        }

        next();
    });
}); // Nova Asset JS

/***/ }),
/* 2 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);