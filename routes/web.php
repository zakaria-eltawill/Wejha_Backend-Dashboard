<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public Student API Endpoints
Route::post('/api/students/register', [\App\Http\Controllers\Api\StudentAuthController::class, 'register']);
Route::post('/api/students/login', [\App\Http\Controllers\Api\StudentAuthController::class, 'login']);
Route::post('/api/students/google-login', [\App\Http\Controllers\Api\StudentAuthController::class, 'googleLogin']);
Route::get('/api/events', [\App\Http\Controllers\Api\EventApiController::class, 'index'])->name('api.events.index');
Route::get('/api/events/{id}', [\App\Http\Controllers\Api\EventApiController::class, 'show'])->name('api.events.show');

// Authenticated API Endpoints (Supports both Client Portal Bearer Token and Filament Dashboard Session)
Route::middleware(['api.token'])->group(function () {
    Route::post('/api/attendance/scan', [\App\Http\Controllers\Api\AttendanceApiController::class, 'scan'])->name('api.attendance.scan');
    Route::post('/api/events/register', [\App\Http\Controllers\Api\EventBookingController::class, 'register'])->name('api.events.register');
    Route::post('/api/events/cancel/{id}', [\App\Http\Controllers\Api\EventBookingController::class, 'cancel'])->name('api.events.cancel');
    Route::post('/api/surveys/submit/{evaluationId}', [\App\Http\Controllers\Api\SurveyController::class, 'submit'])->name('api.surveys.submit');
    Route::post('/api/emails/send', [\App\Http\Controllers\Api\EmailApiController::class, 'send'])->name('api.emails.send');
});
