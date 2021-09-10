<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;

use App\Http\Middleware\AdminCheck;

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
//
Route::prefix('app')->middleware([AdminCheck::class])->group(function(){
    Route::post('/create_crypto', 'App\Http\Controllers\AdminController@addCrypto');
    Route::get('/get_crypto', 'App\Http\Controllers\AdminController@getCrypto');
    Route::post('/edit_crypto', 'App\Http\Controllers\AdminController@editCrypto');
    Route::post('/delete_crypto', 'App\Http\Controllers\AdminController@deleteCrypto');
    Route::post('/upload', 'App\Http\Controllers\AdminController@upload');
    Route::post('/delete_image', 'App\Http\Controllers\AdminController@deleteImage');
    Route::post('/create_category', 'App\Http\Controllers\AdminController@addCategory');
    Route::get('/get_category', 'App\Http\Controllers\AdminController@getCategory');
    Route::post('/edit_category', 'App\Http\Controllers\AdminController@editCategory');
    Route::post('/delete_category', 'App\Http\Controllers\AdminController@deleteCategory');
    Route::post('/create_user', 'App\Http\Controllers\AdminController@createUser');
    Route::get('/get_user', 'App\Http\Controllers\AdminController@getUser');
    Route::post('/edit_user', 'App\Http\Controllers\AdminController@editUser');
    Route::post('/admin_login', 'App\Http\Controllers\AdminController@adminLogin');


    //Roles routes
    Route::post('/create_role', 'App\Http\Controllers\AdminController@addRole');
    Route::get('/get_roles', 'App\Http\Controllers\AdminController@getRoles');
    Route::post('/edit_role', 'App\Http\Controllers\AdminController@editRole');
    Route::post('/assign_roles', 'App\Http\Controllers\AdminController@assignRoles');

    //Achat Action
    Route::post('/set_action', 'App\Http\Controllers\AdminController@setAction');
    Route::get('/get_action', 'App\Http\Controllers\AdminController@getAction');
    Route::post('/update_wallet', 'App\Http\Controllers\AdminController@updateWallet');


});




Route::get('/logout', [AdminController::class, 'logout']);
Route::get('/', [AdminController::class, 'index']);
Route::get('{slug}', [AdminController::class, 'index']);

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('{slug}', function(){
//     return view('welcome');
// });
