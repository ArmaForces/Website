<?php

declare(strict_types=1);

namespace App\Form\ModGroup\DataTransformer;

use App\Entity\EntityInterface;
use App\Entity\Mod\ModInterface;
use App\Entity\ModGroup\ModGroup;
use App\Entity\ModGroup\ModGroupInterface;
use App\Form\FormDtoDataTransformerInterface;
use App\Form\FormDtoInterface;
use App\Form\ModGroup\Dto\ModGroupFormDto;
use Ramsey\Uuid\Uuid;

class ModGroupFormDtoDataTransformer implements FormDtoDataTransformerInterface
{
    /**
     * @param null|ModGroupInterface $entity
     *
     * @return ModGroupInterface
     */
    public function toEntity(FormDtoInterface $dto, EntityInterface $entity = null): EntityInterface
    {
        if (!$dto instanceof ModGroupFormDto) {
            throw new \InvalidArgumentException(sprintf('Dto must be of type of %s, got %s', ModGroupFormDto::class, \get_class($dto)));
        }

        if (!$entity instanceof ModGroupInterface) {
            $entity = new ModGroup(Uuid::uuid4(), $dto->getName());
        }

        $entity->setName($dto->getName());
        $entity->setDescription($dto->getDescription());
        $entity->setMods($dto->getMods());

        return $entity;
    }

    /**
     * @param null|ModGroupInterface $entity
     *
     * @return ModGroupFormDto
     */
    public function fromEntity(EntityInterface $entity = null): FormDtoInterface
    {
        $dto = new ModGroupFormDto();

        /** @var ModInterface $entity */
        if (!$entity instanceof ModGroupInterface) {
            return $dto;
        }

        $dto->setId($entity->getId());
        $dto->setName($entity->getName());
        $dto->setDescription($entity->getDescription());
        $dto->setMods($entity->getMods());

        return $dto;
    }
}
