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
    //Fabrics Section
    router.beforeEach(function (to, from, next) {
        if (to.path === "/resources/fabric-purchase-items/new" && (to.query.viaResource == null || to.query.viaResourceId == null)) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach(function (to, from, next) {
        if (to.path === "/resources/fabric-receive-items/new" && (to.query.viaResource == null || to.query.viaResourceId == null)) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach(function (to, from, next) {
        if (to.path === "/resources/fabric-return-items/new" && (to.query.viaResource == null || to.query.viaResourceId == null)) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    //Material Section
    router.beforeEach(function (to, from, next) {
        if (to.path === "/resources/material-purchase-items/new" && (to.query.viaResource == null || to.query.viaResourceId == null)) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach(function (to, from, next) {
        if (to.path === "/resources/material-receive-items/new" && (to.query.viaResource == null || to.query.viaResourceId == null)) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach(function (to, from, next) {
        if (to.path === "/resources/material-return-items/new" && (to.query.viaResource == null || to.query.viaResourceId == null)) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    //Asset Section
    router.beforeEach(function (to, from, next) {
        if (to.path === "/resources/asset-purchase-items/new" && (to.query.viaResource == null || to.query.viaResourceId == null)) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach(function (to, from, next) {
        if (to.path === "/resources/asset-receive-items/new" && (to.query.viaResource == null || to.query.viaResourceId == null)) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach(function (to, from, next) {
        if (to.path === "/resources/asset-return-items/new" && (to.query.viaResource == null || to.query.viaResourceId == null)) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach(function (to, from, next) {
        if (to.path === "/resources/asset-requisition-items/new" && (to.query.viaResource == null || to.query.viaResourceId == null)) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach(function (to, from, next) {
        if (to.path === "/resources/asset-distribution-items/new" && (to.query.viaResource == null || to.query.viaResourceId == null)) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach(function (to, from, next) {
        if (to.path === "/resources/asset-distribution-receive-items/new" && (to.query.viaResource == null || to.query.viaResourceId == null)) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    //Service Section
    router.beforeEach(function (to, from, next) {
        if (to.path === "/resources/service-dispatches/new" && (to.query.viaResource == null || to.query.viaResourceId == null)) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach(function (to, from, next) {
        if (to.path === "/resources/service-receives/new" && (to.query.viaResource == null || to.query.viaResourceId == null)) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach(function (to, from, next) {
        if (to.path === "/resources/service-transfer-items/new" && (to.query.viaResource == null || to.query.viaResourceId == null)) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach(function (to, from, next) {
        if (to.path === "/resources/service-transfer-receive-items/new" && (to.query.viaResource == null || to.query.viaResourceId == null)) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    //Finishing Section
    router.beforeEach(function (to, from, next) {
        if (to.path === "/resources/finishings/new" && (to.query.viaResource == null || to.query.viaResourceId == null)) {
            router.push({ name: "403" });

            return;
        }

        next();
    });
});

/***/ }),
/* 2 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);