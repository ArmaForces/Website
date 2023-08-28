<?php

declare(strict_types=1);

namespace App\Api\DataTransformer\Mod;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Api\Output\Mod\ModOutput;
use App\Entity\Mod\AbstractMod;
use App\Entity\Mod\DirectoryMod;
use App\Entity\Mod\Enum\ModSourceEnum;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\SteamWorkshopMod;

class ModOutputDataTransformer implements DataTransformerInterface
{
    public function transform($object, string $to, array $context = []): ModOutput
    {
        /** @var AbstractMod $object */
        $output = new ModOutput();

        $output->setId($object->getId()->toString());
        $output->setName($object->getName());
        $output->setStatus($object->getStatus()?->value);
        $output->setCreatedAt($object->getCreatedAt());
        $output->setLastUpdatedAt($object->getLastUpdatedAt());

        if ($object instanceof SteamWorkshopMod) {
            $output->setType($object->getType()->value);
            $output->setSource(ModSourceEnum::STEAM_WORKSHOP->value);
            $output->setItemId($object->getItemId());
        } elseif ($object instanceof DirectoryMod) {
            $output->setType(ModTypeEnum::SERVER_SIDE->value);
            $output->setSource(ModSourceEnum::DIRECTORY->value);
            $output->setDirectory($object->getDirectory());
        }

        return $output;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return ModOutput::class === $to && $data instanceof AbstractMod;
    }
}
