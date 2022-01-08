<?php

declare(strict_types=1);

namespace App\ModManagement\UserInterface\Http\Api\DataTransformer\Mod;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\ModManagement\Domain\Model\Mod\DirectoryMod;
use App\ModManagement\Domain\Model\Mod\Enum\ModSourceEnum;
use App\ModManagement\Domain\Model\Mod\ModInterface;
use App\ModManagement\Domain\Model\Mod\SteamWorkshopMod;
use App\ModManagement\UserInterface\Http\Api\Output\Mod\ModOutput;

class ModOutputDataTransformer implements DataTransformerInterface
{
    public function transform($object, string $to, array $context = []): ModOutput
    {
        /** @var ModInterface $object */
        $output = new ModOutput();

        $output->setId($object->getId()->toString());
        $output->setName($object->getName());
        $output->setCreatedAt($object->getCreatedAt());
        $output->setLastUpdatedAt($object->getLastUpdatedAt());

        $output->setType($object->getType()->getValue());

        /** @var null|string $status */
        $status = $object->getStatus() ? $object->getStatus()->getValue() : null;
        $output->setStatus($status);

        if ($object instanceof SteamWorkshopMod) {
            $output->setSource(ModSourceEnum::STEAM_WORKSHOP);
            $output->setItemId($object->getItemId());
        } elseif ($object instanceof DirectoryMod) {
            $output->setSource(ModSourceEnum::DIRECTORY);
            $output->setDirectory($object->getDirectory());
        }

        return $output;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return ModOutput::class === $to && $data instanceof ModInterface;
    }
}
