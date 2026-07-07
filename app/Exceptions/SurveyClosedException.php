<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

class SurveyClosedException extends Exception
{
    public function __construct(
        string $message = 'Survey is closed or inactive.',
        int $code = 403,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
