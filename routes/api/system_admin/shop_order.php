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
    Route::get('/', \App\Http\Actions\Api\ShopOrder\AdminGetOrderListAction::class);
    Route::get('{id}', \App\Http\Actions\Api\ShopOrder\AdminGetOrderDetailAction::class);
    Route::put('{id}/status', \App\Http\Actions\Api\ShopOrder\AdminUpdateOrderStatusAction::class);
    Route::put('{id}/payment-status', \App\Http\Actions\Api\ShopOrder\AdminUpdatePaymentStatusAction::class);
});
