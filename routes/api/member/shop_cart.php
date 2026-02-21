<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Member Shop Cart Routes
|--------------------------------------------------------------------------
|
| Cart routes that require authentication
|
*/

Route::prefix('shop/cart')->group(function () {
    Route::post('merge', \App\Http\Actions\Api\ShopCart\MergeCartAction::class);
});
