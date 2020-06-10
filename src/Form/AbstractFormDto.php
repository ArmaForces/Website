<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Validator\Constraint;

abstract class AbstractFormDto implements FormDtoInterface
{
    public function resolveValidationGroups(): array
    {
        return [Constraint::DEFAULT_GROUP];
    }
}
