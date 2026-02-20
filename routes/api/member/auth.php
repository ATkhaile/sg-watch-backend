<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| ログイン中ユーザー自身の情報・操作
|
*/

// User Info / Profile
Route::get('user-info', \App\Http\Actions\Api\Auth\UserInfoAction::class);
Route::put('update-profile', \App\Http\Actions\Api\Auth\UpdateProfileAction::class);
Route::post('update-avatar', \App\Http\Actions\Api\Auth\UpdateAvatarAction::class);
Route::delete('delete-avatar', \App\Http\Actions\Api\Auth\DeleteAvatarAction::class);
Route::get('logout', \App\Http\Actions\Api\Auth\LogoutAction::class);
Route::post('change-password', \App\Http\Actions\Api\Auth\ChangePasswordAction::class);
Route::get('pending-email-change', \App\Http\Actions\Api\Auth\GetPendingEmailChangeAction::class);
Route::post('request-email-change', \App\Http\Actions\Api\Auth\RequestEmailChangeAction::class);
Route::post('confirm-email-change', \App\Http\Actions\Api\Auth\ConfirmEmailChangeAction::class);
Route::delete('withdraw', \App\Http\Actions\Api\Auth\WithdrawAction::class);

// FCM Token
Route::post('fcm_token', \App\Http\Actions\Api\FcmToken\CreateFcmTokenAction::class);
Route::delete('fcm_token ', \App\Http\Actions\Api\FcmToken\DeleteFcmTokenAction::class);
