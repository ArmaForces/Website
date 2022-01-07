<?php

declare(strict_types=1);

namespace App\Validator\Dlc;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueSteamStoreDlc extends Constraint
{
    public string $message = 'DLC associated with url "{{ dlcUrl }}" already exist';
    public ?string $errorPath = null;

    public function getTargets(): array|string
    {
        return parent::CLASS_CONSTRAINT;
    }
}
