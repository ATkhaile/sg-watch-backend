<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mobile Settings Routes
|--------------------------------------------------------------------------
|
| コメント・通知
|
*/

// Comments
Route::post('comments/create', \App\Http\Actions\Api\Comment\CreateCommentAction::class);
Route::get('comments/{model}/{modelId}', \App\Http\Actions\Api\Comment\GetCommentsByModelAction::class);
