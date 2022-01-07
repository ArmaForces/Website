<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Controller\Attendance;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\SharedKernel\Infrastructure\Test\Enum\RouteEnum;
use App\SharedKernel\Infrastructure\Test\Traits\DataProvidersTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
final class CreateAttendanceActionTest extends ApiTestCase
{
    use DataProvidersTrait;

    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient([], ['headers' => ['Accept' => 'application/json']]);
    }

    /**
     * @test
     * @dataProvider registeredUsersDataProvider
     */
    public function createAttendanceAction_validApiKey_returnsSuccessfulResponse(): void
    {
        $this->client->request(Request::METHOD_POST, RouteEnum::API_ATTENDANCE_CREATE, [
            'headers' => [
                'X-API-KEY' => 'test_key',
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
        $this->client->request(Request::METHOD_POST, RouteEnum::API_ATTENDANCE_CREATE, [
            'headers' => [
                'X-API-KEY' => '',
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
        $this->client->request(Request::METHOD_POST, RouteEnum::API_ATTENDANCE_CREATE, [
            'headers' => [
                'X-API-KEY' => 'test_key',
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
