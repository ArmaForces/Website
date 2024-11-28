<?php

declare(strict_types=1);

namespace App\Mods\Api\DataTransformer\ModList;

use App\Mods\Api\DataTransformer\Dlc\DlcOutputDataTransformer;
use App\Mods\Api\DataTransformer\Mod\ModOutputDataTransformer;
use App\Mods\Api\Output\ModList\ModListDetailsOutput;
use App\Mods\Api\Output\ModList\ModListOutput;
use App\Mods\Entity\Dlc\Dlc;
use App\Mods\Entity\Mod\AbstractMod;
use App\Mods\Entity\ModList\StandardModList;
use App\Mods\Repository\Mod\ModRepository;

class ModListDetailsOutputDataTransformer
{
    public function __construct(
        private ModOutputDataTransformer $modOutputDataTransformer,
        private DlcOutputDataTransformer $dlcOutputDataTransformer,
        private ModRepository $modRepository
    ) {
    }

    public function transform(StandardModList $standardModList): ModListOutput
    {
        return new ModListDetailsOutput(
            $standardModList->getId()->toString(),
            $standardModList->getName(),
            $standardModList->isActive(),
            $standardModList->isApproved(),
            $standardModList->getCreatedAt(),
            $standardModList->getLastUpdatedAt(),
            array_map(
                fn (AbstractMod $mod) => $this->modOutputDataTransformer->transform($mod),
                $this->modRepository->findIncludedMods($standardModList)
            ),
            array_map(
                fn (Dlc $dlc) => $this->dlcOutputDataTransformer->transform($dlc),
                $standardModList->getDlcs()
            ),
        );
    }
}
