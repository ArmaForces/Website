<?php

declare(strict_types=1);

namespace App\Shared\Security\Enum;

enum ScopeEnum: string
{
    case IDENTIFY = 'identify';
    case EMAIL = 'email';
    case GUILDS = 'guilds';
    case CONNECTIONS = 'connections';
}
