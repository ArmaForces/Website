<?php

declare(strict_types=1);

namespace App\Api\DataTransformer\ModList;

use App\Api\DataTransformer\Dlc\DlcOutputDataTransformer;
use App\Api\DataTransformer\Mod\ModOutputDataTransformer;
use App\Api\Output\ModList\ModListDetailsOutput;
use App\Api\Output\ModList\ModListOutput;
use App\Entity\Dlc\Dlc;
use App\Entity\Mod\AbstractMod;
use App\Entity\ModList\ModList;
use App\Repository\Mod\ModRepository;

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
