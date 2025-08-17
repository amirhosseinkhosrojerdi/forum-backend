<?php

use \App\Http\Controllers\API\V01\Thread\ThreadController;
use \App\Http\Controllers\API\V01\Thread\AnswerController;
use \App\Http\Controllers\API\V01\Thread\SubscribeController;
use Illuminate\Support\Facades\Route;

// Define routes for threads
Route::resource('threads', ThreadController::class);

// Define routes for answers and subscriptions
Route::prefix('threads')->group(function () {
    // Resource route for answers
    Route::resource('answers', AnswerController::class);

    // Subscribe and unsubscribe routes
    Route::post('{thread}/subscribe', [SubscribeController::class, 'subscribe'])->name('subscribe');
    Route::post('{thread}/unsubscribe', [SubscribeController::class, 'unSubscribe'])->name('unSubscribe');
});