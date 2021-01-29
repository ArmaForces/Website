<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\EntityInterface;

interface RegisteredDataTransformerInterface extends DataTransformerInterface
{
    public function supportsTransformationToEntity(FormDtoInterface $formDto, EntityInterface $entity = null): bool;

    public function supportsTransformationFromEntity(FormDtoInterface $formDto, EntityInterface $entity = null): bool;
}
