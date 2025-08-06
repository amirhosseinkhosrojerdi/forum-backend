<?php

use \App\Http\Controllers\API\V01\Channel\ChannelController;
use Illuminate\Support\Facades\Route;

// Define routes for channels
Route::prefix('/channel')->group(function(){
    Route::get('/all', [ChannelController::class, 'getAllChannelsList'])->name('channel.all');
    Route::middleware(['can:Manage Channels', 'auth:sanctum'])->group(function(){
        Route::post('/create', [ChannelController::class, 'createNewChannel'])->name('channel.create');
        Route::put('/update', [ChannelController::class, 'updateChannel'])->name('channel.update');
        Route::delete('/delete', [ChannelController::class, 'deleteChannel'])->name('channel.delete');
    });
});