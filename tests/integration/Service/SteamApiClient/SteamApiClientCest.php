<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\SteamApiClient;

use App\Service\SteamApiClient\Exception\AppNotFoundException;
use App\Service\SteamApiClient\Exception\WorkshopItemNotFoundException;
use App\Service\SteamApiClient\SteamApiClientInterface;
use App\Tests\IntegrationTester;

class SteamApiClientCest
{
    private const ITEM_ID = 1934142795;
    private const ITEM_NAME = 'ArmaForces - Mods';
    private const ITEM_GAME_ID = 107410;

    private const APP_ID = 1681170;
    private const APP_NAME = 'Arma 3 Creator DLC: Western Sahara';
    private const APP_TYPE = 'dlc';
    private const APP_GAME_ID = 107410;

    private SteamApiClientInterface $steamApiClient;

    public function _before(IntegrationTester $I): void
    {
        $this->steamApiClient = $I->grabService(SteamApiClientInterface::class);
    }

    public function testGetExistingWorkshopItemInfo(IntegrationTester $I): void
    {
        $workshopItemInfoDto = $this->steamApiClient->getWorkshopItemInfo($this::ITEM_ID);

        $I->assertSame($this::ITEM_ID, $workshopItemInfoDto->getId());
        $I->assertSame($this::ITEM_NAME, $workshopItemInfoDto->getName());
        $I->assertSame($this::ITEM_GAME_ID, $workshopItemInfoDto->getGameId());
    }

    public function testGetNonExistingWorkshopItemInfo(IntegrationTester $I): void
    {
        $I->expectThrowable(
            new WorkshopItemNotFoundException(sprintf('No items found by item id "%s"!', 1)),
            fn () => $this->steamApiClient->getWorkshopItemInfo(1)
        );
    }

    public function testGetExistingAppInfo(IntegrationTester $I): void
    {
        $appInfoDto = $this->steamApiClient->getAppInfo($this::APP_ID);

        $I->assertSame($this::APP_ID, $appInfoDto->getId());
        $I->assertSame($this::APP_NAME, $appInfoDto->getName());
        $I->assertSame($this::APP_TYPE, $appInfoDto->getType());
        $I->assertSame($this::APP_GAME_ID, $appInfoDto->getGameId());
    }

    public function testGetNonExistingAppInfo(IntegrationTester $I): void
    {
        $I->expectThrowable(
            new AppNotFoundException(sprintf('No apps found by app id "%s"!', 1)),
            fn () => $this->steamApiClient->getAppInfo(1)
        );
    }
}
