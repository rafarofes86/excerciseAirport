<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::resource('airports', 'AirportController');
Route::get('/airports', 'AirportController@index');
Route::get('/airports/{id}', 'AirportController@show');
Route::get('/airports/{id1}/distance/{id2}', 'AirportController@distance');
Route::get('/airports/{country1}/mindistance/{country2}', 'AirportController@shortestDistance');
Route::get('/airports/{lat}/{long}/near/{rad}', 'AirportController@radiusCoordinates');
