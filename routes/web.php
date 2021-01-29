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

// Auth::routes();
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register/ref/{ref_code}/pos/{placement_id}', 'Auth\RegisterController@showRegistrationForm')->name('register')->where(['ref_code' => '[A-Za-z0-9_-]+', 'placement_id' => '[0-9]+']);
Route::post('register/new', 'Auth\RegisterController@register')->name('register_save');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/about-us', 'HomeController@about_us')->name('about_us');
Route::get('/faq', 'HomeController@faq')->name('faq');
Route::get('/terms-and-conditions', 'HomeController@tac')->name('tac');
Route::get('/disclaimer', 'HomeController@disclaimer')->name('disclaimer');
Route::get('/store', 'ProductController@store_index')->name('product_store');

Route::group(['middleware' => ['verifyRegPayment']], function () {
  Route::get('/', 'HomeController@index')->name('home');
  Route::group(['prefix' => 'user', 'middleware' => ['auth']], function () {
    Route::get('/dashboard', 'HomeController@user_dashboard')->name('user_home');
    Route::get('/order', 'OrderController@index')->name('order_list');
    Route::get('/order/{order_code}/payment-failed', 'OrderController@payment_failed')->name('order_payment_failed');
    Route::get('/order/{order_code}/payment-success', 'OrderController@payment_success')->name('order_payment_success');

    Route::group(['prefix' => 'wallet'], function () {
      Route::group(['prefix' => 'fund'], function () {
        Route::get('create', 'FundingController@create')->name('user_fund_wallet');
        Route::post('save', 'FundingController@store')->name('user_fund_wallet_save');
        Route::get('history', 'FundingController@index')->name('user_fund_history');
        Route::get('{amount}/payment-failed', 'FundingController@payment_failed')->name('user_fund_payment_failed');
        Route::get('{amount}/payment-success', 'FundingController@payment_success')->name('user_fund_payment_success');
      });
    });

    Route::group(['prefix' => 'withdraw'], function () {
      Route::group(['prefix' => 'fund'], function () {
        Route::get('/', 'WithdrawController@create')->name('user_create_withdraw_fund');
        Route::post('save', 'WithdrawController@store')->name('user_create_withdraw_fund_save');
        Route::get('history', 'WithdrawController@index')->name('user_withdraw_fund_history');
      });
      Route::get('local/history', 'LocalPayController@index')->name('user_withdraw_local_history');
    });

    Route::get('/local-pay/list/request', 'LocalPayController@index_request')->name('local_pay_requests');

    Route::get('/trade/create', 'TradeController@create')->name('user_create_trade');
    Route::post('/trade/save', 'TradeController@store')->name('user_trade_save');
    Route::get('/trade/history', 'TradeController@index')->name('user_trade_history');


    Route::group(['prefix' => 'bonus'], function () {
      Route::group(['prefix' => 'convert_to'], function () {
        Route::get('wallet', 'BonusController@convert_to_wallet_funds')->name('create_bonus_to_wallet_funds');
        Route::post('wallet/save', 'BonusController@store_convert_to_wallet_funds')->name('save_bonus_to_wallet_funds');
      });
      Route::get('history', 'BonusController@index')->name('user_bonus_history');
    });

    Route::group(['prefix' => 'point'], function () {
      Route::group(['prefix' => 'convert_to'], function () {
        Route::get('wallet', 'PointController@convert_points_to_wallet_funds')->name('create_point_to_wallet_funds');
        Route::post('wallet/save', 'PointController@store_convert_points_to_wallet_funds')->name('save_point_to_wallet_funds');
      });
      Route::get('history', 'PointController@index')->name('user_point_history');
      Route::get('list/nominee', 'PointController@index_nominees')->name('admin_list_point_nominees');
    });

    Route::get('/kyc/list', 'KYCController@index')->name('user_list_kyc');
    Route::get('/referal/history', 'HomeController@show_referal')->name('user_referal_history');

    Route::group(['prefix' => 'registration_credit'], function () {
      Route::get('purchase', 'RegistrationCreditPurchaseController@create')->name('user_purchase_registration_credits');
      Route::post('process', 'RegistrationCreditPurchaseController@store')->name('user_store_purchase_registration_credits');
      Route::get('list', 'RegistrationCreditController@index')->name('user_list_registration_credits');
      Route::get('/purchase/list', 'RegistrationCreditPurchaseController@index')->name('user_list_purchase_registration_credits');
      Route::get('{plan}/{quantity}/payment-failed', 'RegistrationCreditPurchaseController@payment_failed')->name('user_registration_credit_payment_failed');
      Route::get('{plan}/{quantity}/payment-success', 'RegistrationCreditPurchaseController@payment_success')->name('user_registration_credit_payment_success');
    });

    Route::get('agent/application/list', 'AgentApplicationController@index')->name('agent_application_list');
    Route::get('agent/application/new', 'AgentApplicationController@create')->name('agent_application_form');
    Route::post('agent/application/store', 'AgentApplicationController@store')->name('agent_application_form_store');
  });
  Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'AdminController@index')->name('admin_home');
    Route::get('/agent/list/available', 'HomeController@avail_agents')->name('admin_list_avail_agents');
    Route::get('/agent/list/potential', 'HomeController@potential_agents')->name('admin_list_potential_agents');
    Route::get('/investor/list/active', 'HomeController@active_users')->name('admin_list_active_users');
    Route::get('/investor/list/potential', 'HomeController@non_active_users')->name('admin_list_non_active_users');

    Route::get('/order', 'OrderController@admin_index')->name('admin_order_request_list');

    Route::group(['prefix' => 'post'], function () {
      Route::post('/process_new_post', 'PostController@store')->name('process_new_post');
      Route::get('/create', 'PostController@create')->name('create_post');
      Route::get('/list', 'PostController@index')->name('list_post');
      Route::get('/show/{id}', 'PostController@show')->name('show_post');
      Route::get('/delete/{id}', 'PostController@destroy')->name('delete_post');
      Route::get('/delete_image/{id}', 'PostController@delete_post_image')->name('delete_post_image');
      Route::get('/edit/{id}', 'PostController@edit')->name('edit_post');
      Route::post('/update/{id}', 'PostController@update')->name('update_post');
    });

    Route::group(['prefix' => 'product'], function () {
      Route::get('/create', 'ProductController@create')->name('create_product');
      Route::post('/process_new_product', 'ProductController@store')->name('process_new_product');
      Route::get('/list', 'ProductController@index')->name('list_product');
      Route::get('/edit/{id}', 'ProductController@edit')->name('edit_product');
      Route::post('/update/{id}', 'ProductController@update')->name('update_product');
    });
  });
});

Route::post('/process_registration_plan', 'HomeController@process_reg_plan')->name('process_reg_plan');
Route::get('/choose_registration_plan', 'HomeController@choose_reg_plan')->name('choose_reg_plan');
Route::get('/update_registration_plan', 'HomeController@update_reg_plan')->name('update_reg_plan');
Route::get('/registration_plan/{plan}/payment-failed', 'HomeController@payment_failed')->name('reg_plan_payment_failed');
Route::get('/registration_plan/{plan}/payment-success', 'HomeController@payment_success')->name('reg_plan_payment_success');


Route::get('/tester', 'MarketTickerController@index');


Route::get('/alert/list', 'AlertController@index')->name('admin_list_alert');
Route::get('/category/list', 'CategoryController@index')->name('admin_list_category');
