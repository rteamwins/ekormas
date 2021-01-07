<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->group(function () {

  Route::get('user/kyc/list', 'KYCController@index_json');

  Route::get('user/referal/tree_data/for/{id}', 'HomeController@get_ref_level')->where(['id' => '[0-9]+']);

  Route::get('alert/list', 'AlertController@index_json');
  Route::get('alert/enable/{id}', 'AlertController@enable')->where(['id' => '[0-9]+']);
  Route::get('alert/disable/{id}', 'AlertController@disable')->where(['id' => '[0-9]+']);
  Route::get('alert/delete/{id}', 'AlertController@destroy')->where(['id' => '[0-9]+']);
  Route::post('alert/update/{id}', 'AlertController@update')->where(['id' => '[0-9]+']);
  Route::post('alert/new', 'AlertController@store');
});
