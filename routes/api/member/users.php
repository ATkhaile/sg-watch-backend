<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Users Routes
|--------------------------------------------------------------------------
|
| ユーザー管理
|
*/

Route::prefix('users')->group(function () {
    Route::get('list', \App\Http\Actions\Api\Users\GetAllUsersAction::class);
    Route::post('create', \App\Http\Actions\Api\Users\CreateUsersAction::class);
    Route::get('{id}', \App\Http\Actions\Api\Users\GetUsersDetailAction::class);
    Route::put('{id}', \App\Http\Actions\Api\Users\UpdateUsersDetailAction::class);
    Route::delete('{id}', \App\Http\Actions\Api\Users\DeleteUsersAction::class);
    Route::post('{id}/avatar', \App\Http\Actions\Api\Users\UpdateUserAvatarAction::class);
    Route::delete('{id}/avatar', \App\Http\Actions\Api\Users\DeleteUserAvatarAction::class);
    Route::post('{id}/cover-image', \App\Http\Actions\Api\Users\UpdateUserCoverImageAction::class);
    Route::delete('{id}/cover-image', \App\Http\Actions\Api\Users\DeleteUserCoverImageAction::class);
    Route::put('{id}/admin', \App\Http\Actions\Api\Users\ToggleUserAdminAction::class);
    Route::post('{id}/permissions/reset', \App\Http\Actions\Api\Users\ResetUserPermissionsAction::class);

    // Suspension
    Route::put('{id}/suspension', \App\Http\Actions\Api\Users\ToggleUserSuspensionAction::class);
    Route::get('{id}/suspension-logs', \App\Http\Actions\Api\Users\GetUserSuspensionLogsAction::class);

    // Stripe Customer Links
    Route::get('{id}/stripe-customer-links', \App\Http\Actions\Api\Users\GetUserStripeCustomerLinksAction::class);
    Route::post('{id}/stripe-customer-links', \App\Http\Actions\Api\Users\AddUserStripeCustomerLinkAction::class);
    Route::put('{id}/stripe-customer-links/{linkId}', \App\Http\Actions\Api\Users\UpdateUserStripeCustomerLinkAction::class);
    Route::delete('{id}/stripe-customer-links/{linkId}', \App\Http\Actions\Api\Users\DeleteUserStripeCustomerLinkAction::class);
    Route::get('{id}/stripe-customer-links/{linkId}/portal', \App\Http\Actions\Api\Users\GetCustomerPortalLinkAction::class);

    Route::get('{id}/session-device', \App\Http\Actions\Api\Users\GetUserSessionDevicesAction::class);
    Route::delete('session-device/{id}', \App\Http\Actions\Api\Users\DeleteUserSessionDeviceAction::class);
});
