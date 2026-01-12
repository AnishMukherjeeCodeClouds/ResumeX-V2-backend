<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResumeController;
use App\Http\Middleware\AcceptJsonResponse;
use Illuminate\Support\Facades\Route;

Route::middleware([AcceptJsonResponse::class])->group(function () {
    Route::middleware(['auth:sanctum'])->prefix('/resume')->group(function () {
        Route::get('/all', [ResumeController::class, 'getAll']);
        Route::post('/create', [ResumeController::class, 'create']);
    });
    Route::prefix('/auth')->group(function () {
        Route::post('/signup', [AuthController::class, 'signup']);
        Route::post('/login', [AuthController::class, 'login']);
    });
});
