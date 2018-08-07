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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('coin/get_near_coins', 'AdminController@get_near_coins');
Route::post('coin/get_coin', 'AdminController@get_coin');
Route::post('coin/withdraw_coin', 'AdminController@withdraw_coin');