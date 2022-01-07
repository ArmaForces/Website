<?php

declare(strict_types=1);

namespace App\SharedKernel\UserInterface\Http\Form;

use App\SharedKernel\Domain\Model\EntityInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem]
interface RegisteredDataTransformerInterface extends DataTransformerInterface
{
    public function supportsTransformationToEntity(FormDtoInterface $formDto, EntityInterface $entity = null): bool;

    public function supportsTransformationFromEntity(FormDtoInterface $formDto, EntityInterface $entity = null): bool;
}
