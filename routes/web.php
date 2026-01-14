<?php

use App\Http\Middleware\CheckAdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return redirect()->away(env('FRONTEND_URL'));
})->name('login');

Route::get('/', function (Request $request) {
    return view('dashboard', ['current_user' => $request->user()]);
})->middleware(['auth:sanctum', CheckAdminUser::class]);
