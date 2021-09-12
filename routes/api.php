<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Route::get('products', 'ProductController@index');
// Route::get('/userLoggedIn', function (Request $request) {
//     var_dump(Auth::user());
//     if (Auth::check()) {
//         return 'okkk';
//     } else {
//         return 'noon';
//     }
// });

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('/cryptoList', 'CryptoController');
Route::resource('/purchase', 'PurchaseController');
Route::get('purchase/{id}', 'PurchaseController@show')->where(['id' => '[0-9]+']);
Route::post('deletePurchase/{id}', 'PurchaseController@destroy')->where(['id' => '[0-9]+']);
Route::get('user/{id}', 'UserController@show')->where(['id' => '[0-9]+']);
Route::delete('user/{id}', 'UserController@destroy')->where(['id' => '[0-9]+']);
Route::patch('user/{id}', 'UserController@update')->where(['id' => '[0-9]+']);

Route::group(['middleware' => ['auth', 'admin']], function() {
    Route::resource('/admin/users', 'UserController');
  });
  



// Route::group(['middleware' => 'auth:api'], function()
// {
    // Route::resource('/admin/users', 'UserController');
// });
// Route::middleware('auth:api')->resource('/admin/users', 'UserController');
