<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProfileController;

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
Route::middleware(['guest'])->group(function () {
Route::get('/', function () { return view('welcome');})->name('login');
Route::post('/authenticate',[UserController::class,'login'])->name('authenticate');
});

Route::middleware(['auth'])->group(function () {
    /*
     * Dashboard Route Menu
     */
    Route::get('/home',[HomeController::class,'index']);
    Route::post('/logout',[UserController::class,'logout'])->name('logout');

    /*
     * Component Route Menu
     */
    Route::get('/component',[HomeController::class,'components']);

    /*
     * Activity Route Menu
     */
    Route::get('/activity',[ActivityController::class,'index']);
    Route::get('/activity/search',[ActivityController::class,'search']);

    /*
     * users Route Menu
     */
    Route::get('/users',[UserController::class,'index']);
    Route::post('/users/login-as/{id}',[UserController::class,'loginAs']);
    Route::get('/users/add',[UserController::class,'add']);
    Route::get('/users/edit/{id}',[UserController::class,'edit']);
    Route::post('/users/edit/{id}',[UserController::class,'saveEdit']);
    Route::post('/user/add/import', [UserController::class,'importExcel'])->name('user.import');
    Route::get('/searchuser', [UserController::class,'search']);
    Route::get('/users/add/template', [UserController::class,'templatetExcel']);
    Route::post('/users/delete/{id}',[UserController::class,'destroy']);

    /*
     * permissions Route Menu
     */
    Route::get('/permissions',[PermissionsController::class,'index']);
    Route::get('/permissions/{id}',[PermissionsController::class,'rolePermissions']);
    Route::get('/permissions/add/roles',[PermissionsController::class,'addRoles'])->name('addRoles');
    Route::post('/permissions/add/roles',[PermissionsController::class,'storeRoles'])->name('permissions.storeRoles');
    Route::post('/changePermission', [PermissionsController::class,'changePermission'])->name('changePermission');
    Route::post('/changeRoles', [PermissionsController::class,'changeRoles'])->name('changeRoles');


    /*
     * profile Route Menu
     */

     Route::get('/profile',[ProfileController::class,'index']);


});







