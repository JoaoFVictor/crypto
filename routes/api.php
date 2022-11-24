<?php

use App\Http\Controllers\Cryptocurrency\CoinCurrentPriceController;
use App\Http\Controllers\Cryptocurrency\CoinPriceFromDateTime;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('/cryptocurrency/price')->name('cryptocurrency.price.')->group(function () {
    Route::get('/current', CoinCurrentPriceController::class)->name('current');
    Route::get('/history', CoinPriceFromDateTime::class)->name('history');
});
