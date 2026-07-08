<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\DatabaseNotification;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasUuids, SoftDeletes, HasRoles;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'api_token',
        'phone_number',
        'gender',
        'academic_year',
        'school_name',
        'specialization',
        'preferred_language',
        'preferred_theme',
        'timezone',
        'notification_preferences',
        'avatar',
        'status',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notification_preferences' => 'array',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Get the events created by the user.
     */
    public function createdEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'creator_id');
    }

    /**
     * Get the registrations for the user.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get the survey responses for the user.
     */
    public function surveyResponses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }

    /**
     * Get the audit logs of actions performed by this user.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Custom database notifications relationship for Filament top-bar notifications.
     */
    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->latest();
    }

    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }
}
