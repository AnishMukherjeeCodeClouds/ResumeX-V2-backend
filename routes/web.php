<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return [$request->user(), $request->user() != null];
    // return view('welcome');
});
