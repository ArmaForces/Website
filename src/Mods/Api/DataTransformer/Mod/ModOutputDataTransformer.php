<?php

declare(strict_types=1);

namespace App\Mods\Api\DataTransformer\Mod;

use App\Mods\Api\Output\Mod\ModOutput;
use App\Mods\Entity\Mod\AbstractMod;
use App\Mods\Entity\Mod\DirectoryMod;
use App\Mods\Entity\Mod\Enum\ModSourceEnum;
use App\Mods\Entity\Mod\Enum\ModTypeEnum;
use App\Mods\Entity\Mod\SteamWorkshopMod;

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
