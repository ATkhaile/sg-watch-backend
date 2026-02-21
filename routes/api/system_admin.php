<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| System Admin Routes (auth.system_admin)
|--------------------------------------------------------------------------
|
| システム管理者専用ルート（JWT + isSystemAdmin()）
|
*/

Route::group([
    'middleware' => ['auth.system_admin'],
    'prefix' => 'admin',
], function () {
    require __DIR__ . '/system_admin/shop_product.php';
});
