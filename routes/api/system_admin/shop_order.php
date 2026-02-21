<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Shop Order Routes
|--------------------------------------------------------------------------
|
| Order management routes for system admin
|
*/

Route::prefix('shop/orders')->group(function () {
    Route::put('{id}/status', \App\Http\Actions\Api\ShopOrder\AdminUpdateOrderStatusAction::class);
});
