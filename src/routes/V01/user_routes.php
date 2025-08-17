<?php

use App\Http\Controllers\API\V01\User\UserController;
use Illuminate\Support\Facades\Route;

// Define routes for user-related actions
Route::prefix('/users')->group(function(){
    Route::get('/leaderboards', [UserController::class, 'leaderboards'])->name('users.leaderboards');
});