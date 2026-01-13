<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResumeController;
use App\Http\Middleware\AcceptJsonResponse;
use Illuminate\Support\Facades\Route;

Route::middleware([AcceptJsonResponse::class])->group(function () {
    Route::middleware(['auth:sanctum'])->prefix('/resume')->group(function () {
        Route::get('/initial', [ResumeController::class, 'getInitialData']);
        Route::get('/{resume}/load', [ResumeController::class, 'loadResume']);
        Route::post('/create', [ResumeController::class, 'createResume']);
        Route::put('/{resume}/update', [ResumeController::class, 'updateResume']);
    });
    Route::prefix('/auth')->group(function () {
        Route::post('/signup', [AuthController::class, 'signup']);
        Route::post('/login', [AuthController::class, 'login']);
    });
});
