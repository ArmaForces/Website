<?php

declare(strict_types=1);

namespace App\Api\DataTransformer\Mod;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Api\Output\Mod\ModOutput;
use App\Entity\Mod\DirectoryMod;
use App\Entity\Mod\Enum\ModSourceEnum;
use App\Entity\Mod\ModInterface;
use App\Entity\Mod\SteamWorkshopMod;

class ModOutputDataTransformer implements DataTransformerInterface
{
    public function transform($object, string $to, array $context = []): ModOutput
    {
        /** @var ModInterface $mod */
        $mod = $object;

        $output = new ModOutput();

        $output->setId($mod->getId()->toString());
        $output->setName($mod->getName());
        $output->setCreatedAt($mod->getCreatedAt());
        $output->setLastUpdatedAt($mod->getLastUpdatedAt());

        $output->setType($mod->getType()->getValue());

        /** @var null|string $status */
        $status = $mod->getStatus() ? $mod->getStatus()->getValue() : null;
        $output->setStatus($status);

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
