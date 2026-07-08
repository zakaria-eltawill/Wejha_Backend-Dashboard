<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyTemplate extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name_ar',
        'name_en',
        'version',
        'status',
        'category',
        'type',
        'is_reusable',
        'description_ar',
        'description_en',
    ];

    protected function casts(): array
    {
        return [
            'is_reusable' => 'boolean',
        ];
    }

    /**
     * Get the questions belonging to this template.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(SurveyQuestion::class);
    }

    /**
     * Get the evaluations linked to this template.
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(EventEvaluation::class);
    }
}
