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
use App\Entity\Dlc\Dlc;
use App\Entity\ModList\ModList;
use App\Repository\Mod\ModRepository;

class ModListDetailsOutputDataTransformer implements DataTransformerInterface
{
    public function __construct(
        private ModOutputDataTransformer $modDataTransformer,
        private DlcOutputDataTransformer $dlcOutputDataTransformer,
        private ModRepository $modRepository
    ) {
    }

    public function transform($object, string $to, array $context = []): ModListOutput
    {
        /** @var ModList $object */
        $output = new ModListDetailsOutput();

        $output->setId($object->getId()->toString());
        $output->setName($object->getName());
        $output->setActive($object->isActive());
        $output->setApproved($object->isApproved());
        $output->setCreatedAt($object->getCreatedAt());
        $output->setLastUpdatedAt($object->getLastUpdatedAt());

        $mods = [];
        foreach ($this->modRepository->findIncludedMods($object) as $mod) {
            $mods[] = $this->modDataTransformer->transform($mod, ModOutput::class, $context);
        }
        $output->setMods($mods);

        $dlcs = array_map(
            fn (Dlc $dlc) => $this->dlcOutputDataTransformer->transform($dlc, DlcOutput::class, $context),
            $object->getDlcs()
        );
        $output->setDlcs($dlcs);

        return $output;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return ModListDetailsOutput::class === $to && $data instanceof ModList;
    }
}
