<?php

/**
 ** This routes use all controller in App\\Http\\Controllers\Api
 ** Look at App\Providers\RouteServiceProvider
 */

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

Route::post('/incomes/generate/monthly-payment-view', 'IncomeController@reloadPeriodView')->name('incomes.generate-monthly-payment-view');
