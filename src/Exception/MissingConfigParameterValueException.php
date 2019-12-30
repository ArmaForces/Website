<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

class MissingConfigParameterValueException extends \RuntimeException
{
    public function __construct(string $configKey, int $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Make sure that parameter "%s" was set in services configuration!', $configKey);

        parent::__construct($message, $code, $previous);
    }
}
