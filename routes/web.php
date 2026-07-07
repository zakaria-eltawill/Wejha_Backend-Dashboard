<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/api/attendance/scan', [\App\Http\Controllers\Api\AttendanceApiController::class, 'scan'])->name('api.attendance.scan');
    Route::post('/api/events/register', [\App\Http\Controllers\Api\EventBookingController::class, 'register'])->name('api.events.register');
    Route::post('/api/events/cancel/{id}', [\App\Http\Controllers\Api\EventBookingController::class, 'cancel'])->name('api.events.cancel');
    Route::post('/api/surveys/submit/{evaluationId}', [\App\Http\Controllers\Api\SurveyController::class, 'submit'])->name('api.surveys.submit');
});
