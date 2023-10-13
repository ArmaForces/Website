<?php

declare(strict_types=1);

namespace App\Service\SteamApiClient\Exception;

class AppNotFoundException extends ClientException
{
    public static function createForAppId(int $appId): self
    {
        return new self(sprintf('No apps found by app id "%s"!', $appId));
    }
}
