<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DatadiriController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',[DatadiriController::class,'index']);
// Route::get('/view',[DatadiriController::class,'view']);
// Route::get('/create',[DatadiriController::class,'store']);
// Route::get('/',[DatadiriController::class,'index']);
// Route::get('getData', function () {
//     return view('data');
// });
