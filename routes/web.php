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
Route::get('/', 'FrontController@index');

//Route pour afficher des produits soldés
Route::get('/soldes', 'FrontController@showProductSoldes');

//Route pour afficher des produits en fonction de la catégorie, route sécurisée
Route::get('category/{id}', 'FrontController@showProductByCategory')->where(['id' => '[0-9]+']);

//Route pour afficher un produit spécifique, route sécurisée
Route::get('product/{id}', 'FrontController@show')->where(['id' => '[0-9]+']);

//Créer toutes les routes admin pour les produits et catégories
Route::resource('/admin/products', 'ProductController')->middleware('auth');
Route::resource('/admin/categories', 'CategoryController')->middleware('auth');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
