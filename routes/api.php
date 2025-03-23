<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
Route::get('/applications/{application}/cv', [ApplicationController::class, 'downloadCv'])->name('applications.cv.download');

Route::get('/application_statuses', [ApplicationController::class, 'getApplicationStatuses'])->name('application_statuses.index');
Route::get('/employment_types', [ApplicationController::class, 'getEmploymentTypes'])->name('employment_types.index');
Route::get('/availabilities', [ApplicationController::class, 'getAvailabilities'])->name('availabilities.index');
Route::get('/work_modalities', [ApplicationController::class, 'getWorkModalities'])->name('work_modalities.index');
