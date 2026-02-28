<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Notice Management Routes
|--------------------------------------------------------------------------
*/

Route::prefix('notices')->group(function () {
    Route::get('/', \App\Http\Actions\Api\Notice\GetNoticeListAction::class);
    Route::get('{id}', \App\Http\Actions\Api\Notice\GetNoticeDetailAction::class);
    Route::post('/', \App\Http\Actions\Api\Notice\CreateNoticeAction::class);
    Route::post('{id}', \App\Http\Actions\Api\Notice\UpdateNoticeAction::class);
    Route::delete('{id}', \App\Http\Actions\Api\Notice\DeleteNoticeAction::class);
});
