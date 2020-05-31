<?php

declare(strict_types=1);

namespace App\Service\DataTransformer;

use App\Entity\EntityInterface;
use App\Entity\Mod\ModInterface;
use App\Entity\Mod\SteamWorkshopModInterface;
use App\Entity\ModList\ModList;
use App\Entity\ModList\ModListInterface;
use App\Form\FormDtoInterface;
use App\Form\ModList\Dto\ModListFormDto;

class ModListFormDtoTransformer implements FormDtoTransformerInterface
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
            $entity = new ModList($dto->getName());
        }

        $entity->setName($dto->getName());
        $entity->setDescription($dto->getDescription());

        $entity->clearMods();
        foreach ($dto->getMods() as $mod) {
            $entity->addMod($mod);
        }

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

        $dto->clearSteamWorkshopMods();
        foreach ($entity->getMods() as $mod) {
            if ($mod instanceof ModInterface) {
                $dto->addMod($mod);
            }
        }

        return $dto;
    }
}
