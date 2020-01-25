<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\EntityInterface;
use Symfony\Component\Validator\Constraint;

abstract class AbstractFormDto implements FormDtoInterface
{
    abstract public static function fromEntity(EntityInterface $entity = null): FormDtoInterface;

    abstract public function toEntity(EntityInterface $entity = null): EntityInterface;

    public function resolveValidationGroups(): array
    {
        return [Constraint::DEFAULT_GROUP];
    }
}
