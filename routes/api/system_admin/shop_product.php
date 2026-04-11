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
    Route::post('sort-order', \App\Http\Actions\Api\ShopProduct\UpdateProductSortOrderAction::class);
    Route::get('/', \App\Http\Actions\Api\ShopProduct\AdminGetProductListAction::class);
    Route::get('{id}', \App\Http\Actions\Api\ShopProduct\AdminGetProductDetailAction::class);
    Route::post('/', \App\Http\Actions\Api\ShopProduct\CreateProductAction::class);
    Route::post('import', \App\Http\Actions\Api\ShopProduct\ImportProductsAction::class);
    Route::post('import-computers', \App\Http\Actions\Api\ShopProduct\ImportComputersAction::class);
    Route::post('import-ipads', \App\Http\Actions\Api\ShopProduct\ImportIpadsAction::class);
    Route::post('{id}', \App\Http\Actions\Api\ShopProduct\UpdateProductAction::class);
    Route::delete('{id}', \App\Http\Actions\Api\ShopProduct\DeleteProductAction::class);
});

// Product Color Variants
Route::prefix('shop/product-colors')->group(function () {
    Route::get('by-product/{productId}', \App\Http\Actions\Api\ShopProductColor\GetProductColorsAction::class);
    Route::post('/', \App\Http\Actions\Api\ShopProductColor\CreateProductColorAction::class);
    Route::post('{id}', \App\Http\Actions\Api\ShopProductColor\UpdateProductColorAction::class);
    Route::delete('{id}', \App\Http\Actions\Api\ShopProductColor\DeleteProductColorAction::class);
});
