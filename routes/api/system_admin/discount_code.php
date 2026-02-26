<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Discount Code Management Routes
|--------------------------------------------------------------------------
*/

Route::prefix('discount-codes')->group(function () {
    Route::get('/', \App\Http\Actions\Api\DiscountCode\GetDiscountCodeListAction::class);
    Route::get('{id}', \App\Http\Actions\Api\DiscountCode\GetDiscountCodeDetailAction::class);
    Route::post('/', \App\Http\Actions\Api\DiscountCode\CreateDiscountCodeAction::class);
    Route::post('{id}', \App\Http\Actions\Api\DiscountCode\UpdateDiscountCodeAction::class);
    Route::delete('{id}', \App\Http\Actions\Api\DiscountCode\DeleteDiscountCodeAction::class);
});
