<?php

use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppleController;

// Route::get('/', function () {
//     return view('welcome');
// });


// Route::get('/', function () {
//     return redirect('admin');
// });

// AI Generated Websites routes
Route::get('/websites/{uuid}', [WebsiteController::class, 'show'])
    ->name('websites.show');
Route::get('/websites/{uuid}/preview', [WebsiteController::class, 'preview'])
    ->name('websites.preview');
Route::get('/websites/{uuid}/{path}', [WebsiteController::class, 'show'])
    ->where('path', '[a-zA-Z0-9_\-\.]+')
    ->name('websites.file');
Route::post('apple-callback', [AppleController::class, 'index']);
