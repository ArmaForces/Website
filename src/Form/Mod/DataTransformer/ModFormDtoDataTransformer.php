<?php

declare(strict_types=1);

namespace App\Form\Mod\DataTransformer;

use App\Entity\AbstractEntity;
use App\Entity\Mod\AbstractMod;
use App\Entity\Mod\DirectoryMod;
use App\Entity\Mod\Enum\ModSourceEnum;
use App\Entity\Mod\Enum\ModStatusEnum;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\SteamWorkshopMod;
use App\Form\FormDtoInterface;
use App\Form\Mod\Dto\ModFormDto;
use App\Form\RegisteredDataTransformerInterface;
use App\Service\Steam\Helper\SteamHelper;
use App\Service\Steam\SteamApiClientInterface;
use Ramsey\Uuid\Uuid;

class ModFormDtoDataTransformer implements RegisteredDataTransformerInterface
{
    public function __construct(
        private SteamApiClientInterface $steamApiClient
    ) {
    }

    /**
     * @param ModFormDto       $formDto
     * @param null|AbstractMod $entity
     *
     * @return AbstractMod
     */
    public function transformToEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): AbstractEntity
    {
        /** @var ModSourceEnum $source */
        $source = ModSourceEnum::get($formDto->getSource());

        /** @var ModTypeEnum $type */
        $type = ModTypeEnum::get($formDto->getType());

        /** @var null|ModStatusEnum $status */
        $status = $formDto->getStatus() ? ModStatusEnum::get($formDto->getStatus()) : null;

        if ($source->is(ModSourceEnum::STEAM_WORKSHOP)) {
            $itemId = SteamHelper::itemUrlToItemId($formDto->getUrl());
            $name = $formDto->getName() ?? substr($this->steamApiClient->getWorkshopItemInfo($itemId)->getName(), 0, 255);

            if (!$entity instanceof SteamWorkshopMod) {
                return new SteamWorkshopMod(
                    Uuid::uuid4(),
                    $name,
                    $formDto->getDescription(),
                    $status,
                    $type,
                    $itemId
                );
            }

            $entity->update(
                $name,
                $formDto->getDescription(),
                $status,
                $type,
                $itemId
            );
        }

        if ($source->is(ModSourceEnum::DIRECTORY)) {
            if (!$entity instanceof DirectoryMod) {
                return new DirectoryMod(
                    Uuid::uuid4(),
                    $formDto->getName(),
                    $formDto->getDescription(),
                    $status,
                    $formDto->getDirectory(),
                );
            }

            $entity->update(
                $formDto->getName(),
                $formDto->getDescription(),
                $status,
                $formDto->getDirectory()
            );
        }

        return $entity;
    }

    /**
     * @param ModFormDto       $formDto
     * @param null|AbstractMod $entity
     *
     * @return ModFormDto
     */
    public function transformFromEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): FormDtoInterface
    {
        if (!$entity instanceof AbstractMod) {
            return $formDto;
        }

        $formDto->setId($entity->getId());
        $formDto->setName($entity->getName());
        $formDto->setDescription($entity->getDescription());

        /** @var null|string $status */
        $status = $entity->getStatus() ? $entity->getStatus()->getValue() : null;
        $formDto->setStatus($status);

        if ($entity instanceof SteamWorkshopMod) {
            $formDto->setType($entity->getType()->getValue());
            $formDto->setSource(ModSourceEnum::STEAM_WORKSHOP);
            $itemId = $entity->getItemId();
            $url = SteamHelper::itemIdToItemUrl($itemId);
            $formDto->setUrl($url);
        } elseif ($entity instanceof DirectoryMod) {
            $formDto->setType(ModTypeEnum::SERVER_SIDE);
            $formDto->setSource(ModSourceEnum::DIRECTORY);
            $formDto->setDirectory($entity->getDirectory());
        }

        return $formDto;
    }

    public function supportsTransformationToEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): bool
    {
        return $formDto instanceof ModFormDto;
    }

    public function supportsTransformationFromEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): bool
    {
        return $formDto instanceof ModFormDto;
    }
}
