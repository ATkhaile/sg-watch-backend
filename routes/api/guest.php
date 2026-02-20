<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes (認証不要)
|--------------------------------------------------------------------------
|
| 認証不要で誰でもアクセス可能なルート
|
*/

require __DIR__ . '/guest/auth.php';
require __DIR__ . '/guest/webhooks.php';
