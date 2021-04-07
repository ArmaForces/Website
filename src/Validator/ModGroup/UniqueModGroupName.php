<?php

declare(strict_types=1);

namespace App\Validator\ModGroup;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueModGroupName extends Constraint
{
    public string $message = 'Mod group with the same name "{{ modGroupName }}" already exist';
    public ?string $errorPath = null;

    public function getTargets()
    {
        return parent::CLASS_CONSTRAINT;
    }
}
