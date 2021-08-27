<?php

declare(strict_types=1);

namespace App\Service\RestCord;

use RestCord\DiscordClient;

class DiscordClientFactory
{
    public function createBotClient(string $botToken): DiscordClient
    {
        return new DiscordClient([
            'token' => $botToken,
            'tokenType' => 'Bot',
        ]);
    }

    public function createUserClient(string $userToken): DiscordClient
    {
        return new DiscordClient([
            'token' => $userToken,
            'tokenType' => 'OAuth',
        ]);
    }
}
