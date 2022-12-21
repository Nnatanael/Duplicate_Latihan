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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('Create','App\Http\Controllers\DatadiriController@store');// insert

Route::get('View','App\Http\Controllers\DatadiriController@view');
Route::post('Update','App\Http\Controllers\DatadiriController@update');
Route::post('Delete','App\Http\Controllers\DatadiriController@destroy');
Route::post('Find','App\Http\Controllers\DatadiriController@find');