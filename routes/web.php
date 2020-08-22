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

    Route::get('locations/{location}/departments', 'AjaxController@departmentsViaLocation');
    Route::get('locations/{location}/floors', 'AjaxController@floorsViaLocation');
    Route::get('departments/{department}/sections', 'AjaxController@sectionsViaDepartment');
    Route::get('locations/{location}/designations', 'AjaxController@designationsViaLocation');
    Route::get('locations/{location}/employees', 'AjaxController@employeesViaLocation');
    Route::get('locations/{location}/fabric-categories', 'AjaxController@fabricCategoriesViaLocation');
    Route::get('locations/{location}/fabrics', 'AjaxController@fabricsViaLocation');
    Route::get('locations/{location}/material-categories', 'AjaxController@materialCategoiesViaLocation');
    Route::get('locations/{location}/materials', 'AjaxController@materialsViaLocation');
    Route::get('locations/{location}/asset-categories', 'AjaxController@assetCategoriesViaLocation');
    Route::get('locations/{location}/assets', 'AjaxController@assetsViaLocation');
    Route::get('locations/{location}/service-categories', 'AjaxController@serviceCategoriesViaLocation');
    Route::get('locations/{location}/services', 'AjaxController@servicesViaLocation');
    Route::get('locations/{location}/product-categories', 'AjaxController@productCategoriesViaLocation');
    Route::get('locations/{location}/products', 'AjaxController@productsViaLocation');

});
