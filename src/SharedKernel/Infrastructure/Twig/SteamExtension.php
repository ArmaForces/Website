<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Twig;

use App\SharedKernel\Infrastructure\Service\Steam\Helper\SteamHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SteamExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('steam_profile_id_url', [$this, 'getSteamProfileUrl']),
            new TwigFunction('steam_workshop_item_url', [$this, 'getSteamWorkshopItemUrl']),
            new TwigFunction('steam_store_app_url', [$this, 'getSteamStoreAppUrl']),
        ];
    }

    public function getSteamProfileUrl(int $profileId): string
    {
        return SteamHelper::profileIdToProfileUrl($profileId);
    }

    public function getSteamWorkshopItemUrl(int $itemId): string
    {
        return SteamHelper::itemIdToItemUrl($itemId);
    }

    public function getSteamStoreAppUrl(int $appId): string
    {
        return SteamHelper::appIdToAppUrl($appId);
    }
}
