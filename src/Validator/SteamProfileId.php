<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraints\Regex;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class SteamProfileId extends Regex
{
    /** @var string */
    public $message = 'Invalid Steam profile ID.';

    /** @var string */
    public $pattern = '~^\d{17}$~';

    public function __construct(
        string $pattern = null,
        string $message = null,
        string $htmlPattern = null,
        bool $match = null,
        callable $normalizer = null,
        array $groups = null,
        $payload = null,
        array $options = []
    ) {
        parent::__construct(
            $pattern ?? $this->pattern,
            $message,
            $htmlPattern,
            $match,
            $normalizer,
            $groups,
            $payload,
            $options
        );
    }

    /**
     * @return string[]
     */
    public function getRequiredOptions(): array
    {
        return [];
    }
}
