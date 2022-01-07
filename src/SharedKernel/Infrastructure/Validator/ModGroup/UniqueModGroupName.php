<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Validator\ModGroup;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueModGroupName extends Constraint
{
    public string $message = 'Mod group with the same name "{{ modGroupName }}" already exist';
    public ?string $errorPath = null;

    public function getTargets(): array|string
    {
        return parent::CLASS_CONSTRAINT;
    }
}
