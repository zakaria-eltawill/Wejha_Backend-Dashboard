<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public Student API Endpoints
Route::post('/api/students/register', [\App\Http\Controllers\Api\StudentAuthController::class, 'register']);
Route::post('/api/students/login', [\App\Http\Controllers\Api\StudentAuthController::class, 'login']);
Route::post('/api/students/google-login', [\App\Http\Controllers\Api\StudentAuthController::class, 'googleLogin']);
Route::post('/api/students/forgot-password', [\App\Http\Controllers\Api\StudentAuthController::class, 'forgotPassword'])->name('api.students.forgot-password');
Route::post('/api/students/reset-password', [\App\Http\Controllers\Api\StudentAuthController::class, 'resetPassword'])->name('api.students.reset-password');
Route::get('/api/events', [\App\Http\Controllers\Api\EventApiController::class, 'index'])->name('api.events.index');
Route::get('/api/events/{id}', [\App\Http\Controllers\Api\EventApiController::class, 'show'])->name('api.events.show');
Route::get('/api/events/{id}/evaluations', [\App\Http\Controllers\Api\EventApiController::class, 'evaluations'])->name('api.events.evaluations');

// Authenticated API Endpoints (Supports both Client Portal Bearer Token and Filament Dashboard Session)
Route::middleware(['api.token'])->group(function () {
    Route::post('/api/logout', [\App\Http\Controllers\Api\StudentAuthController::class, 'logout'])->name('api.logout');

    Route::post('/api/attendance/scan', [\App\Http\Controllers\Api\AttendanceApiController::class, 'scan'])->name('api.attendance.scan');

    Route::post('/api/events/register', [\App\Http\Controllers\Api\EventBookingController::class, 'register'])->name('api.events.register');
    Route::post('/api/events/cancel/{id}', [\App\Http\Controllers\Api\EventBookingController::class, 'cancel'])->name('api.events.cancel');

    Route::get('/api/registrations', [\App\Http\Controllers\Api\RegistrationApiController::class, 'index'])->name('api.registrations.index');
    Route::get('/api/registrations/{id}', [\App\Http\Controllers\Api\RegistrationApiController::class, 'show'])->name('api.registrations.show');

    Route::get('/api/surveys/{evaluationId}/questions', [\App\Http\Controllers\Api\SurveyController::class, 'questions'])->name('api.surveys.questions');
    Route::post('/api/surveys/submit/{evaluationId}', [\App\Http\Controllers\Api\SurveyController::class, 'submit'])->name('api.surveys.submit');

    Route::get('/api/profile', [\App\Http\Controllers\Api\ProfileApiController::class, 'show'])->name('api.profile.show');
    Route::match(['put', 'patch'], '/api/profile', [\App\Http\Controllers\Api\ProfileApiController::class, 'update'])->name('api.profile.update');
    Route::match(['put', 'patch'], '/api/profile/password', [\App\Http\Controllers\Api\ProfileApiController::class, 'updatePassword'])->name('api.profile.password');

    Route::get('/api/notifications', [\App\Http\Controllers\Api\NotificationApiController::class, 'index'])->name('api.notifications.index');
    Route::post('/api/notifications/{id}/read', [\App\Http\Controllers\Api\NotificationApiController::class, 'markAsRead'])->name('api.notifications.read');

    Route::post('/api/emails/send', [\App\Http\Controllers\Api\EmailApiController::class, 'send'])->name('api.emails.send');
});
