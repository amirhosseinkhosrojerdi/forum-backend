<?php

use App\Http\Controllers\API\V01\Auth\AuthController;
use Illuminate\Support\Facades\Route;

// Define routes for authentication
Route::prefix('/auth')->group(function(){
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('/user', [AuthController::class, 'user'])->name('auth.user');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});