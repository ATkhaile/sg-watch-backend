<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Big Sale Routes
|--------------------------------------------------------------------------
|
| Public big sale routes (no authentication required)
|
*/

Route::prefix('big-sales')->group(function () {
    Route::get('/', \App\Http\Actions\Api\BigSale\GetPublicBigSaleListAction::class);
    Route::get('{id}', \App\Http\Actions\Api\BigSale\GetPublicBigSaleDetailAction::class);
});
