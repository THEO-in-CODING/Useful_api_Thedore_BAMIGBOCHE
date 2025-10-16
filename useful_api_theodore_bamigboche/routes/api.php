<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ShortLinkController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/modules', [ModuleController::class, 'index']);
    Route::post('/modules/{id}/activate', [ModuleController::class, 'activate']);
    Route::post('/modules/{id}/deactivate', [ModuleController::class, 'deactivate']);


    Route::middleware('checkModuleActive:1')->group(function () {
        Route::post('/shorten', [ShortLinkController::class, 'shorten']);
        Route::get('/links', [ShortLinkController::class, 'index']);
        Route::delete('/links/{id}', [ShortLinkController::class, 'destroy']);
    });
});

Route::get('/s/{code}', [ShortLinkController::class, 'redirect']);
