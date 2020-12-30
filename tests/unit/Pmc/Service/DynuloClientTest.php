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

        $dynuloClient = new DynuloClient($httpClient, 'https://dev.dynulo.com/pmc', '');
        $items = $dynuloClient->getItems();

        self::assertEquals('cup_arifle_m16a1', $items[0]->getClass());
        self::assertEquals('M16A1', $items[0]->getPretty());
        self::assertEquals(1800, $items[0]->getCost());
        self::assertEquals(['trait1', 'trait2'], $items[0]->getTraits());

        self::assertEquals('cup_arifle_m4a1_black', $items[1]->getClass());
        self::assertEquals('M4A1', $items[1]->getPretty());
        self::assertEquals(1500, $items[1]->getCost());
        self::assertEquals([], $items[1]->getTraits());
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
