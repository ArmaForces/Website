<?php

declare(strict_types=1);

namespace App\Mods\Api\DataTransformer\ModList;

use App\Mods\Api\Output\ModList\ModListOutput;
use App\Mods\Entity\ModList\AbstractModList;
use App\Mods\Entity\ModList\StandardModList;

class ModListOutputDataTransformer
{
    public function transform(AbstractModList $modList): ModListOutput
    {
        $isApproved = null;
        if ($modList instanceof StandardModList) {
            $isApproved = $modList->isApproved();
        }

        return new ModListOutput(
            $modList->getId()->toString(),
            $modList->getName(),
            $modList->isActive(),
            $modList->getCreatedAt(),
            $modList->getLastUpdatedAt(),
            $isApproved,
        );
    }
}
