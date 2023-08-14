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
        $output->setCreatedAt($object->getCreatedAt());
        $output->setLastUpdatedAt($object->getLastUpdatedAt());

        /** @var null|string $status */
        $status = $object->getStatus() ? $object->getStatus()->getValue() : null;
        $output->setStatus($status);

        if ($object instanceof SteamWorkshopMod) {
            $output->setType($object->getType()->getValue());
            $output->setSource(ModSourceEnum::STEAM_WORKSHOP);
            $output->setItemId($object->getItemId());
        } elseif ($object instanceof DirectoryMod) {
            $output->setType(ModTypeEnum::SERVER_SIDE);
            $output->setSource(ModSourceEnum::DIRECTORY);
            $output->setDirectory($object->getDirectory());
        }

        return $output;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return ModOutput::class === $to && $data instanceof AbstractMod;
    }
}
