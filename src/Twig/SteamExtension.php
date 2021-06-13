<?php

declare(strict_types=1);

namespace App\Twig;

use App\Service\Steam\Helper\SteamHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SteamExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('steam_workshop_item_url', [$this, 'getSteamWorkshopItemUrl']),
            new TwigFunction('steam_store_app_url', [$this, 'getSteamStoreAppUrl']),
        ];
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
