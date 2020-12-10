<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\SteamWorkshop;

use App\Service\SteamWorkshop\SteamWorkshopClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

/**
 * @internal
 * @covers \App\Service\SteamWorkshop\SteamWorkshopClient
 */
final class SteamWorkshopClientTest extends TestCase
{
    protected const ITEM_ID = 1934142795;
    protected const ITEM_NAME = 'ArmaForces - Mods';
    protected const ITEM_GAME_ID = 107410;

    /**
     * @test
     */
    public function getExistingWorkshopItemInfo(): void
    {
        $httpClient = HttpClient::create();
        $steamWorkshopClient = new SteamWorkshopClient($httpClient);
        $workshopItemInfoDto = $steamWorkshopClient->getWorkshopItemInfo(self::ITEM_ID);

        static::assertSame(self::ITEM_ID, $workshopItemInfoDto->getId());
        static::assertSame(self::ITEM_NAME, $workshopItemInfoDto->getName());
        static::assertSame(self::ITEM_GAME_ID, $workshopItemInfoDto->getGameId());
    }
}
