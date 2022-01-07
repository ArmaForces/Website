<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Validator;

use Symfony\Component\Validator\Constraints\Regex;

/**
 * @Annotation
 */
class SteamProfileId extends Regex
{
    /** @var string */
    public $message = 'Invalid Steam profile ID';

    /** @var string */
    public $pattern = '~^\d{17}$~';

    /**
     * @return string[]
     */
    public function getRequiredOptions(): array
    {
        return [];
    }
}
