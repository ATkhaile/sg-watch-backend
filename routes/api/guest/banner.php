<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Banner Routes
|--------------------------------------------------------------------------
|
| Public banner routes (no authentication required)
|
*/

Route::get('banners', \App\Http\Actions\Api\Banner\GetPublicBannerListAction::class);
