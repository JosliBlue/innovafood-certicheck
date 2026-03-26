<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\LookupController;
use App\Http\Controllers\SessionController;
use App\Http\Middleware\VerifyAuth;
use App\Http\Middleware\VerifyGuest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('clients.index')
        : redirect()->route('consulta');
});

Route::get('/consulta', [LookupController::class, 'index'])->name('consulta');
Route::post('/consulta/buscar', [LookupController::class, 'search'])->name('lookup.search');

Route::middleware(VerifyGuest::class)->group(function () {
    Route::get('/login', [SessionController::class, 'toView'])->name('login');
    Route::post('/login', [SessionController::class, 'tryLogin'])->name('login.try');
});

Route::middleware(VerifyAuth::class)->group(function () {
    Route::post('/logout', [SessionController::class, 'logout'])->name('logout');

    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
});
