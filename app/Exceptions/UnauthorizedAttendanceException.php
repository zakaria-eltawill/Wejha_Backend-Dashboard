<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

class UnauthorizedAttendanceException extends Exception
{
    public function __construct(
        string $message = 'User is not authorized to scan or record attendance.',
        int $code = 403,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
