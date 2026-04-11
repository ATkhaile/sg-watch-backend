<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Member Notice Routes
|--------------------------------------------------------------------------
*/

Route::prefix('shop/notices')->group(function () {
    Route::get('/', \App\Http\Actions\Api\Notice\GetMemberNoticesAction::class);
    Route::get('{id}', \App\Http\Actions\Api\Notice\GetMemberNoticeDetailAction::class);
    Route::put('{id}/read', \App\Http\Actions\Api\Notice\MarkNoticeAsReadAction::class);
});
