<?php

declare(strict_types=1);

namespace App\Service\RestCord;

use App\Service\RestCord\Enum\TokenTypeEnum;
use RestCord\DiscordClient;

class DiscordClientFactory
{
    public function createFromToken(string $token, TokenTypeEnum $tokenType): DiscordClient
    {
        return new DiscordClient([
            'token' => $token,
            'tokenType' => $tokenType->getValue(),
        ]);
    }
}
