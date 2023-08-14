<?php

declare(strict_types=1);

namespace App\Service\LegacyModListImport;

use App\Entity\Mod\AbstractMod;
use App\Entity\Mod\DirectoryMod;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\SteamWorkshopMod;
use App\Service\LegacyModListImport\Dto\ModCsvEntryDto;
use Ramsey\Uuid\Uuid;

/**
 * @see https://github.com/ArmaForces/modlist/blob/master/src/util/mods.js
 */
class DtoToEntityConverter
{
    public function convert(ModCsvEntryDto $modCsvEntryDto): AbstractMod
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
            return new SteamWorkshopMod(Uuid::uuid4(), $name, null, null, $modTypeEnum, (int) $id);
        }

        return new DirectoryMod(Uuid::uuid4(), $name, null, null, $name);
    }
}
