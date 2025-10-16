<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ShortLinkController;
use App\Http\Controllers\WalletController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/s/{code}', [ShortLinkController::class, 'redirect']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/modules', [ModuleController::class, 'index']);
    Route::post('/modules/{id}/activate', [ModuleController::class, 'activate']);
    Route::post('/modules/{id}/deactivate', [ModuleController::class, 'deactivate']);


    Route::middleware('checkModuleActive:1')->group(function () {
        Route::post('/shorten', [ShortLinkController::class, 'shorten']);
        Route::get('/links', [ShortLinkController::class, 'index']);
        Route::delete('/links/{id}', [ShortLinkController::class, 'destroy']);
    });

    Route::middleware('checkModuleActive:2')->group(function () {
        Route::get('/wallet', [WalletController::class, 'balance']);
        Route::post('/wallet/transfer', [WalletController::class, 'transfer']);
        Route::post('/wallet/topup', [WalletController::class, 'topup']);
        Route::get('/wallet/transactions', [WalletController::class, 'transactions']);
    });
});
