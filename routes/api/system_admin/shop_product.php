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
    Route::get('featured', \App\Http\Actions\Api\ShopProduct\AdminGetFeaturedProductsAction::class);
    Route::post('featured', \App\Http\Actions\Api\ShopProduct\UpdateFeaturedProductsAction::class);
    Route::get('/', \App\Http\Actions\Api\ShopProduct\AdminGetProductListAction::class);
    Route::get('{id}', \App\Http\Actions\Api\ShopProduct\AdminGetProductDetailAction::class);
    Route::post('/', \App\Http\Actions\Api\ShopProduct\CreateProductAction::class);
    Route::post('{id}', \App\Http\Actions\Api\ShopProduct\UpdateProductAction::class);
    Route::delete('{id}', \App\Http\Actions\Api\ShopProduct\DeleteProductAction::class);
});
