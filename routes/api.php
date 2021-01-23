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

  Route::get('agent/application/list', 'AgentApplicationController@index_json');

  Route::get('user/kyc/list', 'KYCController@index_json');

  Route::get('user/referal/tree_data/for/{id}', 'HomeController@get_ref_level')->where(['id' => '[0-9]+']);

  Route::get('local-pay/request/list/open', 'LocalPayController@index_json');
  Route::post('local-pay/request/confirm/{id}', 'LocalPayController@confirm')->where(['id' => '[0-9]+']);;
  Route::get('local-pay/request/decline/{id}', 'LocalPayController@decline')->where(['id' => '[0-9]+']);;


  Route::get('alert/list', 'AlertController@index_json');
  Route::get('alert/enable/{id}', 'AlertController@enable')->where(['id' => '[0-9]+']);
  Route::get('alert/disable/{id}', 'AlertController@disable')->where(['id' => '[0-9]+']);
  Route::get('alert/delete/{id}', 'AlertController@destroy')->where(['id' => '[0-9]+']);
  Route::post('alert/update/{id}', 'AlertController@update')->where(['id' => '[0-9]+']);
  Route::post('alert/new', 'AlertController@store');

  Route::get('product/enable/{id}', 'ProductController@enable')->where(['id' => '[0-9]+']);
  Route::get('product/disable/{id}', 'ProductController@disable')->where(['id' => '[0-9]+']);
  Route::get('product/{id}/delete_image/{image_name}', 'ProductController@disable')->where(['id' => '[0-9]+']);
});
Route::get('product/list', 'ProductController@index_json');
