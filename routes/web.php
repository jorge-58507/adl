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
    return redirect('/login');
    // return view('welcome');
});

Auth::routes();
Route::get('/home', 'controller_vehicule@index')->name('home');
Route::get('/user/edit', 'controller_user@edit')->middleware('checklogin');
Route::get('/report', 'controller_report@index')->middleware('checklogin');
Route::get('/report/dashboard/{from}/{until}/{vehicule}', 'controller_report@edit')->middleware('checklogin');
Route::get('/register', 'controller_user@index')->middleware('checklogin');
Route::get('company/get_by_user/{user}', 'controller_company@get_company_by_user')->middleware('checklogin');
Route::get('user/get_by_company/{company_id}', 'controller_user@get_user_by_company')->middleware('checklogin');

Route::post('/report/{data}', 'controller_data@show_fromto')->middleware('checklogin');
Route::post('/user_company/delete', 'controller_user_company@delete')->middleware('checklogin');
Route::post('/data/ralenti', 'controller_data@store_ralenti')->middleware('checklogin');
// Route::post('/register', 'controller_user@store_company')->middleware('checklogin');

Route::delete('/data/{name}', 'controller_data@destroy')->middleware('checklogin');
// Route::delete('user/linkup','controller_user@destroy_userlinkup')->middleware('checklogin');

Route::put('/option/unit/update', 'controller_option@update_unit')->middleware('checklogin');

Route::resource('/data', 'controller_data',['middleware' => 'checklogin'])->only(['show', 'destroy','store']);
Route::resource('/vehicule', 'controller_vehicule',['middleware' => 'checklogin']);
Route::resource('/user', 'controller_user',['middleware' => 'checklogin']);
Route::resource('/company', 'controller_company',['middleware' => 'checklogin']);
Route::resource('/user_company', 'controller_user_company',['middleware' => 'checklogin']);
// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
