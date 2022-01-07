<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Security\Enum;

class ScopeEnum
{
    public const IDENTIFY = 'identify';
    public const EMAIL = 'email';
    public const GUILDS = 'guilds';
    public const CONNECTIONS = 'connections';
}
