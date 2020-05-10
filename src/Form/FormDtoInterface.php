<?php

declare(strict_types=1);

namespace App\Form;

interface FormDtoInterface
{
    /**
     * @return string[]
     */
    public function resolveValidationGroups(): array;
}
