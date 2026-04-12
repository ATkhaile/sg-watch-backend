<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Shop Brand Management Routes
|--------------------------------------------------------------------------
*/

Route::prefix('shop-brands')->group(function () {
    Route::get('/', \App\Http\Actions\Api\ShopBrand\GetShopBrandListAction::class);
    Route::get('{id}', \App\Http\Actions\Api\ShopBrand\GetShopBrandDetailAction::class);
    Route::post('/', \App\Http\Actions\Api\ShopBrand\CreateShopBrandAction::class);
    Route::post('{id}', \App\Http\Actions\Api\ShopBrand\UpdateShopBrandAction::class);
    Route::delete('{id}', \App\Http\Actions\Api\ShopBrand\DeleteShopBrandAction::class);
    Route::delete('{brandId}/products', \App\Http\Actions\Api\ShopProduct\DeleteProductsByBrandAction::class);
    Route::post('{brandId}/products/restore', \App\Http\Actions\Api\ShopProduct\RestoreProductsByBrandAction::class);
});
