<?php

declare(strict_types=1);

namespace App\Api\DataTransformer\Mod;

use App\Api\Output\Mod\ModOutput;
use App\Entity\Mod\AbstractMod;
use App\Entity\Mod\DirectoryMod;
use App\Entity\Mod\Enum\ModSourceEnum;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\SteamWorkshopMod;

class ModOutputDataTransformer
{
    public function transform(AbstractMod $mod): ModOutput
    {
        return new ModOutput(
            $mod->getId()->toString(),
            $mod->getName(),
            $mod instanceof SteamWorkshopMod ? ModSourceEnum::STEAM_WORKSHOP->value : ModSourceEnum::DIRECTORY->value,
            $mod instanceof SteamWorkshopMod ? $mod->getType()->value : ModTypeEnum::SERVER_SIDE->value,
            $mod->getStatus()?->value,
            $mod instanceof SteamWorkshopMod ? $mod->getItemId() : null,
            $mod instanceof DirectoryMod ? $mod->getDirectory() : null,
            $mod->getCreatedAt(),
            $mod->getLastUpdatedAt(),
        );
    }
}
