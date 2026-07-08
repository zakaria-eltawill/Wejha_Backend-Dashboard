<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification as BaseNotification;

class DatabaseNotification extends BaseNotification
{
    protected $table = 'realtime_notifications';

    protected $keyType = 'string';
    public $incrementing = false;
}
