<?php

declare(strict_types=1);

namespace App\Tests\Service\SteamWorkshop;

use App\Service\SteamWorkshop\SteamWorkshopClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

/**
 * @internal
 * @coversNothing
 */
class SteamWorkshopClientTest extends TestCase
{
    protected const ITEM_URL = 'https://steamcommunity.com/sharedfiles/filedetails/?id=1934142795';
    protected const ITEM_ID = 1934142795;
    protected const ITEM_NAME = 'ArmaForces - Mods';
    protected const ITEM_GAME_ID = 107410;

    public function testGetWorkshopItemInfo(): void
    {
        $httpClient = HttpClient::create();
        $steamWorkshopClient = new SteamWorkshopClient($httpClient);
        $workshopItemInfoDto = $steamWorkshopClient->getWorkshopItemInfo(self::ITEM_URL);

        $this->assertEquals(self::ITEM_ID, $workshopItemInfoDto->getId());
        $this->assertEquals(self::ITEM_NAME, $workshopItemInfoDto->getName());
        $this->assertEquals(self::ITEM_GAME_ID, $workshopItemInfoDto->getGameId());
    }
}
