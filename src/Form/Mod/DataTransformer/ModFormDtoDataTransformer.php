<?php

declare(strict_types=1);

namespace App\Form\Mod\DataTransformer;

use App\Entity\EntityInterface;
use App\Entity\Mod\DirectoryMod;
use App\Entity\Mod\Enum\ModSourceEnum;
use App\Entity\Mod\Enum\ModStatusEnum;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\ModInterface;
use App\Entity\Mod\SteamWorkshopMod;
use App\Form\FormDtoInterface;
use App\Form\Mod\Dto\ModFormDto;
use App\Form\RegisteredDataTransformerInterface;
use App\Service\SteamWorkshop\Helper\SteamWorkshopHelper;
use App\Service\SteamWorkshop\SteamWorkshopClient;
use Ramsey\Uuid\Uuid;

class ModFormDtoDataTransformer implements RegisteredDataTransformerInterface
{
    /** @var SteamWorkshopClient */
    protected $steamWorkshopClient;

    public function __construct(SteamWorkshopClient $steamWorkshopClient)
    {
        $this->steamWorkshopClient = $steamWorkshopClient;
    }

    /**
     * @param ModFormDto        $formDto
     * @param null|ModInterface $entity
     *
     * @return ModInterface
     */
    public function transformToEntity(FormDtoInterface $formDto, EntityInterface $entity = null): EntityInterface
    {
        /** @var ModSourceEnum $source */
        $source = ModSourceEnum::get($formDto->getSource());

        /** @var ModTypeEnum $type */
        $type = ModTypeEnum::get($formDto->getType());

        /** @var null|ModStatusEnum $status */
        $status = $formDto->getStatus() ? ModStatusEnum::get($formDto->getStatus()) : null;

        if ($source->is(ModSourceEnum::STEAM_WORKSHOP)) {
            $itemId = SteamWorkshopHelper::itemUrlToItemId($formDto->getUrl());
            $name = $formDto->getName() ?: substr($this->steamWorkshopClient->getWorkshopItemInfo($itemId)->getName(), 0, 255);

            if (!$entity instanceof SteamWorkshopMod) {
                $entity = new SteamWorkshopMod(Uuid::uuid4(), $name, $type, $itemId);
            } else {
                $entity->setName($name);
                $entity->setItemId($itemId);
            }
        } elseif ($source->is(ModSourceEnum::DIRECTORY)) {
            if (!$entity instanceof DirectoryMod) {
                $entity = new DirectoryMod(Uuid::uuid4(), $formDto->getName(), $type, $formDto->getDirectory());
            } else {
                $entity->setName($formDto->getName());
                $entity->setDirectory($formDto->getDirectory());
            }
        }

        $entity->setDescription($formDto->getDescription());
        $entity->setType($type);
        $entity->setStatus($status);
        $entity->setTags($formDto->getTags());

        return $entity;
    }

    /**
     * @param ModFormDto        $formDto
     * @param null|ModInterface $entity
     *
     * @return ModFormDto
     */
    public function transformFromEntity(FormDtoInterface $formDto, EntityInterface $entity = null): FormDtoInterface
    {
        /** @var ModInterface $entity */
        if (!$entity instanceof ModInterface) {
            return $formDto;
        }

        /** @var null|string $status */
        $status = $entity->getStatus() ? $entity->getStatus()->getValue() : null;

        $formDto->setId($entity->getId());
        $formDto->setName($entity->getName());
        $formDto->setDescription($entity->getDescription());
        $formDto->setType($entity->getType()->getValue());
        $formDto->setStatus($status);
        $formDto->setTags($entity->getTags());

        if ($entity instanceof SteamWorkshopMod) {
            $formDto->setSource(ModSourceEnum::STEAM_WORKSHOP);
            $itemId = $entity->getItemId();
            $url = SteamWorkshopHelper::itemIdToItemUrl($itemId);
            $formDto->setUrl($url);
        } elseif ($entity instanceof DirectoryMod) {
            $formDto->setSource(ModSourceEnum::DIRECTORY);
            $formDto->setDirectory($entity->getDirectory());
        }

        return $formDto;
    }

    public function supportsTransformationToEntity(FormDtoInterface $formDto, EntityInterface $entity = null): bool
    {
        return $formDto instanceof ModFormDto;
    }

    public function supportsTransformationFromEntity(FormDtoInterface $formDto, EntityInterface $entity = null): bool
    {
        return $formDto instanceof ModFormDto;
    }
}
