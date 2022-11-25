<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\SetupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ApiAccessController;
use App\Http\Controllers\Admin\UserController;
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


Route::middleware(['auth','RestrictedUrl'])->group(function(){

    //Route::get('/{home?}', [HomeController::class, 'index'])->name('home');

    /* 
    * We name this route xero.auth.success as by default the config looks for a route with this name to redirect back to
    * after authentication has succeeded. The name of this route can be changed in the config file.
    */
    Route::get('/manage/xero', [\App\Http\Controllers\Api\XeroController::class, 'index'])->name('xero.auth.success');
    Route::get('/call/back', [ApiAccessController::class, 'index'])->name("callBackRedirect");
    Route::get('/revoke', [\App\Http\Controllers\Api\XeroController::class, 'revokeAccessToken'])->name('xero.auth.revoke');

    Route::get('/fileupload', [InvoiceController::class, 'index'])->name("upload");
    Route::post('/fileupload', [InvoiceController::class, 'saveFile'])->name("saveFile");
    Route::get('/importView', [InvoiceController::class, 'importView'])->name("importView");
    Route::get('/createInvoice', [InvoiceController::class, 'createInvoice'])->name("createInvoice");
    Route::get('/reupload', [InvoiceController::class, 'reupload'])->name("reupload");

    Route::get('/api_info', [ApiAccessController::class, 'apiInfo'])->name("apiInfo");
    Route::post('/api_info', [ApiAccessController::class, 'storeApiInfo'])->name("apiInfo");

    Route::get('/confirm_tenant', [ApiAccessController::class, 'confirmTenant'])->name("confirmTenant");
    Route::post('/confirm_tenant', [ApiAccessController::class, 'updateConfirmTenant'])->name("confirmTenant");
    Route::get('/coa', [ApiAccessController::class, 'getCOA'])->name("coa");
    Route::get('/tracking_categories', [ApiAccessController::class, 'getTrackingCategories'])->name("trackingCategories");
});


Route::middleware(['auth'])->group(function(){

    Route::get('/setup', [SetupController::class, 'index'])->name('Setup');

    Route::get('/step_one', [SetupController::class, 'createStepOne'])->name('StepOne');
    Route::post('/step_one', [SetupController::class, 'postCreateStepOne'])->name('PostStepOne');

    Route::get('/step_two', [SetupController::class, 'createStepTwo'])->name('StepTwo');
    Route::post('/step_two', [SetupController::class, 'postcreateStepTwo'])->name('PostStepTwo');

    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('userEdit');
    Route::post('/user/edit/{id}', [UserController::class, 'update'])->name('userUpdate');
    Route::post('/user/delete/{id}', [UserController::class, 'delete'])->name('userDelete');
    
});

Route::get('/', function () {
    return redirect('/setup');
});





Route::get('/test', [ApiAccessController::class, 'testMethod'])->name("testMethod");


Route::get('clear_cache', function () {
    \Artisan::call('optimize:clear');
    dd("clear cache");
});

Route::get('migrate', function () {
    \Artisan::call('migrate');
    dd("run migrations");
});

Route::get('db_wipe', function () {
    \Artisan::call('db:wipe');
    \Artisan::call('migrate');
    Artisan::call('db:seed', array('--class' => 'CreateDummyDataSeeder'));
    dd("run migrations");
});

Route::get('/reset', function(Request $request) {
    $request->session()->forget('client_id');
    $request->session()->forget('project_id');
    return redirect()->route('StepOne');
})->name('reset');

Auth::routes();
