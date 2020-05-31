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

        $output->id = $mod->getId();
        $output->type = $mod->getType()->getValue();
        $output->name = $mod->getName();
        $output->createdAt = $mod->getCreatedAt();
        $output->lastUpdatedAt = $mod->getLastUpdatedAt();

        if ($mod instanceof SteamWorkshopMod) {
            $output->source = ModSourceEnum::STEAM_WORKSHOP;
            $output->workshopId = $mod->getItemId();
        } elseif ($mod instanceof DirectoryMod) {
            $output->source = ModSourceEnum::DIRECTORY;
            $output->directory = $mod->getDirectory();
        }

        return $output;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return ModOutput::class === $to && $data instanceof ModInterface;
    }
}
