<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

class EventCapacityExceededException extends Exception
{
    public function __construct(
        string $message = 'Event capacity exceeded.',
        int $code = 422,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
