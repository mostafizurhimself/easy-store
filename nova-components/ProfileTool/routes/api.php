<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Easystore\ProfileTool\Http\Controllers\ToolController;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

// Route::get('/endpoint', function (Request $request) {
//     //
// });

Route::get('/', ToolController::class . '@index');
Route::post('/', ToolController::class . '@store');
