<?php

declare(strict_types=1);

namespace App\Entity\Mod\Enum;

use Elao\Enum\AutoDiscoveredValuesTrait;
use Elao\Enum\Enum;

final class ModStatusEnum extends Enum
{
    use AutoDiscoveredValuesTrait;

    /**
     * Mod is old, not supported or there is a better alternative available.
     */
    public const DEPRECATED = 'deprecated';

    /**
     * Mod is broken. Use at your own risk!
     */
    public const BROKEN = 'broken';

    /**
     * Disabled mods are still visible in Mod Lists/Groups but are excluded
     * from customization view and Launcher Presets.
     */
    public const DISABLED = 'disabled';
}
