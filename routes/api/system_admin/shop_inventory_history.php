<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Shop Inventory History Routes
|--------------------------------------------------------------------------
|
| Inventory import/export history management routes for system admin
|
*/

Route::prefix('shop/inventory-histories')->group(function () {
    Route::get('/', \App\Http\Actions\Api\ShopInventoryHistory\GetInventoryHistoryAction::class);
});
