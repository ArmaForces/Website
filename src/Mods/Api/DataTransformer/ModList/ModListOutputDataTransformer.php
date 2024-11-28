<?php

declare(strict_types=1);

namespace App\Mods\Api\DataTransformer\ModList;

use App\Mods\Api\Output\ModList\ModListOutput;
use App\Mods\Entity\ModList\StandardModList;

class ModListOutputDataTransformer
{
    public function transform(StandardModList $standardModList): ModListOutput
    {
        return new ModListOutput(
            $standardModList->getId()->toString(),
            $standardModList->getName(),
            $standardModList->isActive(),
            $standardModList->isApproved(),
            $standardModList->getCreatedAt(),
            $standardModList->getLastUpdatedAt(),
        );
    }
}
