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





Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');

Route::middleware('TokenAuthentication')->group(function () {
    Route::post('book','BookController@store');
  });

Route::group(['middleware' => ['isAdmin']], function () {
    //
    Route::post('book','BookController@store');
    Route::delete('book/{id}','BookController@destroy');
    Route::put('book/{id}','BookController@update');
});

Route::get('book/{id}','BookController@show'); 

Route::get('book','BookController@index');