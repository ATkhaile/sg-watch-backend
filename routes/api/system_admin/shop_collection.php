<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Shop Collection Routes
|--------------------------------------------------------------------------
|
| Collection management routes for system admin
|
*/

Route::prefix('shop/collections')->group(function () {
    Route::get('/', \App\Http\Actions\Api\ShopCollection\AdminGetCollectionsAction::class);
    Route::post('/', \App\Http\Actions\Api\ShopCollection\CreateCollectionAction::class);
    Route::get('{id}', \App\Http\Actions\Api\ShopCollection\AdminGetCollectionDetailAction::class);
    Route::post('{id}', \App\Http\Actions\Api\ShopCollection\UpdateCollectionAction::class);
    Route::delete('{id}', \App\Http\Actions\Api\ShopCollection\DeleteCollectionAction::class);
});
