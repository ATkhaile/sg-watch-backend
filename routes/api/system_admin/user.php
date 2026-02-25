<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin User Management Routes
|--------------------------------------------------------------------------
|
| ユーザー管理ルート（システム管理者専用）
|
*/

Route::prefix('users')->group(function () {
    Route::get('/', \App\Http\Actions\Api\AdminUser\GetAdminUserListAction::class);
    Route::get('{id}', \App\Http\Actions\Api\AdminUser\GetAdminUserDetailAction::class);
    Route::post('/', \App\Http\Actions\Api\AdminUser\CreateAdminUserAction::class);
    Route::post('{id}', \App\Http\Actions\Api\AdminUser\UpdateAdminUserAction::class);
    Route::delete('{id}', \App\Http\Actions\Api\AdminUser\DeleteAdminUserAction::class);
});
