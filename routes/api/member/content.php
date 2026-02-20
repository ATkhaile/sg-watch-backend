<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Content Routes
|--------------------------------------------------------------------------
|
| コンテンツ管理（Comments）
|
*/

Route::prefix('comments')->group(function () {
    Route::get('list', \App\Http\Actions\Api\Comment\GetAllCommentsAction::class);
    Route::delete('{id}', \App\Http\Actions\Api\Comment\DeleteCommentAction::class);
});
