<?php

declare(strict_types=1);

namespace App\Service\DataTransformer;

use App\Entity\EntityInterface;
use App\Entity\Mod\DirectoryMod;
use App\Entity\Mod\Enum\ModSourceEnum;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\ModInterface;
use App\Entity\Mod\SteamWorkshopMod;
use App\Form\FormDtoInterface;
use App\Form\Mod\Dto\ModFormDto;
use App\Service\SteamWorkshop\Helper\SteamWorkshopHelper;
use App\Service\SteamWorkshop\SteamWorkshopClient;

class ModFormDtoTransformer implements FormDtoTransformerInterface
{
    /** @var SteamWorkshopClient */
    protected $steamWorkshopClient;

    public function __construct(SteamWorkshopClient $steamWorkshopClient)
    {
        $this->steamWorkshopClient = $steamWorkshopClient;
    }

    /**
     * @param null|ModInterface $entity
     *
     * @return ModInterface
     */
    public function toEntity(FormDtoInterface $dto, EntityInterface $entity = null): EntityInterface
    {
        if (!$dto instanceof ModFormDto) {
            throw new \InvalidArgumentException(sprintf('Dto must be of type of %s, got %s', ModFormDto::class, \get_class($dto)));
        }

        /** @var ModSourceEnum $source */
        $source = ModSourceEnum::get($dto->getSource());

        /** @var ModTypeEnum $type */
        $type = ModTypeEnum::get($dto->getType());

        if ($source->is(ModSourceEnum::STEAM_WORKSHOP)) {
            $itemId = SteamWorkshopHelper::itemUrlToItemId($dto->getUrl());
            $name = $dto->getName() ?: substr($this->steamWorkshopClient->getWorkshopItemInfo($itemId)->getName(), 0, 255);

            if (!$entity instanceof SteamWorkshopMod) {
                $entity = new SteamWorkshopMod($name, $type, $itemId);
            } else {
                $entity->setName($name);
                $entity->setItemId($itemId);
            }
        } elseif ($source->is(ModSourceEnum::DIRECTORY)) {
            if (!$entity instanceof DirectoryMod) {
                $entity = new DirectoryMod($dto->getName(), $type, $dto->getDirectory());
            } else {
                $entity->setName($dto->getName());
                $entity->setDirectory($dto->getDirectory());
            }
        }

        $entity->setDescription($dto->getDescription());
        $entity->setType($type);

        return $entity;
    }

    /**
     * @param null|ModInterface $entity
     *
     * @return ModFormDto
     */
    public function fromEntity(EntityInterface $entity = null): FormDtoInterface
    {
        $dto = new ModFormDto();

        /** @var ModInterface $entity */
        if (!$entity instanceof ModInterface) {
            return $dto;
        }

        $dto->setId($entity->getId());
        $dto->setName($entity->getName());
        $dto->setDescription($entity->getDescription());
        $dto->setType($entity->getType()->getValue());

        if ($entity instanceof SteamWorkshopMod) {
            $dto->setSource(ModSourceEnum::STEAM_WORKSHOP);
            $itemId = $entity->getItemId();
            $url = SteamWorkshopHelper::itemIdToItemUrl($itemId);
            $dto->setUrl($url);
        } elseif ($entity instanceof DirectoryMod) {
            $dto->setSource(ModSourceEnum::DIRECTORY);
            $dto->setDirectory($entity->getDirectory());
        }

        return $dto;
    }
}
