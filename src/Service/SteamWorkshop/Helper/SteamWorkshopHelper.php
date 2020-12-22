<?php

declare(strict_types=1);

namespace App\Service\SteamWorkshop\Helper;

use App\Service\SteamWorkshop\Helper\Exception\InvalidItemUrlFormatException;

class SteamWorkshopHelper
{
    public const ITEM_URL_REGEX = '~https:\/\/steamcommunity\.com\/(?:sharedfiles|workshop)\/filedetails\/\?id=(\d+)~';

    public static function isValidItemUrl(string $itemUrl): bool
    {
        return 1 === preg_match(self::ITEM_URL_REGEX, $itemUrl);
    }

    public static function itemIdToItemUrl(int $itemId): string
    {
        return "https://steamcommunity.com/sharedfiles/filedetails/?id={$itemId}";
    }

    public static function itemUrlToItemId(string $itemUrl): int
    {
        if (1 === preg_match(self::ITEM_URL_REGEX, $itemUrl, $matches) && 2 === \count($matches)) {
            return (int) $matches[1];
        }

        throw new InvalidItemUrlFormatException(sprintf('Invalid item URL format for: "%s"', $itemUrl));
    }
}
