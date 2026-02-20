<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mobile Stripe Routes
|--------------------------------------------------------------------------
|
| Stripe決済関連
|
*/

Route::get('stripe/portal-link', \App\Http\Actions\Api\Stripe\GetPortalLinkAction::class);
Route::post('stripe/cancel-request', \App\Http\Actions\Api\Stripe\RequestCancelAction::class);
// Route::post('stripe/subscribe-plan', \App\Http\Actions\Api\PaymentStripe\SubscribePlanAction::class);
// Route::post('stripe/create-payment-link', \App\Http\Actions\Api\PaymentStripe\CreatePaymentLinkAction::class);
// Route::post('stripe/activate-membership', \App\Http\Actions\Api\PaymentStripe\ActivateMembershipAction::class);
