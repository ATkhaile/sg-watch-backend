<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Address Routes
|--------------------------------------------------------------------------
|
| ユーザー住所管理
|
*/

Route::prefix('addresses')->group(function () {
    Route::get('', \App\Http\Actions\Api\Address\GetAddressesAction::class);
    Route::get('{id}', \App\Http\Actions\Api\Address\GetAddressDetailAction::class);
    Route::post('', \App\Http\Actions\Api\Address\CreateAddressAction::class);
    Route::put('{id}', \App\Http\Actions\Api\Address\UpdateAddressAction::class);
    Route::delete('{id}', \App\Http\Actions\Api\Address\DeleteAddressAction::class);
});
