<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::group([
        'prefix' => 'auth',
        'middleware' => ['api']
    ], function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });

    Route::group([
        'prefix' => 'folders',
        'middleware' => ['api', "auth:api"],
    ], function () {
        Route::get('', [FolderController::class, 'index'])->name('folders');
        Route::get('{id}', [FolderController::class, 'show'])->name('folder');
    });

    Route::group([
        'prefix' => 'files',
        'middleware' => ['api', "auth:api"],
    ], function () {
        Route::get('{id}', [FileController::class, 'show'])->name('file');
        ;
    });

    Route::group([
        'prefix' => 'info',
        'middleware' => ['api', "auth:api"],
    ], function () {
        Route::get('', [InfoController::class, 'index'])->name('infos');
        Route::get('{slug}', [InfoController::class, 'show'])->name('info');
    });

    Route::group([
        'prefix' => 'test',
    ], function () {
        Route::get('', [TestController::class, 'test'])->name('test');
    });
});
