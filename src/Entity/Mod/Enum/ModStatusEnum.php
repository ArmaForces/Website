<?php

declare(strict_types=1);

namespace App\Entity\Mod\Enum;

enum ModStatusEnum: string
{
    /**
     * Mod is old, not supported or there is a better alternative available.
     */
    case DEPRECATED = 'deprecated';

    /**
     * Mod is broken. Use at your own risk!
     */
    case BROKEN = 'broken';

    /**
     * Disabled mods are still visible in Mod Lists/Groups but are excluded
     * from customization view and Launcher Presets.
     */
    case DISABLED = 'disabled';
}
