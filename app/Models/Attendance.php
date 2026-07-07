<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'attendance';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'registration_id',
        'scanner_user_id',
        'scan_time',
        'device',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'scan_time' => 'datetime',
        ];
    }

    /**
     * Get the registration details for this attendance.
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Get the operator scanner user.
     */
    public function scannerUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scanner_user_id');
    }
}
