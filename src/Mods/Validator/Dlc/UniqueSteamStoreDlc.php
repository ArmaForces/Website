<?php

declare(strict_types=1);

namespace App\Mods\Validator\Dlc;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class UniqueSteamStoreDlc extends Constraint
{
    public string $message = 'DLC associated with url "{{ dlcUrl }}" already exist.';
    public ?string $errorPath = null;

    public function __construct(
        string $message = null,
        string $errorPath = null,
        $options = null,
        array $groups = null,
        $payload = null
    ) {
        parent::__construct($options, $groups, $payload);

        $this->message = $message ?? $this->message;
        $this->errorPath = $errorPath ?? $this->errorPath;
    }

    public function getTargets(): array|string
    {
        return parent::CLASS_CONSTRAINT;
    }
}
