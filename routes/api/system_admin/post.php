<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Post Management Routes
|--------------------------------------------------------------------------
*/

Route::prefix('posts')->group(function () {
    Route::get('/', \App\Http\Actions\Api\Post\GetPostListAction::class);
    Route::get('{id}', \App\Http\Actions\Api\Post\GetPostDetailAction::class);
    Route::post('/', \App\Http\Actions\Api\Post\CreatePostAction::class);
    Route::post('{id}', \App\Http\Actions\Api\Post\UpdatePostAction::class);
    Route::delete('{id}', \App\Http\Actions\Api\Post\DeletePostAction::class);
});
