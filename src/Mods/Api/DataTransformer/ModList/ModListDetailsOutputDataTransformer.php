<?php

declare(strict_types=1);

namespace App\Mods\Api\DataTransformer\ModList;

use App\Mods\Api\DataTransformer\Dlc\DlcOutputDataTransformer;
use App\Mods\Api\DataTransformer\Mod\ModOutputDataTransformer;
use App\Mods\Api\Output\ModList\ModListDetailsOutput;
use App\Mods\Api\Output\ModList\ModListOutput;
use App\Mods\Entity\Dlc\Dlc;
use App\Mods\Entity\Mod\AbstractMod;
use App\Mods\Entity\ModList\ModList;
use App\Mods\Repository\Mod\ModRepository;

class ModListDetailsOutputDataTransformer
{
    public function __construct(
        private ModOutputDataTransformer $modOutputDataTransformer,
        private DlcOutputDataTransformer $dlcOutputDataTransformer,
        private ModRepository $modRepository
    ) {
    }

    public function transform(ModList $modList): ModListOutput
    {
        return new ModListDetailsOutput(
            $modList->getId()->toString(),
            $modList->getName(),
            $modList->isActive(),
            $modList->isApproved(),
            $modList->getCreatedAt(),
            $modList->getLastUpdatedAt(),
            array_map(
                fn (AbstractMod $mod) => $this->modOutputDataTransformer->transform($mod),
                $this->modRepository->findIncludedMods($modList)
            ),
            array_map(
                fn (Dlc $dlc) => $this->dlcOutputDataTransformer->transform($dlc),
                $modList->getDlcs()
            ),
        );
    }
}
