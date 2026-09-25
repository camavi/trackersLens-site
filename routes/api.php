<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\LandingController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/launch-subscriptions', [LandingController::class, 'subscribe'])->middleware('throttle:8,1');
Route::post('/contact-messages', [LandingController::class, 'contact'])->middleware('throttle:5,1');

Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('dashboard')->group(function (): void {
        Route::get('/summary', [DashboardController::class, 'summary']);
        Route::get('/activity', [DashboardController::class, 'activity']);
        Route::get('/system-status', [DashboardController::class, 'systemStatus']);
    });
});
