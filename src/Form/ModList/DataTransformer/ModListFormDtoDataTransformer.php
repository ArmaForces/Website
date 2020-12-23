<?php

declare(strict_types=1);

namespace App\Form\ModList\DataTransformer;

use App\Entity\EntityInterface;
use App\Entity\Mod\ModInterface;
use App\Entity\ModList\ModList;
use App\Entity\ModList\ModListInterface;
use App\Form\FormDtoDataTransformerInterface;
use App\Form\FormDtoInterface;
use App\Form\ModList\Dto\ModListFormDto;
use Ramsey\Uuid\Uuid;

class ModListFormDtoDataTransformer implements FormDtoDataTransformerInterface
{
    /**
     * @param null|ModListInterface $entity
     *
     * @return ModListInterface
     */
    public function toEntity(FormDtoInterface $dto, EntityInterface $entity = null): EntityInterface
    {
        if (!$dto instanceof ModListFormDto) {
            throw new \InvalidArgumentException(sprintf('Dto must be of type of %s, got %s', ModListFormDto::class, \get_class($dto)));
        }

        if (!$entity instanceof ModListInterface) {
            $entity = new ModList(Uuid::uuid4(), $dto->getName());
        }

        $entity->setName($dto->getName());
        $entity->setDescription($dto->getDescription());
        $entity->setMods($dto->getMods());
        $entity->setModGroups($dto->getModGroups());
        $entity->setOwner($dto->getOwner());
        $entity->setActive($dto->isActive());
        $entity->setApproved($dto->isApproved());

        return $entity;
    }

    /**
     * @param null|ModListInterface $entity
     *
     * @return ModListFormDto
     */
    public function fromEntity(EntityInterface $entity = null): FormDtoInterface
    {
        $dto = new ModListFormDto();

        /** @var ModInterface $entity */
        if (!$entity instanceof ModListInterface) {
            return $dto;
        }

        $dto->setId($entity->getId());
        $dto->setName($entity->getName());
        $dto->setDescription($entity->getDescription());
        $dto->setMods($entity->getMods());
        $dto->setModGroups($entity->getModGroups());
        $dto->setOwner($entity->getOwner());
        $dto->setActive($entity->isActive());
        $dto->setApproved($entity->isApproved());

        return $dto;
    }
}
