<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;

class Notification extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title_ar',
        'title_en',
        'content_ar',
        'content_en',
        'recipient_type',
        'user_id',
        'role_id',
        'event_id',
        'scheduled_at',
        'delivered_at',
        'status',
        'delivery_logs',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'delivered_at' => 'datetime',
            'delivery_logs' => 'array',
        ];
    }

    /**
     * Get the individual user target (if recipient_type is individual).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the role target (if recipient_type is role).
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the event target (if recipient_type is event).
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
