<?php

declare(strict_types=1);

namespace App\Validator\Mod;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueDirectoryMod extends Constraint
{
    public string $message = 'Mod associated with directory "{{ directoryName }}" already exist';
    public ?string $errorPath = null;

    public function getTargets(): array|string
    {
        return parent::CLASS_CONSTRAINT;
    }
}
