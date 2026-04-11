<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Post Routes
|--------------------------------------------------------------------------
|
| Public post routes (no authentication required)
|
*/

Route::prefix('posts')->group(function () {
    Route::get('/', \App\Http\Actions\Api\Post\GetPublicPostListAction::class);
    Route::get('{id}', \App\Http\Actions\Api\Post\GetPublicPostDetailAction::class);
});
