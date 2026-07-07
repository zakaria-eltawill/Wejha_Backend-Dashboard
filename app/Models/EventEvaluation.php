<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EvaluationType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventEvaluation extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'event_id',
        'survey_template_id',
        'evaluation_type',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'evaluation_type' => EvaluationType::class,
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the event this evaluation belongs to.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the survey template used for this evaluation.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(SurveyTemplate::class, 'survey_template_id');
    }

    /**
     * Get the survey responses for this evaluation session.
     */
    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }
}
