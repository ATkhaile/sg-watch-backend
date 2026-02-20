<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Notifications Routes
|--------------------------------------------------------------------------
|
| 通知管理
|
*/

Route::prefix('notifications')->group(function () {
    Route::get('', \App\Http\Actions\Api\Notifications\GetAllNotificationsAction::class);
    Route::post('', \App\Http\Actions\Api\Notifications\CreateNotificationsAction::class);
    Route::get('{id}', \App\Http\Actions\Api\Notifications\GetNotificationsDetailAction::class);
    Route::put('{id}', \App\Http\Actions\Api\Notifications\UpdateNotificationsDetailAction::class);
    Route::delete('{id}', \App\Http\Actions\Api\Notifications\DeleteNotificationsAction::class);
});

Route::prefix('notification_pushs')->group(function () {
    Route::get('', \App\Http\Actions\Api\NotificationPush\GetAllNotificationPushsAction::class);
    Route::post('', \App\Http\Actions\Api\NotificationPush\CreateNotificationPushAction::class);
    Route::get('{id}', \App\Http\Actions\Api\NotificationPush\GetNotificationPushDetailAction::class);
    Route::post('{id}', \App\Http\Actions\Api\NotificationPush\UpdateNotificationPushAction::class);
    Route::delete('{id}', \App\Http\Actions\Api\NotificationPush\DeleteNotificationPushAction::class);
    Route::get('{notification_push_id}/history', \App\Http\Actions\Api\NotificationPush\GetNotificationPushHistoryAction::class);
});

// Route::prefix('pusher/notifications')->group(function () {
//     Route::get('', \App\Http\Actions\Api\PusherNotification\GetPusherNotificationsAction::class);
//     Route::get('unread', \App\Http\Actions\Api\PusherNotification\GetPusherUnreadNotificationsAction::class);
//     Route::put('{id}/readed', \App\Http\Actions\Api\PusherNotification\UpdatePusherNotificationReadedAction::class);
// });

Route::prefix('firebase/notifications')->group(function () {
    Route::get('', \App\Http\Actions\Api\Firebase\GetFirebaseNotificationsAction::class);
    Route::get('unread', \App\Http\Actions\Api\Firebase\GetFirebaseUnreadNotificationsAction::class);
    Route::put('{id}/readed', \App\Http\Actions\Api\Firebase\UpdateFirebaseNotificationReadedAction::class);
});
