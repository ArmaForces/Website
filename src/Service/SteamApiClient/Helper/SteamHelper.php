<?php

declare(strict_types=1);

namespace App\Service\SteamApiClient\Helper;

use App\Service\SteamApiClient\Helper\Exception\InvalidAppUrlFormatException;
use App\Service\SteamApiClient\Helper\Exception\InvalidWorkshopItemUrlFormatException;

class SteamHelper
{
    public const ITEM_URL_REGEX = '~^https://steamcommunity\.com/(?:sharedfiles|workshop)/filedetails/\?id=(\d+)$~';
    public const APP_URL_REGEX = '~^https://store\.steampowered\.com/app/(\d+)~';

    public static function profileIdToProfileUrl(int $profileId): string
    {
        return "https://steamcommunity.com/profiles/{$profileId}";
    }

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

        throw new InvalidWorkshopItemUrlFormatException(sprintf('Invalid item URL format for: "%s"', $itemUrl));
    }

    public static function appIdToAppUrl(int $appId): string
    {
        return "https://store.steampowered.com/app/{$appId}";
    }

    public static function appUrlToAppId(string $appUrl): int
    {
        if (1 === preg_match(self::APP_URL_REGEX, $appUrl, $matches) && 2 === \count($matches)) {
            return (int) $matches[1];
        }

        throw new InvalidAppUrlFormatException(sprintf('Invalid app URL format for: "%s"', $appUrl));
    }
}
