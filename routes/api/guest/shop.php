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
    // Collections
    Route::get('collections', \App\Http\Actions\Api\ShopCollection\GetCollectionsAction::class);

    // Products
    Route::get('featured-products', \App\Http\Actions\Api\ShopProduct\GetFeaturedProductsAction::class);
    Route::get('products', \App\Http\Actions\Api\ShopProduct\GetProductListAction::class);
    Route::get('products/best-sellers', \App\Http\Actions\Api\ShopProduct\GetBestSellersAction::class);
    Route::get('products/{slug}', \App\Http\Actions\Api\ShopProduct\GetProductDetailAction::class);

    // Product Colors
    Route::get('products/{productId}/colors', \App\Http\Actions\Api\ShopProductColor\GetProductColorsAction::class);

    // Reviews
    Route::get('products/{productId}/reviews', \App\Http\Actions\Api\ShopReview\GetProductReviewsAction::class);

    // Cart (guest + logged-in user)
    Route::get('cart', \App\Http\Actions\Api\ShopCart\GetCartAction::class);
    Route::post('cart/items', \App\Http\Actions\Api\ShopCart\AddToCartAction::class);
    Route::put('cart/items/{itemId}', \App\Http\Actions\Api\ShopCart\UpdateCartItemAction::class);
    Route::delete('cart/items/{itemId}', \App\Http\Actions\Api\ShopCart\RemoveCartItemAction::class);
});
