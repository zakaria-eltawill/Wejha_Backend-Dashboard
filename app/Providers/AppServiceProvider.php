<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\User::observe(\App\Observers\AuditObserver::class);
        \App\Models\Event::observe(\App\Observers\AuditObserver::class);
        \App\Models\Registration::observe(\App\Observers\AuditObserver::class);
        \App\Models\Attendance::observe(\App\Observers\AuditObserver::class);
        \App\Models\SurveyTemplate::observe(\App\Observers\AuditObserver::class);
        \App\Models\SurveyResponse::observe(\App\Observers\AuditObserver::class);
        \App\Models\Notification::observe(\App\Observers\AuditObserver::class);
    }
}
