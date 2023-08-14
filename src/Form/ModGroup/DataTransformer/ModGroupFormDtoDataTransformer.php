<?php

declare(strict_types=1);

namespace App\Form\ModGroup\DataTransformer;

use App\Entity\AbstractEntity;
use App\Entity\ModGroup\ModGroup;
use App\Form\FormDtoInterface;
use App\Form\ModGroup\Dto\ModGroupFormDto;
use App\Form\RegisteredDataTransformerInterface;
use Ramsey\Uuid\Uuid;

class ModGroupFormDtoDataTransformer implements RegisteredDataTransformerInterface
{
    /**
     * @param ModGroupFormDto $formDto
     * @param null|ModGroup   $entity
     *
     * @return ModGroup
     */
    public function transformToEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): AbstractEntity
    {
        if (!$entity instanceof ModGroup) {
            return new ModGroup(
                Uuid::uuid4(),
                $formDto->getName(),
                $formDto->getDescription(),
                $formDto->getMods()
            );
        }

        $entity->update(
            $formDto->getName(),
            $formDto->getDescription(),
            $formDto->getMods()
        );

        return $entity;
    }

    /**
     * @param ModGroupFormDto $formDto
     * @param null|ModGroup   $entity
     *
     * @return ModGroupFormDto
     */
    public function transformFromEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): FormDtoInterface
    {
        if (!$entity instanceof ModGroup) {
            return $formDto;
        }

        $formDto->setId($entity->getId());
        $formDto->setName($entity->getName());
        $formDto->setDescription($entity->getDescription());
        $formDto->setMods($entity->getMods());

        return $formDto;
    }

    public function supportsTransformationToEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): bool
    {
        return $formDto instanceof ModGroupFormDto;
    }

    public function supportsTransformationFromEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): bool
    {
        return $formDto instanceof ModGroupFormDto;
    }
}
