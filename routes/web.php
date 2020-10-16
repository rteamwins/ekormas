<?php

use Illuminate\Support\Facades\Route;

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
Auth::routes();

Route::group(['middleware' => ['verifyRegPayment']], function () {
  Route::get('/', 'HomeController@index')->name('home');
  Route::group(['middleware' => ['auth']], function () {
  Route::get('/user/dashboard', 'HomeController@user_dashboard')->name('user_home');
  Route::get('/user/wallet/fund/create', 'FundingController@create')->name('user_fund_wallet');
  Route::post('/user/wallet/fund/save', 'FundingController@store')->name('user_fund_wallet_save');
  Route::get('/user/wallet/fund/history', 'FundingController@index')->name('user_fund_history');
  Route::get('/user/trade/create', 'TradeController@create')->name('user_create_trade');
  Route::post('/user/trade/save', 'TradeController@store')->name('user_trade_save');
  Route::get('/user/trade/history', 'TradeController@index')->name('user_trade_history');
  });

  // Route::post('/default_reg', 'HomeController@register')->name('default_register');
});
Route::post('/process_registration_plan', 'HomeController@process_reg_plan')->name('process_reg_plan');
Route::get('/choose_registration_plan', 'HomeController@choose_reg_plan')->name('choose_reg_plan');
