<?php

use App\Http\Controllers\SessionController;
use App\Http\Middleware\VerifyAuth;
use App\Http\Middleware\VerifyGuest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('home')
        : redirect()->route('login');
});

Route::middleware(VerifyGuest::class)->group(function () {
    Route::get('/login', [SessionController::class, 'toView'])->name('login');
    Route::post('/login', [SessionController::class, 'tryLogin'])->name('login.try');
});

Route::middleware(VerifyAuth::class)->group(function () {
    Route::view('/home', 'home')->name('home');
    Route::post('/logout', [SessionController::class, 'logout'])->name('logout');
});
