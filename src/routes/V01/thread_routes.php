<?php

use \App\Http\Controllers\API\V01\Thread\ThreadController;
use \App\Http\Controllers\API\V01\Thread\AnswerControlle;
use Illuminate\Support\Facades\Route;

// Define routes for threads
Route::resource('threads', ThreadController::class);

// Define routes for answers related to threads
route::prefix('threads')->group(function () {
    Route::resource('answers', AnswerControlle::class);
});