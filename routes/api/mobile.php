<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mobile App Routes (auth.basic)
|--------------------------------------------------------------------------
|
| iOS/Android向けのモバイルアプリ用ルート（JWTのみ）
|
*/

Route::group([
    'middleware' => ['auth.basic'],
], function () {
    require __DIR__ . '/mobile/settings.php';
    require __DIR__ . '/mobile/stripe.php';
});
