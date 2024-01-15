<?php

declare(strict_types=1);

namespace App\Form\Mod\DataTransformer;

use App\Entity\Mod\AbstractMod;
use App\Entity\Mod\DirectoryMod;
use App\Entity\Mod\Enum\ModSourceEnum;
use App\Entity\Mod\Enum\ModStatusEnum;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\SteamWorkshopMod;
use App\Form\Mod\Dto\ModFormDto;
use App\Service\SteamApiClient\Helper\SteamHelper;
use App\Service\SteamApiClient\SteamApiClientInterface;
use Ramsey\Uuid\Uuid;

class ModFormDtoDataTransformer
{
    public function __construct(
        private SteamApiClientInterface $steamApiClient
    ) {
    }

    public function transformToEntity(ModFormDto $modFormDto, AbstractMod $mod = null): AbstractMod
    {
        $source = ModSourceEnum::from($modFormDto->getSource());
        $type = ModTypeEnum::from($modFormDto->getType());

        /** @var null|ModStatusEnum $status */
        $status = $modFormDto->getStatus() ? ModStatusEnum::from($modFormDto->getStatus()) : null;

        if (ModSourceEnum::STEAM_WORKSHOP === $source) {
            $itemId = SteamHelper::itemUrlToItemId($modFormDto->getUrl());
            $name = $modFormDto->getName() ?? substr($this->steamApiClient->getWorkshopItemInfo($itemId)->getName(), 0, 255);

            if (!$mod instanceof SteamWorkshopMod) {
                return new SteamWorkshopMod(
                    Uuid::uuid4(),
                    $name,
                    $modFormDto->getDescription(),
                    $status,
                    $type,
                    $itemId
                );
            }

            $mod->update(
                $name,
                $modFormDto->getDescription(),
                $status,
                $type,
                $itemId
            );
        }

        if (ModSourceEnum::DIRECTORY === $source) {
            if (!$mod instanceof DirectoryMod) {
                return new DirectoryMod(
                    Uuid::uuid4(),
                    $modFormDto->getName(),
                    $modFormDto->getDescription(),
                    $status,
                    $modFormDto->getDirectory(),
                );
            }

            $mod->update(
                $modFormDto->getName(),
                $modFormDto->getDescription(),
                $status,
                $modFormDto->getDirectory()
            );
        }

        return $mod;
    }

    public function transformFromEntity(ModFormDto $modFormDto, AbstractMod $mod = null): ModFormDto
    {
        if (!$mod instanceof AbstractMod) {
            return $modFormDto;
        }

        $modFormDto->setId($mod->getId());
        $modFormDto->setName($mod->getName());
        $modFormDto->setDescription($mod->getDescription());

        /** @var null|string $status */
        $status = $mod->getStatus() ? $mod->getStatus()->value : null;
        $modFormDto->setStatus($status);

        if ($mod instanceof SteamWorkshopMod) {
            $modFormDto->setType($mod->getType()->value);
            $modFormDto->setSource(ModSourceEnum::STEAM_WORKSHOP->value);
            $itemId = $mod->getItemId();
            $url = SteamHelper::itemIdToItemUrl($itemId);
            $modFormDto->setUrl($url);
        } elseif ($mod instanceof DirectoryMod) {
            $modFormDto->setType(ModTypeEnum::SERVER_SIDE->value);
            $modFormDto->setSource(ModSourceEnum::DIRECTORY->value);
            $modFormDto->setDirectory($mod->getDirectory());
        }

        return $modFormDto;
    }
}
