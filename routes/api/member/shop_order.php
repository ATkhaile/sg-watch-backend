<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Member Shop Order Routes
|--------------------------------------------------------------------------
|
| Order routes that require authentication
|
*/

Route::prefix('shop/orders')->group(function () {
    Route::post('checkout', \App\Http\Actions\Api\ShopOrder\CheckoutAction::class);
    Route::get('/', \App\Http\Actions\Api\ShopOrder\GetOrderListAction::class);
    Route::get('{id}', \App\Http\Actions\Api\ShopOrder\GetOrderDetailAction::class);
    Route::get('{id}/invoice', \App\Http\Actions\Api\ShopOrder\GenerateInvoiceAction::class);
    Route::post('{id}/cancel', \App\Http\Actions\Api\ShopOrder\CancelOrderAction::class);
    Route::post('{id}/payment-receipt', \App\Http\Actions\Api\ShopOrder\UpdatePaymentReceiptAction::class);
});
