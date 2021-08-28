<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Controller\Attendance;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Test\Enum\RouteEnum;
use App\Test\Traits\DataProvidersTrait;
use App\Test\Traits\ServicesTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
final class CreateAttendanceActionTest extends ApiTestCase
{
    use ServicesTrait;
    use DataProvidersTrait;

    /**
     * @test
     * @dataProvider registeredUsersDataProvider
     */
    public function createAttendanceAction_validApiKey_returnsSuccessfulResponse(): void
    {
        $client = $this::getClient();
        $client->request(Request::METHOD_POST, RouteEnum::API_ATTENDANCE_CREATE, [
            'headers' => [
                'X-API-KEY' => 'test_key',
                'Accept' => 'application/json',
            ],
            'json' => [
                'missionId' => 'mission_99',
                'playerId' => 76561198048200529,
            ],
        ]);

        $this::assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    /**
     * @test
     * @dataProvider registeredUsersDataProvider
     */
    public function createAttendanceAction_invalidApiKey_returnsForbiddenResponse(): void
    {
        $client = $this::getClient();
        $client->request(Request::METHOD_POST, RouteEnum::API_ATTENDANCE_CREATE, [
            'headers' => [
                'X-API-KEY' => '',
                'Accept' => 'application/json',
            ],
            'json' => [
                'missionId' => 'mission_99',
                'playerId' => 76561198048200529,
            ],
        ]);

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        $this::assertJsonContains([
            'detail' => 'Invalid or missing API key provided!',
        ]);
    }

    /**
     * @test
     * @dataProvider registeredUsersDataProvider
     */
    public function createAttendanceAction_duplicatedEntry_returnsUnprocessableEntityResponse(): void
    {
        $client = $this::getClient();
        $client->request(Request::METHOD_POST, RouteEnum::API_ATTENDANCE_CREATE, [
            'headers' => [
                'X-API-KEY' => 'test_key',
                'Accept' => 'application/json',
            ],
            'json' => [
                'missionId' => 'mission_1',
                'playerId' => 76561198048200529,
            ],
        ]);

        $this::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this::assertJsonContains([
            'detail' => 'Attendance of player "76561198048200529" in mission "mission_1" already exists',
        ]);
    }
}
