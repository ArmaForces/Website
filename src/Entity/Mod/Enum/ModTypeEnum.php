<?php

declare(strict_types=1);

namespace App\Entity\Mod\Enum;

use Elao\Enum\AutoDiscoveredValuesTrait;
use Elao\Enum\Enum;

final class ModTypeEnum extends Enum
{
    use AutoDiscoveredValuesTrait;

    // Loaded by the server ONLY
    public const SERVER_SIDE = 'server_side';

    // Loaded by the server. Required from the client
    public const REQUIRED = 'required';

    // Loaded by the server. Optional for client
    public const OPTIONAL = 'optional';

    // Loaded by the client ONLY
    public const CLIENT_SIDE = 'client_side';
}
