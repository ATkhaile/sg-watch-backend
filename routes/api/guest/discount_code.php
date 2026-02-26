<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Discount Code Routes
|--------------------------------------------------------------------------
|
| Public discount code routes (no authentication required)
|
*/

Route::get('discount-codes/{code}', \App\Http\Actions\Api\DiscountCode\GetDiscountCodeByCodeAction::class);
