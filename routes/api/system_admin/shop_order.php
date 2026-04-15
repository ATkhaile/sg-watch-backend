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
    Route::post('/', \App\Http\Actions\Api\ShopOrder\AdminCreateOrderAction::class);
    Route::get('{id}', \App\Http\Actions\Api\ShopOrder\AdminGetOrderDetailAction::class);
    Route::put('{id}', \App\Http\Actions\Api\ShopOrder\AdminUpdateOrderAction::class);
    Route::get('{id}/invoice', \App\Http\Actions\Api\ShopOrder\AdminGenerateInvoiceAction::class);
    Route::put('{id}/status', \App\Http\Actions\Api\ShopOrder\AdminUpdateOrderStatusAction::class);
    Route::put('{id}/payment-status', \App\Http\Actions\Api\ShopOrder\AdminUpdatePaymentStatusAction::class);
    Route::delete('{id}', \App\Http\Actions\Api\ShopOrder\AdminDeleteOrderAction::class);
});
