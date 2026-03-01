<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Prefecture Routes
|--------------------------------------------------------------------------
|
| 都道府県マスターデータ取得（認証不要）
|
*/

Route::get('prefectures', \App\Http\Actions\Api\Prefecture\GetPrefectureListAction::class);
