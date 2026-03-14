<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Big Sale Management Routes
|--------------------------------------------------------------------------
*/

Route::prefix('big-sales')->group(function () {
    Route::get('/', \App\Http\Actions\Api\BigSale\GetBigSaleListAction::class);
    Route::get('{id}', \App\Http\Actions\Api\BigSale\GetBigSaleDetailAction::class);
    Route::post('/', \App\Http\Actions\Api\BigSale\CreateBigSaleAction::class);
    Route::post('{id}', \App\Http\Actions\Api\BigSale\UpdateBigSaleAction::class);
    Route::delete('{id}', \App\Http\Actions\Api\BigSale\DeleteBigSaleAction::class);
});
