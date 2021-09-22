<?php

declare(strict_types=1);

namespace App\Api\DataTransformer\ModList;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Api\DataTransformer\Dlc\DlcOutputDataTransformer;
use App\Api\DataTransformer\Mod\ModOutputDataTransformer;
use App\Api\Output\Dlc\DlcOutput;
use App\Api\Output\Mod\ModOutput;
use App\Api\Output\ModList\ModListDetailsOutput;
use App\Api\Output\ModList\ModListOutput;
use App\Entity\Dlc\DlcInterface;
use App\Entity\ModList\ModList;
use App\Entity\ModList\ModListInterface;
use App\Repository\Mod\ModRepository;

class ModListDetailsOutputDataTransformer implements DataTransformerInterface
{
    protected ModOutputDataTransformer $modDataTransformer;
    protected DlcOutputDataTransformer $dlcOutputDataTransformer;
    protected ModRepository $modRepository;

    public function __construct(
        ModOutputDataTransformer $modDataTransformer,
        DlcOutputDataTransformer $dlcOutputDataTransformer,
        ModRepository $modRepository
    ) {
        $this->modDataTransformer = $modDataTransformer;
        $this->dlcOutputDataTransformer = $dlcOutputDataTransformer;
        $this->modRepository = $modRepository;
    }

    /**
     * {@inheritdoc}
     *
     * @param ModListInterface $modList
     */
    public function transform($modList, string $to, array $context = []): ModListOutput
    {
        $output = new ModListDetailsOutput();

        $output->setId($modList->getId()->toString());
        $output->setName($modList->getName());
        $output->setActive($modList->isActive());
        $output->setApproved($modList->isApproved());
        $output->setCreatedAt($modList->getCreatedAt());
        $output->setLastUpdatedAt($modList->getLastUpdatedAt());

        $mods = [];
        foreach ($this->modRepository->findIncludedMods($modList) as $mod) {
            $mods[] = $this->modDataTransformer->transform($mod, ModOutput::class, $context);
        }
        $output->setMods($mods);

        $dlcs = array_map(
            fn (DlcInterface $dlc) => $this->dlcOutputDataTransformer->transform($dlc, DlcOutput::class, $context),
            $modList->getDlcs()
        );
        $output->setDlcs($dlcs);

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return ModListDetailsOutput::class === $to && $data instanceof ModList;
    }
}
