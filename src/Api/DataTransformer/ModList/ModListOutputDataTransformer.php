<?php

declare(strict_types=1);

namespace App\Api\DataTransformer\ModList;

use App\Api\Output\ModList\ModListOutput;
use App\Entity\ModList\ModList;

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
