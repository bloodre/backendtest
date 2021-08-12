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


/*
  |--------------------------------------------------------------------------
  | API calls used for tabular data in backend
  |--------------------------------------------------------------------------
 */

// Below are the routes accessible only by admin
Route::GROUP(['middleware' => ['auth:user']], function() {

    // Get User List
    Route::get('/admin/users/getUsersTabular', 'Api\ApiUsersController@getUsersTabular');
    Route::get('/companies/getCompaniesTabular', 'Api\ApiCompaniesController@getCompaniesTabular');
    Route::get('/companies/tempPicture/{username}', 'Api\ApiCompaniesController@tempPicture');
    Route::get('/companies/tempRefresh/{username}', 'Api\ApiCompaniesController@tempRefresh');
    Route::get('/postcodes/{postcode}','Api\ApiPostcodesController@getLocation');
});
