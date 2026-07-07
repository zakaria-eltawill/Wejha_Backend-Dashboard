<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\QuestionType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyQuestion extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'survey_template_id',
        'type',
        'question_text_ar',
        'question_text_en',
        'description_ar',
        'description_en',
        'help_text_ar',
        'help_text_en',
        'options',
        'is_required',
        'score',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'type' => QuestionType::class,
            'options' => 'array',
            'is_required' => 'boolean',
            'score' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get the template that owns this question.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(SurveyTemplate::class, 'survey_template_id');
    }

    /**
     * Get the responses associated with this question.
     */
    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class, 'question_id');
    }
}
