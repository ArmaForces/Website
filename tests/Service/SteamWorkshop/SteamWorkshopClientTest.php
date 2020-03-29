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
final class SteamWorkshopClientTest extends TestCase
{
    protected const ITEM_ID = 1934142795;
    protected const ITEM_NAME = 'ArmaForces - Mods';
    protected const ITEM_GAME_ID = 107410;

    public function testGetExistingWorkshopItemInfo(): void
    {
        $httpClient = HttpClient::create();
        $steamWorkshopClient = new SteamWorkshopClient($httpClient);
        $workshopItemInfoDto = $steamWorkshopClient->getWorkshopItemInfo(self::ITEM_ID);

        static::assertSame(self::ITEM_ID, $workshopItemInfoDto->getId());
        static::assertSame(self::ITEM_NAME, $workshopItemInfoDto->getName());
        static::assertSame(self::ITEM_GAME_ID, $workshopItemInfoDto->getGameId());
    }
}
