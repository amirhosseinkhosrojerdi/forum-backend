<?php

use \App\Http\Controllers\API\V01\Thread\ThreadController;
use Illuminate\Support\Facades\Route;

Route::resource('threads', ThreadController::class);