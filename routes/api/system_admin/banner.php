<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Banner Management Routes
|--------------------------------------------------------------------------
*/

Route::prefix('banners')->group(function () {
    Route::get('/', \App\Http\Actions\Api\Banner\GetBannerListAction::class);
    Route::get('{id}', \App\Http\Actions\Api\Banner\GetBannerDetailAction::class);
    Route::post('/', \App\Http\Actions\Api\Banner\CreateBannerAction::class);
    Route::post('{id}', \App\Http\Actions\Api\Banner\UpdateBannerAction::class);
    Route::delete('{id}', \App\Http\Actions\Api\Banner\DeleteBannerAction::class);
});
