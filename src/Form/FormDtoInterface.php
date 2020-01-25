<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\EntityInterface;

interface FormDtoInterface
{
    public static function fromEntity(EntityInterface $entity = null): self;

    public function toEntity(EntityInterface $entity = null): EntityInterface;

    /**
     * @return string[]
     */
    public function resolveValidationGroups(): array;
}
