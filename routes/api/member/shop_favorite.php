<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Member Shop Favorite Routes
|--------------------------------------------------------------------------
|
| Favorite routes that require authentication
|
*/

Route::prefix('shop/favorites')->group(function () {
    Route::get('/', \App\Http\Actions\Api\ShopFavorite\GetFavoriteListAction::class);
    Route::post('toggle', \App\Http\Actions\Api\ShopFavorite\ToggleFavoriteAction::class);
});
