<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Shop Product Routes
|--------------------------------------------------------------------------
|
| Product management routes for system admin
|
*/

Route::prefix('shop/products')->group(function () {
    Route::post('/', \App\Http\Actions\Api\ShopProduct\CreateProductAction::class);
    Route::put('{id}', \App\Http\Actions\Api\ShopProduct\UpdateProductAction::class);
    Route::delete('{id}', \App\Http\Actions\Api\ShopProduct\DeleteProductAction::class);
});
