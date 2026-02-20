<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Member Routes (auth.session)
|--------------------------------------------------------------------------
|
| 一般ユーザー向けルート（Web向け、セッション管理付き）
|
*/

Route::group([
    'middleware' => ['auth.session'],
], function () {
    require __DIR__ . '/member/auth.php';
    require __DIR__ . '/member/authorization.php';
    require __DIR__ . '/member/users.php';
    require __DIR__ . '/member/content.php';
    require __DIR__ . '/member/notifications.php';
    require __DIR__ . '/member/stripe.php';
    require __DIR__ . '/member/chat.php';
    require __DIR__ . '/member/user_data.php';
    require __DIR__ . '/member/addresses.php';
});
