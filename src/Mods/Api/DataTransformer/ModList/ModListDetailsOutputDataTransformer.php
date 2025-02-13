<?php

declare(strict_types=1);

namespace App\Mods\Api\DataTransformer\ModList;

use App\Mods\Api\DataTransformer\Dlc\DlcOutputDataTransformer;
use App\Mods\Api\DataTransformer\Mod\ModOutputDataTransformer;
use App\Mods\Api\Output\ModList\ModListDetailsOutput;
use App\Mods\Api\Output\ModList\ModListOutput;
use App\Mods\Entity\Dlc\Dlc;
use App\Mods\Entity\Mod\AbstractMod;
use App\Mods\Entity\ModList\AbstractModList;
use App\Mods\Entity\ModList\ExternalModList;
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

    public function transform(AbstractModList $modList): ModListOutput
    {
        $isApproved = null;
        if ($modList instanceof StandardModList) {
            $isApproved = $modList->isApproved();
        }

        $mods = [];
        if ($modList instanceof StandardModList) {
            $mods = array_map(
                fn (AbstractMod $mod) => $this->modOutputDataTransformer->transform($mod),
                $this->modRepository->findIncludedMods($modList)
            );
        }

        $dlcs = [];
        if ($modList instanceof StandardModList) {
            $dlcs = array_map(
                fn (Dlc $dlc) => $this->dlcOutputDataTransformer->transform($dlc),
                $modList->getDlcs()
            );
        }

        $url = null;
        if ($modList instanceof ExternalModList) {
            $url = $modList->getUrl();
        }

        return new ModListDetailsOutput(
            $modList->getId()->toString(),
            $modList->getName(),
            $modList->isActive(),
            $modList->getCreatedAt(),
            $modList->getLastUpdatedAt(),
            $isApproved,
            $mods,
            $dlcs,
            $url
        );
    }
}
