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
    require __DIR__ . '/system_admin/shop_order.php';
    require __DIR__ . '/system_admin/user.php';
    require __DIR__ . '/system_admin/banner.php';
    require __DIR__ . '/system_admin/discount_code.php';
    require __DIR__ . '/system_admin/notice.php';
    require __DIR__ . '/system_admin/shop_brand.php';
});
