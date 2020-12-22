<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\SteamWorkshop;

use App\Service\SteamWorkshop\Exception\ItemNotFoundException;
use App\Service\SteamWorkshop\SteamWorkshopClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

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
    public function getWorkshopItemInfo_existingItem_returnsItemDto(): void
    {
        $responsePayload = $this->mockResponsePayload(1, self::ITEM_NAME, self::ITEM_GAME_ID);

        $httpClient = $this->mockHttpClient($responsePayload);
        $steamWorkshopClient = new SteamWorkshopClient($httpClient);
        $workshopItemInfoDto = $steamWorkshopClient->getWorkshopItemInfo(self::ITEM_ID);

        static::assertSame(self::ITEM_ID, $workshopItemInfoDto->getId());
        static::assertSame(self::ITEM_NAME, $workshopItemInfoDto->getName());
        static::assertSame(self::ITEM_GAME_ID, $workshopItemInfoDto->getGameId());
    }

    /**
     * @test
     */
    public function getWorkshopItemInfo_nonExistingItem_throwsException(): void
    {
        $responsePayload = $this->mockResponsePayload(0, self::ITEM_NAME, self::ITEM_GAME_ID);

        $httpClient = $this->mockHttpClient($responsePayload);
        $steamWorkshopClient = new SteamWorkshopClient($httpClient);

        $this->expectException(ItemNotFoundException::class);
        $this->expectExceptionMessage(sprintf('No items found by item id "%s"!', $this::ITEM_ID));

        $steamWorkshopClient->getWorkshopItemInfo(self::ITEM_ID);
    }

    private function mockResponsePayload(int $resultCount, string $itemName, int $itemGameId): array
    {
        return [
            'response' => [
                'resultcount' => $resultCount,
                'publishedfiledetails' => [
                    [
                        'title' => $itemName,
                        'creator_app_id' => $itemGameId,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return HttpClientInterface|MockObject
     */
    private function mockHttpClient(array $responsePayload): MockObject
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('toArray')->willReturn($responsePayload);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($response);

        return $httpClient;
    }
}
