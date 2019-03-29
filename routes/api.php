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

// books
Route::get('book/{id}','BookController@show');

Route::get('book','BookController@index');


// auth
Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');

//protected
Route::middleware('TokenAuthentication')->group(function () {
    Route::post('borrow','BorrowController@store');
    Route::get('borrow/{id}','BorrowController@show');
    Route::put('borrow/{id}','BorrowController@update');
    Route::get('borrows/user','BorrowController@allUserBorrows');
    Route::get('borrows/user/{borrowId}','BorrowController@singleUserBorrow');
    Route::put('borrows/user/{borrowId}','BorrowController@returnBook');
  });

//admin
Route::group(['middleware' => ['isAdmin']], function () {
    //
    Route::get('borrow', 'BorrowController@index');
    Route::post('book','BookController@store');
    Route::delete('book/{id}','BookController@destroy');
    Route::put('book/{id}','BookController@update');
});

