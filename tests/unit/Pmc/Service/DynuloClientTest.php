<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pmc\Service;

use App\Pmc\Service\Dynulo\DynuloClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @internal
 * @covers \App\Pmc\Service\Dynulo\DynuloClient
 */
final class DynuloClientTest extends TestCase
{
    /**
     * @test
     */
    public function getItems_returnsItemsDtos(): void
    {
        $responsePayload = $this->mockItemsPayload();
        $httpClient = $this->mockHttpClient($responsePayload);

        $dynuloClient = new DynuloClient($httpClient, 'https://dev.dynulo.com/pmc/', '');
        $items = $dynuloClient->getItems();

        static::assertSame('cup_arifle_m16a1', $items[0]->getClass());
        static::assertSame('M16A1', $items[0]->getPretty());
        static::assertSame(1800, $items[0]->getCost());
        static::assertSame(['trait1', 'trait2'], $items[0]->getTraits());
        static::assertSame(1609347416, $items[0]->getCreated()->getTimestamp());

        static::assertSame('cup_arifle_m4a1_black', $items[1]->getClass());
        static::assertSame('M4A1', $items[1]->getPretty());
        static::assertSame(1500, $items[1]->getCost());
        static::assertSame([], $items[1]->getTraits());
        static::assertSame(1609346164, $items[1]->getCreated()->getTimestamp());
    }

    /**
     * @test
     */
    public function getItem_returnsItemDto(): void
    {
        $responsePayload = $this->mockItemPayload();
        $httpClient = $this->mockHttpClient($responsePayload);

        $dynuloClient = new DynuloClient($httpClient, 'https://dev.dynulo.com/pmc/', '');
        $item = $dynuloClient->getItem('cup_arifle_m16a1');

        static::assertSame('cup_arifle_m16a1', $item->getClass());
        static::assertSame('M16A1', $item->getPretty());
        static::assertSame(1800, $item->getCost());
        static::assertSame(['trait1', 'trait2'], $item->getTraits());
        static::assertSame(1609347416, $item->getCreated()->getTimestamp());
    }

    /**
     * @test
     */
    public function getPlayers_returnsPlayersDtos(): void
    {
        $responsePayload = $this->mockPlayersPayload();
        $httpClient = $this->mockHttpClient($responsePayload);

        $dynuloClient = new DynuloClient($httpClient, 'https://dev.dynulo.com/pmc/', '');
        $items = $dynuloClient->getPlayers();

        static::assertSame(123, $items[0]->getPlayer());
        static::assertSame('player1', $items[0]->getNickname());
        static::assertSame(1602902078, $items[0]->getCreated()->getTimestamp());

        static::assertSame(456, $items[1]->getPlayer());
        static::assertSame('player2', $items[1]->getNickname());
        static::assertSame(1608297858, $items[1]->getCreated()->getTimestamp());
    }

    /**
     * @test
     */
    public function getPlayer_returnsPlayerDto(): void
    {
        $responsePayload = $this->mockPlayersPayload()[0];
        $httpClient = $this->mockHttpClient($responsePayload);

        $dynuloClient = new DynuloClient($httpClient, 'https://dev.dynulo.com/pmc/', '');
        $item = $dynuloClient->getPlayer(123);

        static::assertSame(123, $item->getPlayer());
        static::assertSame('player1', $item->getNickname());
        static::assertSame(1602902078, $item->getCreated()->getTimestamp());
    }

    /**
     * @test
     */
    public function getPlayerPurchases_returnsPlayerPurchasesDtos(): void
    {
        $responsePayload = $this->mockPlayerPurchasesPayload();
        $httpClient = $this->mockHttpClient($responsePayload);

        $dynuloClient = new DynuloClient($httpClient, 'https://dev.dynulo.com/pmc/', '');
        $items = $dynuloClient->getPlayerPurchases(123);

        static::assertSame(123, $items[0]->getPlayer());
        static::assertSame(5, $items[0]->getAmount());
        static::assertSame(1, $items[0]->getQuantity());
        static::assertSame('ace_earplugs', $items[0]->getClass());
        static::assertSame(1605402069, $items[0]->getCreated()->getTimestamp());

        static::assertSame(123, $items[1]->getPlayer());
        static::assertSame(15, $items[1]->getAmount());
        static::assertSame(2, $items[1]->getQuantity());
        static::assertSame('ace_splint', $items[1]->getClass());
        static::assertSame(1605402069, $items[1]->getCreated()->getTimestamp());
    }

    private function mockItemsPayload(): array
    {
        return [
            [
                'class' => 'cup_arifle_m16a1',
                'pretty' => 'M16A1',
                'cost' => 1800,
                'traits' => 'trait1|trait2',
                'created' => '2020-12-30T16:56:56.973535',
            ],
            [
                'class' => 'cup_arifle_m4a1_black',
                'pretty' => 'M4A1',
                'cost' => 1500,
                'traits' => '',
                'created' => '2020-12-30T16:36:04.876307',
            ],
        ];
    }

    private function mockItemPayload(): array
    {
        return
            [
                'class' => 'cup_arifle_m16a1',
                'pretty' => 'M16A1',
                'cost' => 1800,
                'traits' => 'trait1|trait2',
                'created' => '2020-12-30T16:56:56.973535',
            ];
    }

    private function mockPlayersPayload(): array
    {
        return [
            [
                'player' => 123,
                'nickname' => 'player1',
                'created' => '2020-10-17T03:34:38.612335',
            ],
            [
                'player' => 456,
                'nickname' => 'player2',
                'created' => '2020-12-18T13:24:18.401222',
            ],
        ];
    }

    private function mockPlayerPurchasesPayload(): array
    {
        return [
            [
                'player' => 123,
                'amount' => 5,
                'quantity' => 1,
                'class' => 'ace_earplugs',
                'created' => '2020-11-15T01:01:09.781098',
            ],
            [
                'player' => 123,
                'amount' => 15,
                'quantity' => 2,
                'class' => 'ace_splint',
                'created' => '2020-11-15T01:01:09.781098',
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
