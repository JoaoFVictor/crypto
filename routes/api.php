<?php

use App\Http\Controllers\Cryptocurrency\CoinCurrentPriceController;
use App\Http\Controllers\Cryptocurrency\CoinPriceFromDateTimeController;
use App\Http\Controllers\Cryptocurrency\ValidCryptocurrencyNameController;
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

Route::prefix('/cryptocurrency')->name('cryptocurrency.')->group(function () {
    Route::get('/names', ValidCryptocurrencyNameController::class)->name('names');
    Route::prefix('/price')->name('price.')->group(function () {
        Route::get('/current', CoinCurrentPriceController::class)->name('current');
        Route::get('/history', CoinPriceFromDateTimeController::class)->name('history');
    });
});
