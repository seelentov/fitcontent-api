<?php

// use App\Http\Controllers\AuthController;
// use App\Http\Controllers\FileController;
// use App\Http\Controllers\FolderController;
// use Illuminate\Support\Facades\Route;

// Route::group([], function () {
//     Route::group([
//         'prefix' => 'auth',
//         'middleware' => ['api']
//     ], function () {
//         Route::post('register', [AuthController::class, 'register']);
//         // Route::post('login', [AuthController::class, 'login']);
//         Route::post('logout', [AuthController::class, 'logout']);
//         Route::post('refresh', [AuthController::class, 'refresh']);
//         Route::get('me', [AuthController::class, 'me']);
//     });

//     Route::group([
//         'prefix' => 'folders',
//         'middleware' => ['api'],
//     ], function () {
//         Route::get('', [FolderController::class, 'index'])->name('folders');
//         Route::get('{id}', [FolderController::class, 'show'])->name('folder');
//     });

//     Route::group([
//         'prefix' => 'files',
//         'middleware' => ['api'],
//     ], function () {
//         Route::get('{id}', [FileController::class, 'show'])->name('file');;
//     });
// });
