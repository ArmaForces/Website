<?php

declare(strict_types=1);

namespace App\Api\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Api\Dto\ModOutput;
use App\Entity\Mod\DirectoryMod;
use App\Entity\Mod\Enum\ModSourceEnum;
use App\Entity\Mod\ModInterface;
use App\Entity\Mod\SteamWorkshopMod;

class ModOutputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     *
     * @param ModInterface $mod
     */
    public function transform($mod, string $to, array $context = []): ModOutput
    {
        $output = new ModOutput();

        $output->setId($mod->getId());
        $output->setName($mod->getName());
        $output->setCreatedAt($mod->getCreatedAt());
        $output->setLastUpdatedAt($mod->getLastUpdatedAt());

        $output->setType($mod->getType()->getValue());

        if ($mod instanceof SteamWorkshopMod) {
            $output->setSource(ModSourceEnum::STEAM_WORKSHOP);
            $output->setItemId($mod->getItemId());
        } elseif ($mod instanceof DirectoryMod) {
            $output->setSource(ModSourceEnum::DIRECTORY);
            $output->setDirectory($mod->getDirectory());
        }

        return $output;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return ModOutput::class === $to && $data instanceof ModInterface;
    }
}
