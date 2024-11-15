<?php

use App\Http\Controllers\LoggingController;
use Illuminate\Support\Facades\Route;
use Filament\Facades\Filament;

Route::group(["prefix" => "logging", 'middleware' => 'web'], function () {
    Route::post('auth', [LoggingController::class, 'auth'])->name('logging_auth');
    Route::get('auth', [LoggingController::class, 'login'])->name('logging_login');
});
