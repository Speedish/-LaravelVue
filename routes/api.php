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

Route::apiResources(['user'=>'API\UserController']);
Route::apiResources(['course'=>'API\CourseController']);
Route::apiResources(['enrollment'=>'API\EnrollmentController']);
Route::apiResources(['dashboard'=>'API\DashboardController']);
Route::get('profile', 'API\UserController@profile');

Route::post('login', 'API\UserController@login');


Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', 'API\UserController@details');
    });