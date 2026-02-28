<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Shop Category Management Routes
|--------------------------------------------------------------------------
*/

Route::prefix('shop-categories')->group(function () {
    Route::get('/', \App\Http\Actions\Api\ShopCategory\GetShopCategoryListAction::class);
    Route::get('{id}', \App\Http\Actions\Api\ShopCategory\GetShopCategoryDetailAction::class);
    Route::post('/', \App\Http\Actions\Api\ShopCategory\CreateShopCategoryAction::class);
    Route::post('{id}', \App\Http\Actions\Api\ShopCategory\UpdateShopCategoryAction::class);
    Route::delete('{id}', \App\Http\Actions\Api\ShopCategory\DeleteShopCategoryAction::class);
});
