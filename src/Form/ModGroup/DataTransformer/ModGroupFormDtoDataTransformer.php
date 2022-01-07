<?php

declare(strict_types=1);

namespace App\Form\ModGroup\DataTransformer;

use App\Entity\Mod\ModInterface;
use App\Entity\ModGroup\ModGroup;
use App\Entity\ModGroup\ModGroupInterface;
use App\Form\ModGroup\Dto\ModGroupFormDto;
use App\SharedKernel\Domain\Model\EntityInterface;
use App\SharedKernel\UserInterface\Http\Form\FormDtoInterface;
use App\SharedKernel\UserInterface\Http\Form\RegisteredDataTransformerInterface;
use Ramsey\Uuid\Uuid;

class ModGroupFormDtoDataTransformer implements RegisteredDataTransformerInterface
{
    /**
     * @param ModGroupFormDto        $formDto
     * @param null|ModGroupInterface $entity
     *
     * @return ModGroupInterface
     */
    public function transformToEntity(FormDtoInterface $formDto, EntityInterface $entity = null): EntityInterface
    {
        if (!$entity instanceof ModGroupInterface) {
            $entity = new ModGroup(Uuid::uuid4(), $formDto->getName());
        }

        $entity->setName($formDto->getName());
        $entity->setDescription($formDto->getDescription());
        $entity->setMods($formDto->getMods());

        return $entity;
    }

    /**
     * @param ModGroupFormDto        $formDto
     * @param null|ModGroupInterface $entity
     *
     * @return ModGroupFormDto
     */
    public function transformFromEntity(FormDtoInterface $formDto, EntityInterface $entity = null): FormDtoInterface
    {
        /** @var ModInterface $entity */
        if (!$entity instanceof ModGroupInterface) {
            return $formDto;
        }

        $formDto->setId($entity->getId());
        $formDto->setName($entity->getName());
        $formDto->setDescription($entity->getDescription());
        $formDto->setMods($entity->getMods());

        return $formDto;
    }

    public function supportsTransformationToEntity(FormDtoInterface $formDto, EntityInterface $entity = null): bool
    {
        return $formDto instanceof ModGroupFormDto;
    }

    public function supportsTransformationFromEntity(FormDtoInterface $formDto, EntityInterface $entity = null): bool
    {
        return $formDto instanceof ModGroupFormDto;
    }
}
