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

Route::get('/users_list', 'Api\RegisterController@usersListGet');
Route::post('/save_reg_accountant', 'Api\RegisterController@saveRegAccountantPost')->name('save_reg_accountant');
Route::post('/save_reg_std', 'Api\RegisterController@saveRegStdPost')->name('save_reg_std');

Route::post('/get_monthly_collection', 'Api\HomeController@getMonthlyCollection')->name('get_monthly_collection');
Route::post('/get_net_collection', 'Api\HomeController@getNetCollection')->name('get_net_collection');
Route::post('/get_expenses', 'Api\HomeController@getOperationalExpenses')->name('get_expenses');
Route::post('/get_employee_costs_expenses', 'Api\HomeController@getEmployeeCostsExpenses')->name('get_employee_costs_expenses');
