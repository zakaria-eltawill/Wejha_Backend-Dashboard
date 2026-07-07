<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

class DuplicateRegistrationException extends Exception
{
    public function __construct(
        string $message = 'User is already registered for this event.',
        int $code = 422,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
