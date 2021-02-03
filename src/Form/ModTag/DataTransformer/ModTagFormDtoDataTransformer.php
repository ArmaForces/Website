<?php

declare(strict_types=1);

namespace App\Form\ModTag\DataTransformer;

use App\Entity\EntityInterface;
use App\Entity\Mod\ModInterface;
use App\Entity\ModTag\ModTag;
use App\Entity\ModTag\ModTagInterface;
use App\Form\FormDtoInterface;
use App\Form\ModTag\Dto\ModTagFormDto;
use App\Form\RegisteredDataTransformerInterface;
use Ramsey\Uuid\Uuid;

class ModTagFormDtoDataTransformer implements RegisteredDataTransformerInterface
{
    /**
     * @param ModTagFormDto        $formDto
     * @param null|ModTagInterface $entity
     *
     * @return ModTagInterface
     */
    public function transformToEntity(FormDtoInterface $formDto, EntityInterface $entity = null): EntityInterface
    {
        if (!$entity instanceof ModTagInterface) {
            $entity = new ModTag(Uuid::uuid4(), $formDto->getName());
        }

        $entity->setName($formDto->getName());
        $entity->setDescription($formDto->getDescription());

        return $entity;
    }

    /**
     * @param ModTagFormDto        $formDto
     * @param null|ModTagInterface $entity
     *
     * @return ModTagFormDto
     */
    public function transformFromEntity(FormDtoInterface $formDto, EntityInterface $entity = null): FormDtoInterface
    {
        /** @var ModInterface $entity */
        if (!$entity instanceof ModTagInterface) {
            return $formDto;
        }

        $formDto->setId($entity->getId());
        $formDto->setName($entity->getName());
        $formDto->setDescription($entity->getDescription());

        return $formDto;
    }

    public function supportsTransformationToEntity(FormDtoInterface $formDto, EntityInterface $entity = null): bool
    {
        return $formDto instanceof ModTagFormDto;
    }

    public function supportsTransformationFromEntity(FormDtoInterface $formDto, EntityInterface $entity = null): bool
    {
        return $formDto instanceof ModTagFormDto;
    }
}
