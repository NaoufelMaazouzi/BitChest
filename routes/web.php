<?php

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

//Route de base
Route::get('/{path?}', function(){
    if (Auth::check()) {
        $user = Auth::user()->role;
        $userId = Auth::user()->id;
    } else {
        $user = Auth::user();
        $userId = null;
    }
    return view('layouts.master', ["userLoggedIn" => collect([
        'user' => $user ]), "userId" => collect([
            'userId' => $userId ])]); 
 })->where('path', '.*');

 Auth::routes();
