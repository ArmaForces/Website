<?php

declare(strict_types=1);

namespace App\ModManagement\Infrastructure\Validator\ModList;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueModListName extends Constraint
{
    public string $message = 'Mod list with the same name "{{ modListName }}" already exist';
    public ?string $errorPath = null;

    public function getTargets(): array|string
    {
        return parent::CLASS_CONSTRAINT;
    }
}
