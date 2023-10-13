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
use App\Service\SteamApiClient\Helper\SteamHelper;
use App\Service\SteamApiClient\SteamApiClientInterface;
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
        $source = ModSourceEnum::from($formDto->getSource());
        $type = ModTypeEnum::from($formDto->getType());

        /** @var null|ModStatusEnum $status */
        $status = $formDto->getStatus() ? ModStatusEnum::from($formDto->getStatus()) : null;

        if (ModSourceEnum::STEAM_WORKSHOP === $source) {
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

        if (ModSourceEnum::DIRECTORY === $source) {
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
        $status = $entity->getStatus() ? $entity->getStatus()->value : null;
        $formDto->setStatus($status);

        if ($entity instanceof SteamWorkshopMod) {
            $formDto->setType($entity->getType()->value);
            $formDto->setSource(ModSourceEnum::STEAM_WORKSHOP->value);
            $itemId = $entity->getItemId();
            $url = SteamHelper::itemIdToItemUrl($itemId);
            $formDto->setUrl($url);
        } elseif ($entity instanceof DirectoryMod) {
            $formDto->setType(ModTypeEnum::SERVER_SIDE->value);
            $formDto->setSource(ModSourceEnum::DIRECTORY->value);
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
