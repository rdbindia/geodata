<?php

use App\Http\Controllers\Factorial;
use App\Http\Controllers\GeoDataApiController;
use Illuminate\Support\Facades\Route;

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

Route::any('/', function () {
    return view('heatMap');
});

Route::any('/GeoDataApiController', [GeoDataApiController::class, 'readFileData']);
Route::any('/polygon', [GeoDataApiController::class, 'polygon']);
Route::get('/factorial',[Factorial::class,'index']);

