<?php

declare(strict_types=1);

namespace App\ModManagement\Infrastructure\Service\LegacyModListImport;

use App\ModManagement\Domain\Model\Mod\DirectoryMod;
use App\ModManagement\Domain\Model\Mod\Enum\ModTypeEnum;
use App\ModManagement\Domain\Model\Mod\ModInterface;
use App\ModManagement\Domain\Model\Mod\SteamWorkshopMod;
use App\ModManagement\Infrastructure\Service\LegacyModListImport\Dto\ModCsvEntryDto;
use Ramsey\Uuid\Uuid;

/**
 * @see https://github.com/ArmaForces/modlist/blob/master/src/util/mods.js
 */
class DtoToEntityConverter
{
    public function convert(ModCsvEntryDto $modCsvEntryDto): ModInterface
    {
        $id = $modCsvEntryDto->getId();
        $isSteamWorkshopMod = 'local' !== strtolower($id);
        $name = trim($modCsvEntryDto->getName());
        $isOptional = 'true' === strtolower($modCsvEntryDto->getIsOptional());
        $isServerSide = 'true' === strtolower($modCsvEntryDto->getIsServerSide());
        $isMap = 'true' === strtolower($modCsvEntryDto->getIsServerSide());

        $modType = ModTypeEnum::REQUIRED;
        if ($isServerSide) {
            $modType = ModTypeEnum::SERVER_SIDE;
        } elseif ($isMap) {
            $modType = ModTypeEnum::OPTIONAL;
        } elseif ($isOptional) {
            $modType = ModTypeEnum::CLIENT_SIDE;
        }

        /** @var ModTypeEnum $modTypeEnum */
        $modTypeEnum = ModTypeEnum::get($modType);

        if ($isSteamWorkshopMod) {
            return new SteamWorkshopMod(Uuid::uuid4(), $name, $modTypeEnum, (int) $id);
        }

        return new DirectoryMod(Uuid::uuid4(), $name, $modTypeEnum, $name);
    }
}
