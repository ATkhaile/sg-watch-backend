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
});
