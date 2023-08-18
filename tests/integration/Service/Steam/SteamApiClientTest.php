<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Steam;

use App\Service\Steam\SteamApiClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

/**
 * @internal
 * @covers \App\Service\Steam\SteamApiClient
 */
final class SteamApiClientTest extends TestCase
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
        $steamWorkshopClient = new SteamApiClient($httpClient);
        $workshopItemInfoDto = $steamWorkshopClient->getWorkshopItemInfo($this::ITEM_ID);

        self::assertSame($this::ITEM_ID, $workshopItemInfoDto->getId());
        self::assertSame($this::ITEM_NAME, $workshopItemInfoDto->getName());
        self::assertSame($this::ITEM_GAME_ID, $workshopItemInfoDto->getGameId());
    }
}
