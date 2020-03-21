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

Route::group(['namespace' => 'Authentications'], function () {
    Route::post('/authenticate', 'AuthenticationController@authenticate');
    Route::post('/register', 'AuthenticationController@register');
});

Route::group(['namespace' => 'LocationCtl'], function () {
    Route::get('/regions', 'RegionsController@getRegionsList');
    Route::get('/regions_paginated', 'RegionsController@getRegionsPaginated');
    Route::post('/region', 'RegionsController@createRegion');
    Route::patch('/region', 'RegionsController@updateRegion');
    Route::delete('/region/{id}', 'RegionsController@deleteRegion');
    //
    Route::get('/zones/{id}', 'ZoneController@getZonesList');
    Route::get('/zones_paginated/{id}', 'ZoneController@getZonesPaginated');
    Route::post('/zone', 'ZoneController@createZone');
    Route::patch('/zone', 'ZoneController@updateZone');
    Route::delete('/zone/{id}', 'ZoneController@deleteZone');

    Route::get('/weredas/{id}', 'WeredaController@getWeredasList');
    Route::get('/weredas_paginated/{id}', 'WeredaController@getWeredasPaginated');
    Route::post('/wereda', 'WeredaController@createWereda');
    Route::patch('/wereda', 'WeredaController@updateWereda');
    Route::delete('/wereda/{id}', 'WeredaController@deleteWereda');

    Route::get('/cities/{id}', 'CitiesController@getCitiesList');
    Route::get('/cities_paginated/{id}', 'CitiesController@getCitiesPaginated');
    Route::post('/city', 'CitiesController@createCity');
    Route::patch('/city', 'CitiesController@updateCity');
    Route::delete('/city/{id}', 'CitiesController@deleteCity');
});

Route::group(['namespace' => 'Users'], function () {
    Route::get('/roles', 'UsersController@getRoleList');

    Route::get('/users', 'UsersController@getUsersList');
    Route::get('/users_paginated', 'UsersController@getUsersPaginated');
    Route::post('/user', 'UsersController@register');
});

