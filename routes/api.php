<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
Route::get('/applications/{application}/cv', [ApplicationController::class, 'downloadCv'])->name('applications.cv.download');
