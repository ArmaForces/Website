<?php

declare(strict_types=1);

namespace App\Entity\Mod\Enum;

enum ModTypeEnum: string
{
    // Loaded by the server ONLY
    case SERVER_SIDE = 'server_side';

    // Loaded by the server. Required from the client
    case REQUIRED = 'required';

    // Loaded by the server. Optional for client
    case OPTIONAL = 'optional';

    // Loaded by the client ONLY
    case CLIENT_SIDE = 'client_side';
}
