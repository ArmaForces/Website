<?php

declare(strict_types=1);

namespace App\Service\RestCord\Enum;

use Elao\Enum\AutoDiscoveredValuesTrait;
use Elao\Enum\Enum;

class TokenTypeEnum extends Enum
{
    use AutoDiscoveredValuesTrait;

    public const TOKEN_TYPE_OAUTH = 'OAuth';
    public const TOKEN_TYPE_BOT = 'Bot';
}
