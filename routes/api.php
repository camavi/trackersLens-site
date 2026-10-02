<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\LandingController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/launch-subscriptions', [LandingController::class, 'subscribe'])->middleware('throttle:8,1');
Route::post('/contact-messages', [LandingController::class, 'contact'])->middleware('throttle:5,1');
Route::get('/public/catalog', [CatalogController::class, 'publicIndex'])->middleware('throttle:60,1');

Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('/catalog', [CatalogController::class, 'index']);
    Route::get('/catalog/{artifact}/versions/{version}', [CatalogController::class, 'download']);
    Route::post('/catalog', [CatalogController::class, 'publish'])->middleware('throttle:20,1');
    Route::get('/user', [AuthController::class, 'user']);
    Route::patch('/user', [AuthController::class, 'updateProfile'])->middleware('throttle:10,1');
    Route::put('/user/password', [AuthController::class, 'updatePassword'])->middleware('throttle:10,1');
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('dashboard')->group(function (): void {
        Route::get('/summary', [DashboardController::class, 'summary']);
        Route::get('/activity', [DashboardController::class, 'activity']);
        Route::get('/system-status', [DashboardController::class, 'systemStatus']);
    });
});
