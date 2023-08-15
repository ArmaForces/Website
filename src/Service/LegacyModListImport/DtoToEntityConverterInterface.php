<?php

declare(strict_types=1);

namespace App\Service\LegacyModListImport;

use App\Entity\Mod\AbstractMod;
use App\Service\LegacyModListImport\Dto\ModCsvEntryDto;

interface DtoToEntityConverterInterface
{
    public function convert(ModCsvEntryDto $modCsvEntryDto): AbstractMod;
}
