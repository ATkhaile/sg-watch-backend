<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Shop Routes
|--------------------------------------------------------------------------
|
| Public shop routes (no authentication required)
|
*/

Route::prefix('shop')->group(function () {
    // Products
    Route::get('products', \App\Http\Actions\Api\ShopProduct\GetProductListAction::class);
    Route::get('products/{slug}', \App\Http\Actions\Api\ShopProduct\GetProductDetailAction::class);

    // Cart (guest + logged-in user)
    Route::get('cart', \App\Http\Actions\Api\ShopCart\GetCartAction::class);
    Route::post('cart/items', \App\Http\Actions\Api\ShopCart\AddToCartAction::class);
});
