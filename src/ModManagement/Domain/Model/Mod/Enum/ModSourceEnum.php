<?php

declare(strict_types=1);

namespace App\ModManagement\Domain\Model\Mod\Enum;

use Elao\Enum\AutoDiscoveredValuesTrait;
use Elao\Enum\Enum;

final class ModSourceEnum extends Enum
{
    use AutoDiscoveredValuesTrait;

    public const STEAM_WORKSHOP = 'steam_workshop';

    public const DIRECTORY = 'directory';
}
