<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\AbstractEntity;

interface RegisteredDataTransformerInterface extends DataTransformerInterface
{
    public function supportsTransformationToEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): bool;

    public function supportsTransformationFromEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): bool;
}
