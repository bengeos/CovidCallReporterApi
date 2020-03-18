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
    Route::get('/regions', 'RegionsController@authenticate');
    Route::get('/regions_paginated', 'RegionsController@getRegionsPaginated');
    Route::post('/region', 'RegionsController@createRegion');
    Route::patch('/region', 'RegionsController@updateRegion');
    Route::delete('/region/{id}', 'RegionsController@deleteRegion');
});
