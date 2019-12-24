<?php

declare(strict_types=1);

namespace App\Service\RestCord;

use RestCord\DiscordClient;

class DiscordClientFactory
{
    public const TOKEN_TYPE_OAUTH = 'OAuth';

    public function createFromToken(string $token): DiscordClient
    {
        return new DiscordClient([
            'token' => $token,
            'tokenType' => self::TOKEN_TYPE_OAUTH,
        ]);
    }
}
