<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/applications', [ApplicationController::class, 'store']);
Route::get('/applications', [ApplicationController::class, 'index']);
