<?php

declare(strict_types=1);

namespace App\Mods\Form\Mod\DataTransformer;

use App\Mods\Entity\Mod\AbstractMod;
use App\Mods\Entity\Mod\DirectoryMod;
use App\Mods\Entity\Mod\Enum\ModSourceEnum;
use App\Mods\Entity\Mod\Enum\ModStatusEnum;
use App\Mods\Entity\Mod\Enum\ModTypeEnum;
use App\Mods\Entity\Mod\SteamWorkshopMod;
use App\Mods\Form\Mod\Dto\ModFormDto;
use App\Shared\Service\IdentifierFactory\IdentifierFactoryInterface;
use App\Shared\Service\SteamApiClient\Helper\SteamHelper;
use App\Shared\Service\SteamApiClient\SteamApiClientInterface;

class ModFormDtoDataTransformer
{
    public function __construct(
        private SteamApiClientInterface $steamApiClient,
        private IdentifierFactoryInterface $identifierFactory
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
                    $this->identifierFactory->create(),
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
                    $this->identifierFactory->create(),
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
