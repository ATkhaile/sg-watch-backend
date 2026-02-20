<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authorization Routes
|--------------------------------------------------------------------------
|
| ロール・パーミッション管理
|
*/

Route::prefix('authorization')->group(function () {
    Route::prefix('roles')->group(function () {
        Route::get('list', \App\Http\Actions\Api\Authorization\GetAllRoleAction::class);
        Route::post('create', \App\Http\Actions\Api\Authorization\CreateRoleAction::class);
        Route::get('{id}', \App\Http\Actions\Api\Authorization\GetRoleDetailAction::class);
        Route::put('{id}', \App\Http\Actions\Api\Authorization\UpdateRoleDetailAction::class);
        Route::delete('{id}', \App\Http\Actions\Api\Authorization\DeleteRoleAction::class);
    });

    Route::prefix('permissions')->group(function () {
        Route::get('list', \App\Http\Actions\Api\Authorization\GetAllPermissionAction::class);
        Route::post('create', \App\Http\Actions\Api\Authorization\CreatePermissionAction::class);
        Route::get('usecase-groups', \App\Http\Actions\Api\Authorization\GetUsecaseGroupsAction::class);
        Route::post('{id}/toggle-active', \App\Http\Actions\Api\Authorization\TogglePermissionActiveAction::class);
        Route::get('{id}', \App\Http\Actions\Api\Authorization\GetPermissionDetailAction::class);
        Route::put('{id}', \App\Http\Actions\Api\Authorization\UpdatePermissionDetailAction::class);
        Route::delete('{id}', \App\Http\Actions\Api\Authorization\DeletePermissionAction::class);
    });

    Route::post('users/{user_id}/permissions/attach', \App\Http\Actions\Api\Authorization\AttachPermissionToUserAction::class);
    Route::delete('users/{user_id}/permissions/revoke', \App\Http\Actions\Api\Authorization\RevokePermissionToUserAction::class);
    Route::post('users/{user_id}/roles/attach', \App\Http\Actions\Api\Authorization\AttachRoleToUserAction::class);
    Route::delete('users/{user_id}/roles/revoke', \App\Http\Actions\Api\Authorization\RevokeRoleToUserAction::class);
    Route::post('roles/{role_id}/permissions/attach', \App\Http\Actions\Api\Authorization\AttachPermissionToRoleAction::class);
    Route::delete('roles/{role_id}/permissions/revoke', \App\Http\Actions\Api\Authorization\RevokePermissionToRoleAction::class);
    Route::get('users/{user_id}/roles', \App\Http\Actions\Api\Authorization\ListRoleFromUserAction::class);
    Route::get('users/{user_id}/permissions', \App\Http\Actions\Api\Authorization\ListPermissionFromUserAction::class);
    Route::get('roles/{role_id}/permissions', \App\Http\Actions\Api\Authorization\ListPermissionFromRoleAction::class);
});
