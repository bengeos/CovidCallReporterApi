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

Route::group(['namespace' => 'Dashboards'], function () {
    Route::get('/dashboard/count_data', 'DashboardsController@getDashboardCountData');
    Route::get('/dashboard/regional_call_reports', 'DashboardsController@getRegionalCallReports');
    Route::get('/dashboard/reports_history/{id}', 'DashboardsController@getDailyReportsHistoryCount');
});

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
    Route::get('/cities_by_region/{id}', 'CitiesController@getCitiesListByRegion');
    Route::get('/cities_paginated/{id}', 'CitiesController@getCitiesPaginated');
    Route::post('/city', 'CitiesController@createCity');
    Route::patch('/city', 'CitiesController@updateCity');
    Route::delete('/city/{id}', 'CitiesController@deleteCity');

    Route::get('/sub_cities/{id}', 'SubCitiesController@getSubCitiesList');
    Route::get('/sub_cities_paginated/{id}', 'SubCitiesController@getSubCitiesPaginated');
    Route::post('/sub_city', 'SubCitiesController@createSubCity');
    Route::patch('/sub_city', 'SubCitiesController@updateSubCity');
    Route::delete('/sub_city/{id}', 'SubCitiesController@deleteSubCity');
});

Route::group(['namespace' => 'CallReports'], function () {
    Route::get('/rumor_types', 'CallReportsController@getRumorTypes');
    Route::get('/call_reports_of_user', 'CallReportsController@getCallReports');
    Route::post('/call_report', 'CallReportsController@createCallReport');
    Route::patch('/call_report', 'CallReportsController@updateCallReport');
    Route::delete('/call_report/{id}', 'CallReportsController@deleteCallReport');
});

Route::group(['namespace' => 'Reports'], function () {
    // CallReports Section
    Route::get('/new_call_reports', 'CallReportsController@getNewCallReports');
    Route::get('/all_call_reports', 'CallReportsController@getAllCallReports');
    Route::patch('/update_call_report', 'CallReportsController@updateCallReport');

    Route::get('/all_rapid_call_reports_paginated', 'CallReportsController@getNewRapidCallReportsPaginated');
    Route::get('/new_followup_call_reports_paginated', 'CallReportsController@getNewFollowupCallReportsPaginated');

});

Route::group(['namespace' => 'RapidResponses'], function () {
    // CallReports Section
    Route::get('/get_new_rapid_call_reports', 'CallReportsController@getNewRapidCallReportsPaginated');
    Route::get('/get_assigned_rapid_call_reports', 'CallReportsController@getNewRapidCallReportsPaginated');
});

Route::group(['namespace' => 'Users'], function () {
    Route::get('/roles', 'UsersController@getRoleList');
    Route::get('/users', 'UsersController@getUsersList');
    Route::get('/users_paginated', 'UsersController@getUsersPaginated');
    Route::post('/user', 'UsersController@createUser');
    Route::patch('/user', 'UsersController@updateUsers');
    Route::patch('/user_status', 'UsersController@updateUserStatus');
    Route::delete('/user/{id}', 'UsersController@deleteUser');
});


Route::group(['namespace' => 'Reports'], function () {
    Route::get('/pull_request_payload', 'CallReportsController@pullPayload');
});

