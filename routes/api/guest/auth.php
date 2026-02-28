<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Auth Routes
|--------------------------------------------------------------------------
|
| 認証関連（ログイン・登録・パスワードリセット・OAuth）
|
*/

Route::prefix('login')->group(function () {
    Route::post('', \App\Http\Actions\Api\Auth\LoginAction::class);

    // OAuth - Web
    Route::post('google', \App\Http\Actions\Api\Google\GoogleCallbackAction::class);

    // OAuth - Mobile App
    Route::post('google-app', \App\Http\Actions\Api\Google\GoogleAppLoginAction::class);
});
Route::post('verify-login', \App\Http\Actions\Api\Auth\VerifyLoginAction::class);
Route::post('forgot-password', \App\Http\Actions\Api\Auth\ForgotPasswordAction::class);
Route::post('reset-password/{token}', \App\Http\Actions\Api\Auth\ResetPasswordAction::class);
Route::post('register', \App\Http\Actions\Api\Auth\RegisterUserAction::class);
Route::post('verify-registration', \App\Http\Actions\Api\Auth\VerifyRegistrationOtpAction::class);
Route::get('check-reset-token/{token}', \App\Http\Actions\Api\Auth\CheckResetTokenAction::class);
Route::post('web-session-auth', \App\Http\Actions\Api\Auth\SessionAppLoginAction::class);

// Password Reset OTP Flow
Route::prefix('password')->group(function () {
    Route::post('otp/send', \App\Http\Actions\Api\Auth\SendPasswordOtpAction::class);
    Route::post('otp/verify', \App\Http\Actions\Api\Auth\VerifyPasswordOtpAction::class);
    Route::post('reset', \App\Http\Actions\Api\Auth\ResetPasswordByTokenAction::class);
});
