<?php

declare(strict_types=1);

namespace App\SharedKernel\UserInterface\Http\Form;

interface FormDtoInterface
{
    /**
     * @return string[]
     */
    public function resolveValidationGroups(): array;
}
