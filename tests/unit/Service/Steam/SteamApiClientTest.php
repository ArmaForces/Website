<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Steam;

use App\Service\Steam\Exception\WorkshopItemNotFoundException;
use App\Service\Steam\SteamApiClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

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
    public function getWorkshopItemInfo_existingItem_returnsItemDto(): void
    {
        $responsePayload = $this->mockResponsePayload(1, $this::ITEM_NAME, $this::ITEM_GAME_ID);

        $httpClient = $this->mockHttpClient($responsePayload);
        $steamWorkshopClient = new SteamApiClient($httpClient);
        $workshopItemInfoDto = $steamWorkshopClient->getWorkshopItemInfo($this::ITEM_ID);

        $this::assertSame($this::ITEM_ID, $workshopItemInfoDto->getId());
        $this::assertSame($this::ITEM_NAME, $workshopItemInfoDto->getName());
        $this::assertSame($this::ITEM_GAME_ID, $workshopItemInfoDto->getGameId());
    }

    /**
     * @test
     */
    public function getWorkshopItemInfo_nonExistingItem_throwsException(): void
    {
        $responsePayload = $this->mockResponsePayload(0, $this::ITEM_NAME, $this::ITEM_GAME_ID);

        $httpClient = $this->mockHttpClient($responsePayload);
        $steamWorkshopClient = new SteamApiClient($httpClient);

        $this->expectException(WorkshopItemNotFoundException::class);
        $this->expectExceptionMessage(sprintf('No items found by item id "%s"!', $this::ITEM_ID));

        $steamWorkshopClient->getWorkshopItemInfo($this::ITEM_ID);
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
