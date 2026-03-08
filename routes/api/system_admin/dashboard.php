<?php

use Illuminate\Support\Facades\Route;

Route::get('dashboard', \App\Http\Actions\Api\Dashboard\AdminGetDashboardAction::class);
