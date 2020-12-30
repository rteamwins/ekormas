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
  Route::group(['prefix' => 'user', 'middleware' => ['auth']], function () {
    Route::get('/dashboard', 'HomeController@user_dashboard')->name('user_home');

    Route::get('/wallet/fund/create', 'FundingController@create')->name('user_fund_wallet');
    Route::post('/wallet/fund/save', 'FundingController@store')->name('user_fund_wallet_save');
    Route::get('/wallet/fund/history', 'FundingController@index')->name('user_fund_history');

    Route::get('/withdraw/fund', 'WithdrawController@create')->name('user_create_withdraw_fund');
    Route::post('/withdraw/fund/save', 'WithdrawController@store')->name('user_create_withdraw_fund_save');
    Route::get('/withdraw/fund/history', 'WithdrawController@index')->name('user_withdraw_fund_history');

    Route::get('/trade/create', 'TradeController@create')->name('user_create_trade');
    Route::post('/trade/save', 'TradeController@store')->name('user_trade_save');
    Route::get('/trade/history', 'TradeController@index')->name('user_trade_history');

    Route::get('/bonus/convert_to/wallet', 'BonusController@convert_to_wallet_funds')->name('create_bonus_to_wallet_funds');
    Route::post('/bonus/convert_to/wallet/save', 'BonusController@store_convert_to_wallet_funds')->name('save_bonus_to_wallet_funds');
    Route::get('/bonus/history', 'BonusController@index')->name('user_bonus_history');

    Route::get('/kyc/list', 'KYCController@index')->name('user_list_kyc');
    Route::get('/referal/history', 'ReferalController@show')->name('user_referal_history');

    Route::get('/registration_credit/purchase', 'RegistrationCreditPurchaseController@create')->name('user_purchase_registration_credits');
  });
  Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'AdminController@index')->name('admin_home');
  });
});
Route::post('/process_registration_plan', 'HomeController@process_reg_plan')->name('process_reg_plan');
Route::get('/choose_registration_plan', 'HomeController@choose_reg_plan')->name('choose_reg_plan');
