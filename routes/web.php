<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\SetupController;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
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

Route::get('/home', [HomeController::class, 'index'])->name('home');

/* 
 * We name this route xero.auth.success as by default the config looks for a route with this name to redirect back to
 * after authentication has succeeded. The name of this route can be changed in the config file.
 */
Route::get('/manage/xero', [\App\Http\Controllers\Api\XeroController::class, 'index'])->name('xero.auth.success');

Route::get('/setup', [SetupController::class, 'index'])->name('Setup');
Route::get('/step_one', [SetupController::class, 'createStepOne'])->name('StepOne');
Route::post('/step_one', [SetupController::class, 'postCreateStepOne'])->name('PostStepOne');

Route::get('/step_two', [SetupController::class, 'createStepTwo'])->name('StepTwo');
Route::post('/step_two', [SetupController::class, 'postcreateStepTwo'])->name('PostStepTwo');

Route::get('/fileupload', [InvoiceController::class, 'index'])->name("upload");
Route::post('/fileupload', [InvoiceController::class, 'saveFile'])->name("saveFile");
Route::get('/createInvoice', [InvoiceController::class, 'createInvoice'])->name("createInvoice");

Route::get('/test', [InvoiceController::class, 'testMethod'])->name("testMethod");




Route::get('/reset', function(Request $request) {
    $request->session()->forget('client_id');
    $request->session()->forget('project_id');
    return redirect()->route('StepOne');
})->name('reset');

Auth::routes();
