<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EventStatus;
use App\Enums\EventType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'type',
        'banner_image',
        'cover_image',
        'speaker',
        'event_date',
        'event_time',
        'venue',
        'venue_map_url',
        'capacity',
        'registration_opens_at',
        'registration_closes_at',
        'qr_attendance_enabled',
        'requires_approval',
        'status',
        'visibility',
        'featured',
        'organizer_notes',
        'contact_person',
        'creator_id',
    ];

    protected function casts(): array
    {
        return [
            'type' => EventType::class,
            'event_date' => 'date',
            'registration_opens_at' => 'datetime',
            'registration_closes_at' => 'datetime',
            'qr_attendance_enabled' => 'boolean',
            'requires_approval' => 'boolean',
            'status' => EventStatus::class,
            'featured' => 'boolean',
        ];
    }

    /**
     * Get the creator of the event.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get the registrations for the event.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get the evaluations linked to the event.
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(EventEvaluation::class);
    }

    /**
     * Get the survey responses submitted for this event's evaluations.
     */
    public function surveyResponses(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            SurveyResponse::class,
            EventEvaluation::class,
            'event_id',
            'event_evaluation_id',
            'id',
            'id'
        );
    }

    /**
     * Get the activities/timeline for the event.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(EventActivity::class);
    }
}
