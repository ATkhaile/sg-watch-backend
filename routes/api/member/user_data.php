<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Data Routes
|--------------------------------------------------------------------------
|
| ユーザー別データ操作
|
*/

Route::prefix('user/{user_id}')->group(function () {
    Route::get('fcm_token', \App\Http\Actions\Api\FcmToken\GetUserFcmTokensAction::class);
});

Route::post('user/receive_notification', \App\Http\Actions\Api\NotificationPush\UpdateReceiveNotificationSettingAction::class);
Route::post('fcm_token/{fcm_token_id}/status', \App\Http\Actions\Api\FcmToken\UpdateFcmTokenStatusAction::class);
