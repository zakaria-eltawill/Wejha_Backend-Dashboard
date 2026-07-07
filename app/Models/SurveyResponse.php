<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponse extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'event_evaluation_id',
        'question_id',
        'response_text',
        'response_json',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'response_json' => 'array',
            'submitted_at' => 'datetime',
        ];
    }

    /**
     * Get the user who submitted the response.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event evaluation session.
     */
    public function eventEvaluation(): BelongsTo
    {
        return $this->belongsTo(EventEvaluation::class);
    }

    /**
     * Get the question answered.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class, 'question_id');
    }
}
