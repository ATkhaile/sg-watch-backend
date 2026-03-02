<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Member Shop Review Routes
|--------------------------------------------------------------------------
|
| Product review routes for authenticated users
|
*/

Route::prefix('shop/reviews')->group(function () {
    Route::get('my', \App\Http\Actions\Api\ShopReview\GetMyReviewsAction::class);
    Route::get('{id}', \App\Http\Actions\Api\ShopReview\GetReviewDetailAction::class);
    Route::post('/', \App\Http\Actions\Api\ShopReview\CreateReviewAction::class);
    Route::post('{id}', \App\Http\Actions\Api\ShopReview\UpdateReviewAction::class);
    Route::delete('{id}', \App\Http\Actions\Api\ShopReview\DeleteReviewAction::class);
});
