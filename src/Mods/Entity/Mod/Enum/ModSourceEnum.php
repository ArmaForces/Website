<?php

declare(strict_types=1);

namespace App\Mods\Entity\Mod\Enum;

enum ModSourceEnum: string
{
    case STEAM_WORKSHOP = 'steam_workshop';

    case DIRECTORY = 'directory';
}
