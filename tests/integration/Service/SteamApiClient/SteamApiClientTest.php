<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\SteamApiClient;

use App\Service\SteamApiClient\Exception\AppNotFoundException;
use App\Service\SteamApiClient\Exception\WorkshopItemNotFoundException;
use App\Service\SteamApiClient\SteamApiClientInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 * @covers \App\Service\SteamApiClient\SteamApiClient
 */
final class SteamApiClientTest extends KernelTestCase
{
    private const ITEM_ID = 1934142795;
    private const ITEM_NAME = 'ArmaForces - Mods';
    private const ITEM_GAME_ID = 107410;

    private const APP_ID = 1227700;
    private const APP_NAME = 'Arma 3 Creator DLC: S.O.G. Prairie Fire';
    private const APP_TYPE = 'dlc';
    private const APP_GAME_ID = 107410;

    private SteamApiClientInterface $steamApiClient;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $this->steamApiClient = self::getContainer()->get(SteamApiClientInterface::class);
    }

    /**
     * @test
     */
    public function getWorkshopItemInfo_existingItem_returnsItemDto(): void
    {
        $workshopItemInfoDto = $this->steamApiClient->getWorkshopItemInfo($this::ITEM_ID);

        self::assertSame($this::ITEM_ID, $workshopItemInfoDto->getId());
        self::assertSame($this::ITEM_NAME, $workshopItemInfoDto->getName());
        self::assertSame($this::ITEM_GAME_ID, $workshopItemInfoDto->getGameId());
    }

    /**
     * @test
     */
    public function getWorkshopItemInfo_nonExistingItem_throwsException(): void
    {
        $this->expectException(WorkshopItemNotFoundException::class);
        $this->expectExceptionMessage(sprintf('No items found by item id "%s"!', 1));

        $this->steamApiClient->getWorkshopItemInfo(1);
    }

    /**
     * @test
     */
    public function getAppInfo_existingApp_returnsAppInfoDto(): void
    {
        $appInfoDto = $this->steamApiClient->getAppInfo($this::APP_ID);

        self::assertSame($this::APP_ID, $appInfoDto->getId());
        self::assertSame($this::APP_NAME, $appInfoDto->getName());
        self::assertSame($this::APP_TYPE, $appInfoDto->getType());
        self::assertSame($this::APP_GAME_ID, $appInfoDto->getGameId());
    }

    /**
     * @test
     */
    public function getAppInfo_nonExistingApp_throwsException(): void
    {
        $this->expectException(AppNotFoundException::class);
        $this->expectExceptionMessage(sprintf('No apps found by app id "%s"!', 1));

        $this->steamApiClient->getAppInfo(1);
    }
}
