<?php

declare(strict_types=1);

namespace App\Mods\Api\DataTransformer\ModList;

use App\Mods\Api\Output\ModList\ModListOutput;
use App\Mods\Entity\ModList\ModList;

class ModListOutputDataTransformer
{
    public function transform(ModList $modList): ModListOutput
    {
        return new ModListOutput(
            $modList->getId()->toString(),
            $modList->getName(),
            $modList->isActive(),
            $modList->isApproved(),
            $modList->getCreatedAt(),
            $modList->getLastUpdatedAt(),
        );
    }
}
