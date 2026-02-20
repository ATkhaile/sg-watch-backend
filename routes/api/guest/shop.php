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
    Route::get('products', \App\Http\Actions\Api\ShopProduct\GetProductListAction::class);
    Route::get('products/{slug}', \App\Http\Actions\Api\ShopProduct\GetProductDetailAction::class);
});
