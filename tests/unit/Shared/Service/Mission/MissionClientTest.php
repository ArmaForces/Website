<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Service\Mission;

use App\Shared\Service\Mission\Enum\MissionStateEnum;
use App\Shared\Service\Mission\MissionClient;
use App\Shared\Service\Mission\MissionStore;
use Codeception\Test\Unit;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class MissionClientTest extends Unit
{
    /**
     * @dataProvider provideGetCurrentMissionCases
     */
    public function testGetCurrentMission(array $missionData, ?string $expectedTitle, ?string $expectedModlist): void
    {
        $mockHttpClient = $this->mockHttpClient($missionData);
        $mockStore = $this->getMockBuilder(MissionStore::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $client = new MissionClient($mockHttpClient, $mockStore, 'https://2137.xd');

        $currentMission = $client->getCurrentMission();

        if ($expectedTitle) {
            self::assertSame($expectedTitle, $currentMission->getTitle());
            self::assertSame($expectedModlist, $currentMission->getModlist());
        } else {
            self::assertNull($currentMission);
        }
    }

    public function provideGetCurrentMissionCases(): iterable
    {
        $now = new \DateTimeImmutable();
        $dateFormat = 'Y-m-d\TH:i:s';

        return
        [
            'current mission available, with modlistName' => [[
                [
                    'id' => 1,
                    'title' => 'Mission in future 1',
                    'date' => $now->add(new \DateInterval('P2D'))->format($dateFormat),
                    'closeDate' => $now->add(new \DateInterval('P1D'))->format($dateFormat),
                    'description' => '',
                    'modlistName' => '',
                    'image' => '',
                    'freeSlots' => 0,
                    'allSlots' => 0,
                    'state' => MissionStateEnum::OPEN,
                ],
                [
                    'id' => 2,
                    'title' => 'Current mission',
                    'date' => $now->add(new \DateInterval('PT1H'))->format($dateFormat),
                    'closeDate' => $now->sub(new \DateInterval('P1D'))->format($dateFormat),
                    'description' => '',
                    'modlistName' => 'Default',
                    'image' => '',
                    'freeSlots' => 0,
                    'allSlots' => 0,
                    'state' => MissionStateEnum::OPEN,
                ],
                [
                    'id' => 3,
                    'title' => 'Old mission 1',
                    'date' => $now->sub(new \DateInterval('P2D'))->format($dateFormat),
                    'closeDate' => $now->sub(new \DateInterval('P3D'))->format($dateFormat),
                    'description' => '',
                    'modlistName' => '',
                    'image' => '',
                    'freeSlots' => 0,
                    'allSlots' => 0,
                    'state' => MissionStateEnum::ARCHIVED,
                ],
            ], 'Current mission', 'Default'],
            'current mission available, without modlistName' => [[
                [
                    'id' => 2,
                    'title' => 'Current mission 2',
                    'date' => $now->add(new \DateInterval('PT1H'))->format($dateFormat),
                    'closeDate' => $now->sub(new \DateInterval('P1D'))->format($dateFormat),
                    'description' => '',
                    'modlistName' => null,
                    'modlist' => 'https://armaforces.com/standard-mod-list/Default2',
                    'image' => '',
                    'freeSlots' => 0,
                    'allSlots' => 0,
                    'state' => MissionStateEnum::CLOSED,
                ],
                [],
            ], 'Current mission 2', 'Default2'],
            'no current mission' => [[
                [
                    'id' => 1,
                    'title' => 'Mission in future 1',
                    'date' => $now->add(new \DateInterval('P2D'))->format($dateFormat),
                    'closeDate' => $now->add(new \DateInterval('P1D'))->format($dateFormat),
                    'description' => '',
                    'modlistName' => '',
                    'image' => '',
                    'freeSlots' => 0,
                    'allSlots' => 0,
                    'state' => MissionStateEnum::OPEN,
                ],
                [
                    'id' => 2,
                    'title' => 'Old mission 1',
                    'date' => $now->sub(new \DateInterval('P2D'))->format($dateFormat),
                    'closeDate' => $now->sub(new \DateInterval('P3D'))->format($dateFormat),
                    'description' => '',
                    'modlistName' => '',
                    'image' => '',
                    'freeSlots' => 0,
                    'allSlots' => 0,
                    'state' => MissionStateEnum::ARCHIVED,
                ],
                [], // empty element will explode the deserialization process, so this basically makes sure that we're not iterating more than we should ;)
            ], null, null],
            'empty data' => [[], null, null],
        ];
    }

    private function mockHttpClient(array $responsePayload): HttpClientInterface
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getContent')->willReturn(json_encode($responsePayload, JSON_THROW_ON_ERROR));

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($response);

        return $httpClient;
    }
}
