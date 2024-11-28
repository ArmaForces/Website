<?php

declare(strict_types=1);

namespace App\Mods\Entity\ModList\Enum;

enum ModListTypeEnum: string
{
    case STANDARD = 'standard';

    case EXTERNAL = 'external';
}
