<?php

declare(strict_types=1);

namespace App\Service\SteamApiClient\Exception;

class WorkshopItemNotFoundException extends ClientException
{
    public static function createForItemId(int $itemId): self
    {
        return new self(sprintf('No items found by item id "%s"!', $itemId));
    }
}
