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


Route::prefix('v2')->namespace('Api\V2')->group(function () {

    Route::post('/update/check', 'OptionController@Update');

    Route::post('/login', 'UserController@Login');
    
    Route::post('/register', 'UserController@Register');
    
    Route::post('/cities', 'MosqueController@GetCities');
    
    Route::post('/cities/id', 'MosqueController@GetCity');
    
    Route::post('/mosques', 'MosqueController@GetMosques');
    
    Route::post('/mosques/times/updated', 'MosqueController@GetTimes');

    Route::post('/mosques/times/fajr', 'MosqueController@GetFajr');

    Route::post('/mosques/times/zohr', 'MosqueController@GetZohr');

    Route::post('/mosques/times/asr', 'MosqueController@GetAsr');

    Route::post('/mosques/times/maghrib', 'MosqueController@GetMaghrib');

    Route::post('/mosques/times/isha', 'MosqueController@GetIsha');

    Route::post('/mosques/id', 'MosqueController@GetMosque');

    Route::post('/mosques/add', 'MosqueController@AddMosque');

    Route::post('/comment', 'OptionController@Comment');

    Route::post('/sounds', 'OptionController@Sounds');

    Route::post('/guids', 'OptionController@Guids');

    Route::post('/guids/id', 'OptionController@GetGuid');

    // Responsibles:
    
    Route::post('/mosques/responsible/all', 'MosqueController@AllResbonsibles');

    Route::middleware('auth:api')->group(function () {

        Route::post('/dashboard', 'UserController@Dashboard');

        Route::post('/dashboard/add', 'UserController@AddToDashboard');

        Route::post('/mosques/times/update', 'MosqueController@UpdateTimes');

        // Favorites:

        Route::post('/user/favorites', 'MosqueController@GetFavorites');

        Route::post('/user/favorites/add', 'MosqueController@AddFavorites');

        Route::post('/user/favorites/delete', 'MosqueController@DeleteFavorites');

        // Update User:

        Route::post('/user/update/name', 'UserController@UpdateName');

        Route::post('/user/update/phone', 'UserController@UpdatePhone');

        Route::post('/user/update/password', 'UserController@UpdatePassword');

        // Responsible:

        Route::post('/mosques/responsible/add', 'MosqueController@AddResbonsible');

        Route::post('/mosques/responsible', 'MosqueController@GetResbonsibles');
    });
});
