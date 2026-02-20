<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Webhook Routes
|--------------------------------------------------------------------------
|
| Webhook（Stripe）
|
*/

// Stripe
Route::post('stripe/webhook', \App\Http\Actions\Api\PaymentStripe\WebHookAction::class);
Route::post('stripe/create-customer', \App\Http\Actions\Api\Stripe\CreateCustomerAction::class);
Route::post('stripe/submit-request', \App\Http\Actions\Api\PaymentStripe\SubmitCancelAction::class);
Route::post('stripe/check-code-request', \App\Http\Actions\Api\PaymentStripe\CheckCancelCodeAction::class);
