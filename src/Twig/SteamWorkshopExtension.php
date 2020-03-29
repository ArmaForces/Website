<?php

declare(strict_types=1);

namespace App\Twig;

use App\Service\SteamWorkshop\Helper\SteamWorkshopHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SteamWorkshopExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('steam_workshop_item_url', [$this, 'getSteamWorkshopItemUrl']),
        ];
    }

    public function getSteamWorkshopItemUrl(int $itemId): string
    {
        return SteamWorkshopHelper::itemIdToItemUrl($itemId);
    }
}
