<?php

declare(strict_types=1);

namespace App\Service\RestCord;

use RestCord\DiscordClient;

class DiscordClientFactory
{
    public const TOKEN_TYPE_OAUTH = 'OAuth';
    public const TOKEN_TYPE_BOT = 'Bot';

    public function createFromToken(string $token, string $tokenType = self::TOKEN_TYPE_BOT): DiscordClient
    {
        return new DiscordClient([
            'token' => $token,
            'tokenType' => $tokenType,
        ]);
    }
}
