<?php

declare(strict_types=1);

namespace App\ModManagement\UserInterface\Http\Api\DataTransformer\ModList;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\ModManagement\Domain\Model\Dlc\DlcInterface;
use App\ModManagement\Domain\Model\ModList\ModList;
use App\ModManagement\Domain\Model\ModList\ModListInterface;
use App\ModManagement\Infrastructure\Persistence\Mod\ModRepository;
use App\ModManagement\UserInterface\Http\Api\DataTransformer\Dlc\DlcOutputDataTransformer;
use App\ModManagement\UserInterface\Http\Api\DataTransformer\Mod\ModOutputDataTransformer;
use App\ModManagement\UserInterface\Http\Api\Output\Dlc\DlcOutput;
use App\ModManagement\UserInterface\Http\Api\Output\Mod\ModOutput;
use App\ModManagement\UserInterface\Http\Api\Output\ModList\ModListDetailsOutput;
use App\ModManagement\UserInterface\Http\Api\Output\ModList\ModListOutput;

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
        /** @var ModListInterface $object */
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
            fn (DlcInterface $dlc) => $this->dlcOutputDataTransformer->transform($dlc, DlcOutput::class, $context),
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
