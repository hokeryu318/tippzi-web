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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', 'AdminController@scatter_coin');

Route::get('admin/bar/csvupload', 'AdminController@csvupload');
Route::get('admin/bar/manual', 'AdminController@manual');
Route::get('admin/coin/scatter', 'AdminController@scatter_coin');
Route::post('coin/scatter_post',  'AdminController@scatter_coin_post');

Route::get('coin/get_near_coins', 'AdminController@get_near_coins');