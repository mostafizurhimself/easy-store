<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Ajax Routes

Route::group(['middleware' => 'nova'], function () {

    Route::get('locations/{location}/floors', 'AjaxController@floorsViaLocation');
    Route::get('locations/{location}/styles', 'AjaxController@stylesViaLocation');
    Route::get('locations/{location}/departments', 'AjaxController@departmentsViaLocation');
    Route::get('departments/{department}/sections', 'AjaxController@sectionsViaDepartment');
    Route::get('locations/{location}/sections', 'AjaxController@sectionsViaLocation');
    Route::get('floors/{floor}/sections', 'AjaxController@sectionsViaFloor');
    Route::get('sections/{section}/sub-sections', 'AjaxController@subSectionsViaSection');
    Route::get('locations/{location}/designations', 'AjaxController@designationsViaLocation');
    Route::get('locations/{location}/employees', 'AjaxController@employeesViaLocation');
    Route::get('locations/{location}/fabric-categories', 'AjaxController@fabricCategoriesViaLocation');
    Route::get('locations/{location}/fabrics', 'AjaxController@fabricsViaLocation');
    Route::get('locations/{location}/material-categories', 'AjaxController@materialCategoiesViaLocation');
    Route::get('locations/{location}/materials', 'AjaxController@materialsViaLocation');
    Route::get('locations/{location}/asset-categories', 'AjaxController@assetCategoriesViaLocation');
    Route::get('locations/{location}/asset-requisitions', 'AjaxController@assetRequisitionsViaLocation');
    Route::get('locations/{location}/requisitions', 'AjaxController@requisitionsViaLocation');
    Route::get('locations/{location}/service-categories', 'AjaxController@serviceCategoriesViaLocation');
    Route::get('locations/{location}/services', 'AjaxController@servicesViaLocation');
    Route::get('locations/{location}/product-categories', 'AjaxController@productCategoriesViaLocation');
    Route::get('locations/{location}/products', 'AjaxController@productsViaLocation');
    Route::get('locations/{location}/expensers', 'AjaxController@expensersViaLocation');
    Route::get('locations/{location}/expense-categories', 'AjaxController@expenseCategoriesViaLocation');

    // Invoice Routes
    Route::group(['prefix' => 'invoices'], function () {
        Route::get('asset-distributions/{invoice}', 'InvoiceController@assetDistributionInvoice')->name('invoices.asset-distributions');
        Route::get('services/{invoice}', 'InvoiceController@serviceInvoice')->name('invoices.services');
        Route::get('fabric-returns/{invoice}', 'InvoiceController@fabricReturnInvoice')->name('invoices.fabric-returns');
        Route::get('material-returns/{invoice}', 'InvoiceController@materialReturnInvoice')->name('invoices.material-returns');
        Route::get('asset-returns/{invoice}', 'InvoiceController@assetReturnInvoice')->name('invoices.asset-returns');
    });

    // Requisitions
    Route::group(['prefix' => 'requisitions'], function () {
        Route::get('assets/{requisition}', 'RequisitionController@assetRequisition')->name('requisitions.assets');
        Route::get('products/{requisition}', 'RequisitionController@productRequisition')->name('requisitions.products');
    });

    // Invoice Routes
    Route::group(['prefix' => 'purchase-orders'], function () {
        Route::get('fabrics/{purchaseOrder}', 'PurchaseOrderController@fabricPurchaseOrder')->name('purchase-orders.fabrics');
        Route::get('materials/{purchaseOrder}', 'PurchaseOrderController@materialPurchaseOrder')->name('purchase-orders.materials');
        Route::get('assets/{purchaseOrder}', 'PurchaseOrderController@assetPurchaseOrder')->name('purchase-orders.assets');
    });

    // Helper Controller
    Route::get('dump-download/{filename}', "HelperController@dumpDownload")->name('dump-download');
    Route::get('test', function(){
        $models = \App\Models\FabricReceiveItem::all();

        return view('pdf.pages.fabric-receive-items', compact('models'));
    });


});
