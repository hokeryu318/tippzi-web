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

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();
Route::get('/', 'IndexController@index')->name('index');
Route::get('/logout', 'Auth\LoginController@logout')->name('user.logout');

Route::get('/admin', 'AdminController@scatter_coin');

Route::get('admin/bar/csvupload', 'AdminController@csvupload');
Route::get('admin/bar/manual', 'AdminController@manual');
Route::get('admin/coin/scatter', 'AdminController@scatter_coin');
Route::post('coin/scatter_post',  'AdminController@scatter_coin_post');


// Web Front end for input business data
Route::get('/dashboard', 'UserController@index')->name('user.dashboard')->middleware('auth');
Route::get('/settings', 'UserController@settings')->name('user.settings')->middleware('auth');
Route::post('/changeEmail', 'UserController@changeEmail')->name('user.change.email')->middleware('auth');
Route::post('/changePassword', 'UserController@changePassword')->name('user.change.password')->middleware('auth');
Route::post('/changeLoginname', 'UserController@changeLoginname')->name('user.change.loginname')->middleware('auth');
//Route::get('/stage1', 'BusinessInsertController@stage1');
//Route::get('/stage2', 'BusinessInsertController@stage2');
//Route::get('/stage3', 'BusinessInsertController@stage3');
//Route::get('/stage4', 'BusinessInsertController@stage4');
//Route::get('/business_insert', 'BusinessInsertController@business_insert');

// API connect part
// for login
//Route::get('/login', 'Auth\LoginController@showLoginForm');
//Route::post('/login', 'Auth\LoginController@login')->name('login.post');

// for business register
Route::get('/create', 'BarRegisterController@create')->name('bar.create');
Route::post('/store1', 'BarRegisterController@store1')->name('bar.store1');
Route::post('/store2', 'BarRegisterController@store2')->name('bar.store2');
Route::post('/store3', 'BarRegisterController@store3')->name('bar.store3');
Route::post('/store4', 'BarRegisterController@store4')->name('bar.store4');

Route::get('/admin/create', 'BarRegisterController@adminCreate')->name('bar.admin.create');
Route::post('/admin/create/login', 'UserController@checkAdminLogin')->name('admin.create.login');
// for edit profile
Route::get('/edit', 'BarRegisterController@edit')->name('bar.edit')->middleware('auth');
Route::post('/update1', 'BarRegisterController@update1')->name('bar.update1')->middleware('auth');
Route::post('/update2', 'BarRegisterController@update2')->name('bar.update2')->middleware('auth');
Route::post('/update3', 'BarRegisterController@update3')->name('bar.update3')->middleware('auth');
Route::post('/update4', 'BarRegisterController@update4')->name('bar.update4')->middleware('auth');

Route::get('/agreement', 'UserController@agreement')->name('agreement');
Route::get('/cookie-policy', 'UserController@cookiePolicy')->name('cookie-policy');
Route::get('/terms-conditions-suppliers', 'UserController@tcSuppliers')->name('terms-conditions-suppliers');
Route::get('/terms-of-services', 'UserController@terms')->name('terms');

// for deal
Route::get('/deal/create', 'DealController@create')->name('deal.create')->middleware('auth');
Route::get('/deal/edit', 'DealController@edit')->name('deal.edit')->middleware('auth');
Route::post('/deal/save', 'DealController@save')->name('deal.save')->middleware('auth');

Route::post('/ajax/upload', 'AjaxUploadController@upload')->name('ajax.upload');
//Route::get('/stage1', 'BarRegisterController@stage1')->name('stage1.get');
//Route::post('/stage2', 'BarRegisterController@stage1_register')->name('bar_register.stage1');
//
//Route::get('/stage2', 'BarRegisterController@stage2')->name('stage2.get');
//Route::post('/stage3', 'BarRegisterController@stage2_register')->name('bar_register.stage2');
//
//Route::get('/stage3', 'BarRegisterController@stage3')->name('stage3.get');
//Route::post('/stage4', 'BarRegisterController@stage3_register')->name('bar_register.stage3');
//
//Route::get('/stage4', 'BarRegisterController@stage4')->name('stage4.get');
//Route::post('/dashboard', 'BarRegisterController@stage4_register')->name('bar_register.stage4');
//
//// previous part
//Route::post('/', 'StageViewController@stage1_previous')->name('stage1.previous');
//Route::post('/to_stage1', 'StageViewController@stage2_previous')->name('stage2.previous');
//Route::post('/to_stage2', 'StageViewController@stage3_previous')->name('stage3.previous');
//Route::post('/to_stage3', 'StageViewController@stage4_previous')->name('stage4.previous');
